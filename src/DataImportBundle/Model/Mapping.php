<?php

namespace DataImportBundle\Model;

use Symfony\Component\DependencyInjection\ContainerInterface;
use DataConnectBundle\Model\Tbldataconnection;
use Pimcore\Model\Asset;

class Mapping
{

    /**
     * Constant for allowed extensions
     */
    CONST ALLOWED_EXTENSIONS = ["xlsx", "xls", "csv"];

    /**
     * Holds the asset object
     * @var Asset
     */
    protected $asset = null;

    /**
     * @var Mapping\Column $mappingColumn
     */
    protected $mappingColumn = null;

    /**
     * @var \Pimcore\Log\ApplicationLogger
     */
    protected $logger = null;

    /**
     * @var ContainerInterface
     */
    protected $container = null;

    /**
     * Constructor
     * @param ContainerInterface $container
     * @param Mapping\Column $mappingColumn
     * @param \Pimcore\Log\ApplicationLogger $logger
     */
    public function __construct(ContainerInterface $container, $mappingColumn, $logger)
    {
        $this->container = $container;
        $this->mappingColumn = $mappingColumn;
        $this->logger = $logger;
    }

    /**
     * Get the mapping according to the asset
     *
     * @param Asset $asset
     * @return \stdClass
     * @throws \Exception
     */
    public function get($asset)
    {
        $this->asset = $asset;
        return $this->findMapping();
    }

    /**
     * Find Mapping
     *
     * @return bool|mixed
     * @throws \Exception
     */
    protected function findMapping()
    {
        try {
            $mappingKey = $this->validateImportPath();
            if ($mappingKey) {
                $connectionModel = new Tbldataconnection();
                $mapping = $connectionModel->getDataConnectionDetails($mappingKey);
                $this->validateMapping($mapping);
                return $mapping;
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
        return false;
    }

    /**
     * @param $mapping
     * @return bool
     * @throws \Exception
     */
    protected function validateMapping($mapping)
    {
        try {
            $this->mappingColumn->validate($mapping, $this->asset);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
        return true;
    }

    /**
     * Validate Import Path and Check if import path exists in data connection table,
     * if found return mapping name
     *
     * @return string|null
     */
    protected function validateImportPath()
    {
        $path = $this->asset->getPath();
        $where = " import_path = '" . rtrim($path, "/") . "' ";

        $table = new Tbldataconnection();
        $row = $table->getConnection($where);
        if (count($row) > 0) {
            return $row[0]['con_name'];
        } else {
            return null;
        }
    }

}
