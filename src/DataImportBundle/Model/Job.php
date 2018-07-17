<?php

namespace DataImportBundle\Model;

use Pimcore\Model\DataObject;

class Job
{

    /**
     * @var \Pimcore\Log\ApplicationLogger
     */
    protected $logger = null;

    /**
     * @var \Pimcore\Model\Asset
     */
    protected $asset = null;

    /**
     * @var array
     */
    protected $mapping = null;

    /**
     * Constant path for parent folder which contains all vendor specific folder
     */
    CONST JOBS_FOLDER_PATH = 'DataImportJobs';


    /**
     * Set logger object
     * @param \Pimcore\Log\ApplicationLogger $logger
     */
    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    /**
     * setAsset
     * Setter for current asset
     *
     * @param \Pimcore\Model\Asset $asset
     */
    public function setAsset($asset)
    {
        $this->asset = $asset;
    }

    /**
     * setMapping
     * Setter for current mapping
     *
     * @param \stdClass $mapping
     */
    public function setMapping($mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * Create a job object
     *
     * @throws \Exception
     */
    public function create()
    {
        try {
            $parentFolder = $this->getParentFolder();
            $job = new DataObject\Job();
            $job->setStatus("tobeexecuted");
            if (\Pimcore\Tool\Authentication::authenticateSession()) {
                $user = \Pimcore\Tool\Authentication::authenticateSession();
                $job->setCreatedUser($user->getId());
            }
            $job->setAsset($this->asset);
            $job->setMapping($this->mapping[0]["con_name"]);
            $job->setKey(\Pimcore\File::getValidFilename("job-" . date("Y-m-d H:i:s")));
            $job->setParent($parentFolder);
            $job->save();
        } catch (\Exception $e) {
            $this->asset->delete();
            $this->logger->error($e);
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Create job folder if not exists
     *
     * @return DataObject\AbstractObject|DataObject\Folder
     * @throws \Exception
     */
    protected function getParentFolder()
    {
        $jobFolderPath = "/". self::JOBS_FOLDER_PATH . "/" . ucfirst($this->mapping[0]["con_name"]);
        $importJobFolder = DataObject\Folder::getByPath($jobFolderPath);
        if (!$importJobFolder) {
            $path = "/". self::JOBS_FOLDER_PATH;
            $jobFolder = DataObject\Folder::getByPath($path);
            if (!$jobFolder) {
                $jobFolder = new DataObject\Folder();
                $jobFolder->setKey($path);
                $jobFolder->setParentId(1);
                $jobFolder->save();
            }
            $importJobFolder = new DataObject\Folder();
            $importJobFolder->setKey(ucfirst($this->mapping[0]["con_name"]));
            $importJobFolder->setParent($jobFolder);
            $importJobFolder->save();
        }
        return $importJobFolder;
    }

}