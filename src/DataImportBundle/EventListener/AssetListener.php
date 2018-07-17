<?php

namespace DataImportBundle\EventListener;

use Pimcore\Event\Model\ElementEventInterface;
use Pimcore\Event\Model\AssetEvent;
use DataImportBundle\Model\Mapping;
use DataImportBundle\Model\CsvConversion;
use NotificationBundle\Model\Notification;
use Symfony\Component\Yaml\Yaml;

class AssetListener
{

    /**
     * @var \Pimcore\Log\ApplicationLogger
     */
    protected $logger = null;

    /**
     * Consist of all the parent folder name for current asset
     * @var array
     */
    protected $assetParents = [];

    /**
     * Holds the current asset object
     * @var \Pimcore\Model\Asset
     */
    protected $asset = null;

    /** @var \DataImportBundle\Model\Mapping|null */
    protected $mapping = null;


    /**
     * Construct
     * @param \Pimcore\Log\ApplicationLogger $logger
     * @param \DataImportBundle\Model\Job $job
     * @param \DataImportBundle\Model\Mapping $mapping
     */
    public function __construct($logger, $job, $mapping)
    {

        $this->logger = $logger;
        $this->job = $job;
        $this->mapping = $mapping;
    }

    /**
     * onPostAdd
     * Hook after the current asset is uploaded
     *
     * @param ElementEventInterface $e
     * @throws \Exception
     */
    public function onPostAdd(ElementEventInterface $e)
    {
        if ($e instanceof AssetEvent) {
            $asset = $e->getAsset();
            if ($asset->getType() !== "folder") {
                $extension = pathinfo($asset->getFilename(), PATHINFO_EXTENSION);
                if (in_array($extension, Mapping::ALLOWED_EXTENSIONS)) {
                    $this->asset = $asset;
                    $mapping = $this->mapping->get($this->asset);
                    if ($mapping) {
                        $this->job->setMapping($mapping);
                        $this->job->setAsset($this->asset);
                        $this->job->create();
                    }
                }
            }
        }
    }

    /**
     * onPostDelete
     * Hook before the current asset is deleted
     *
     * @param ElementEventInterface $e
     * @throws \Exception
     */
    public function onPostDelete(ElementEventInterface $e)
    {
        if ($e instanceof AssetEvent) {
            $asset = $e->getAsset();
            $extension = pathinfo($asset->getFilename(), PATHINFO_EXTENSION);
            if ($asset->getType() !== "folder") {
                $this->asset = null;
                if (in_array($extension, ["xls", "xlsx"])) {
                    $file = CsvConversion::$conversionPath . "/" . $asset->getId() . ".csv";
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }
        }
    }


    /**
     * getParents
     * Get all the parents folder\'s names in array for current asset
     *
     * @param \Pimcore\Model\Asset $asset
     * @return array
     */
    protected function getParents($asset)
    {
        if ($asset->getParentId() != 1) {
            $element = \Pimcore\Model\Asset::getById($asset->getParentId());
            if ($element) {
                $this->assetParents[$element->getId()] = $element->getFilename();
                $this->getParents($element);
            }
        }
        return $this->assetParents;
    }

    /**
     * Update asset properties
     *
     * @return array
     */
    public function updateAsset()
    {
        $parents = array_reverse($this->getParents($this->asset));
        $parentPath = "/" . $parents[0] . "/" . $parents[1];
        $failedPath = \Pimcore\Model\Asset::getByPath($parentPath . "/Import Failed");
        if ($failedPath == NULL) {
            $parentId = \Pimcore\Model\Asset::getByPath($parentPath);

            $folder = \Pimcore\Model\Asset::create($parentId->getId(), [
                'filename' => "Import Failed",
                'type' => 'folder',
                'userOwner' => 1,
                'userModification' => 1
            ]);
            $failedPath = \Pimcore\Model\Asset::getByPath($parentPath . "/Import Failed");
        }
        $extension = pathinfo($this->asset->getFileName(), PATHINFO_EXTENSION);
        $fileParts = explode('.', $this->asset->getFilename());
        $key = str_ireplace("." . $extension, "", $fileParts[0]) . time() . "." . $fileParts[1];
        $this->asset->setFilename($key);
        $this->asset->setParentId($failedPath->getId());
        $this->asset->update();

        return $parents;
    }
}