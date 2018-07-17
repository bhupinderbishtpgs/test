<?php

namespace DataImportBundle\Model\Mapping;

use DataConnectBundle\Controller\ConnectionController;
use DataConnectBundle\Model\Tblcolumnconnectiondetails;
use DataImportBundle\Model\CsvConversion;
use NotificationBundle\Model\Notification;
use Pimcore\Event\Model\AssetEvent;
use Pimcore\Model\Asset;

class Column
{

    /**
     * @var array
     */
    protected $mapping = null;

    /**
     * @var \Pimcore\Model\Asset
     */
    protected $asset = null;

    /**
     * @var \Pimcore\Model\Asset
     */
    protected $parentPath = null;

    /**
     * validate
     * Validate csv headers against mapped columns
     *
     * @param \stdClass $mapping
     * @param Asset $asset
     * @return boolean
     * @throws \Exception
     */
    public function validate($mapping, $asset)
    {
        $this->mapping = $mapping;
        $this->asset = $asset;

        $headers = $this->getAssetHeaders();
        $mappingColumns = $this->getMappingColumns();

        $headersArr = [];
        foreach ($headers as $header) {
            $headersArr[] = strtolower($header);
        }

        $flag = true;
        foreach ($mappingColumns as $col) {
            if ($col["name"] !== "" && $col["target_col"] !== "") {
                if (!in_array(strtolower($col["name"]), $headersArr)) {
                    $flag = false;
                }
            }
        }

        if ($flag == true) {
            return true;
        } else {
            $this->updateAsset();
            throw new \Exception("Uploaded file structure does not match with mapped structure.Please upload correct file or contact Merchandiser.");

        }
    }

    /**
     * getAssetHeaders
     * Get all headers from csv file
     *
     * @return array
     * @throws \Exception
     */
    protected function getAssetHeaders(): array
    {
        $connectionController = new ConnectionController();
        $configuration = new \stdClass();
        $configuration->csvFilePath = $this->getFilePath();
        $configuration->fromDataImportBundle = true;
        $headers = $connectionController->getCSVColumns($configuration);
        if (empty($headers) || isset($headers["error"])) {
            throw new \Exception("Not able to get headers from the file. Please try again");
        }
        return $headers;
    }

    /**
     * getMappingColumns
     * Get mapping columns from the current mapping
     *
     * @return array
     * @throws \Exception
     */
    protected function getMappingColumns(): array
    {
        $mappingRow = new Tblcolumnconnectiondetails();
        $columns = $mappingRow->getMapping($this->mapping[0]["con_name"]);
        if (isset($columns["columnSource"]) && !empty($columns["columnSource"])) {
            return $columns["columnSource"];
        } else {
            throw new \Exception(sprintf("No mapped columns found for the given mapping %s", $this->mapping[0]["con_name"]));
        }
    }

    /**
     * Return the file path of converted csv
     *
     * @return string
     * @throws \Exception
     */
    protected function getFilePath()
    {
        if ($this->asset->getMimeType() === "text/csv") {
            return PIMCORE_ASSET_DIRECTORY . $this->asset->getPath() . $this->asset->getFilename();
        } else {
            $csv = new CsvConversion($this->asset);
            return $csv->getFilePath();
        }
    }

    public function setInitialData($asset)
    {
        $this->asset = $asset;
    }

    public function updateAsset()
    {
        $filepath = explode("/", $this->asset->getFullpath());
        $this->parentPath = "/" . $filepath[1] . "/" . $filepath[2];
        $failedPath = \Pimcore\Model\Asset::getByPath($this->parentPath . "/Import Failed");

        if ($failedPath == NULL) {
            $parentId = \Pimcore\Model\Asset::getByPath($this->parentPath);

            $folder = \Pimcore\Model\Asset::create($parentId->getId(), [
                'filename' => "Import Failed",
                'type' => 'folder',
                'userOwner' => 1,
                'userModification' => 1
            ]);
            $failedPath = \Pimcore\Model\Asset::getByPath($this->parentPath . "/Import Failed");
        }
        $extension = pathinfo($this->asset->getFileName(), PATHINFO_EXTENSION);
        $fileParts = explode('.', $this->asset->getFilename());
        $key = str_ireplace("." . $extension, "", $fileParts[0]) . time() . "." . $fileParts[1];
        $this->asset->setFilename($key);
        $this->asset->setParentId($failedPath->getId());
        $this->asset->save();
    }
}
