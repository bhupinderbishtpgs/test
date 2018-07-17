<?php

namespace DataConnectBundle\Entity;

/**
 * Tbldataconnection
 */
class Tbldataconnection
{
    /**
     * @var string
     */
    private $conName;

    /**
     * @var integer
     */
    private $bridgeId;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $brickType;

    /**
     * @var boolean
     */
    private $execOrder;

    /**
     * @var string
     */
    private $dataKeyName;

    /**
     * @var string
     */
    private $overwrite = 'yes';

    /**
     * @var string
     */
    private $languageKey;

    /**
     * @var string
     */
    private $targetPath;

    /**
     * @var string
     */
    private $objKeyPrefix;

    /**
     * @var integer
     */
    private $totalRecords = '0';

    /**
     * @var string
     */
    private $bridgeType = 'internal';

    /**
     * @var integer
     */
    private $brickId;

    /**
     * Set conName
     *
     * @param string $conName
     *
     * @return Tbldataconnection
     */
    public function setConName($conName)
    {
        $this->conName = $conName;

        return $this;
    }

    /**
     * Get conName
     *
     * @return string
     */
    public function getConName()
    {
        return $this->conName;
    }

    /**
     * Set bridgeId
     *
     * @param integer $bridgeId
     *
     * @return Tbldataconnection
     */
    public function setBridgeId($bridgeId)
    {
        $this->bridgeId = $bridgeId;

        return $this;
    }

    /**
     * Get bridgeId
     *
     * @return integer
     */
    public function getBridgeId()
    {
        return $this->bridgeId;
    }

    /**
     * Set className
     *
     * @param string $className
     *
     * @return Tbldataconnection
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get className
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Set brickType
     *
     * @param string $brickType
     *
     * @return Tbldataconnection
     */
    public function setBrickType($brickType)
    {
        $this->brickType = $brickType;

        return $this;
    }

    /**
     * Get brickType
     *
     * @return string
     */
    public function getBrickType()
    {
        return $this->brickType;
    }

    /**
     * Set execOrder
     *
     * @param boolean $execOrder
     *
     * @return Tbldataconnection
     */
    public function setExecOrder($execOrder)
    {
        $this->execOrder = $execOrder;

        return $this;
    }

    /**
     * Get execOrder
     *
     * @return boolean
     */
    public function getExecOrder()
    {
        return $this->execOrder;
    }

    /**
     * Set dataKeyName
     *
     * @param string $dataKeyName
     *
     * @return Tbldataconnection
     */
    public function setDataKeyName($dataKeyName)
    {
        $this->dataKeyName = $dataKeyName;

        return $this;
    }

    /**
     * Get dataKeyName
     *
     * @return string
     */
    public function getDataKeyName()
    {
        return $this->dataKeyName;
    }

    /**
     * Set overwrite
     *
     * @param string $overwrite
     *
     * @return Tbldataconnection
     */
    public function setOverwrite($overwrite)
    {
        $this->overwrite = $overwrite;

        return $this;
    }

    /**
     * Get overwrite
     *
     * @return string
     */
    public function getOverwrite()
    {
        return $this->overwrite;
    }

    /**
     * Set languageKey
     *
     * @param string $languageKey
     *
     * @return Tbldataconnection
     */
    public function setLanguageKey($languageKey)
    {
        $this->languageKey = $languageKey;

        return $this;
    }

    /**
     * Get languageKey
     *
     * @return string
     */
    public function getLanguageKey()
    {
        return $this->languageKey;
    }

    /**
     * Set targetPath
     *
     * @param string $targetPath
     *
     * @return Tbldataconnection
     */
    public function setTargetPath($targetPath)
    {
        $this->targetPath = $targetPath;

        return $this;
    }

    /**
     * Get targetPath
     *
     * @return string
     */
    public function getTargetPath()
    {
        return $this->targetPath;
    }

    /**
     * Set objKeyPrefix
     *
     * @param string $objKeyPrefix
     *
     * @return Tbldataconnection
     */
    public function setObjKeyPrefix($objKeyPrefix)
    {
        $this->objKeyPrefix = $objKeyPrefix;

        return $this;
    }

    /**
     * Get objKeyPrefix
     *
     * @return string
     */
    public function getObjKeyPrefix()
    {
        return $this->objKeyPrefix;
    }

    /**
     * Set totalRecords
     *
     * @param integer $totalRecords
     *
     * @return Tbldataconnection
     */
    public function setTotalRecords($totalRecords)
    {
        $this->totalRecords = $totalRecords;

        return $this;
    }

    /**
     * Get totalRecords
     *
     * @return integer
     */
    public function getTotalRecords()
    {
        return $this->totalRecords;
    }

    /**
     * Set bridgeType
     *
     * @param string $bridgeType
     *
     * @return Tbldataconnection
     */
    public function setBridgeType($bridgeType)
    {
        $this->bridgeType = $bridgeType;

        return $this;
    }

    /**
     * Get bridgeType
     *
     * @return string
     */
    public function getBridgeType()
    {
        return $this->bridgeType;
    }

    /**
     * Set brickId
     *
     * @param integer $brickId
     *
     * @return Tbldataconnection
     */
    public function setBrickId($brickId)
    {
        $this->brickId = $brickId;

        return $this;
    }

    /**
     * Get brickId
     *
     * @return integer
     */
    public function getBrickId()
    {
        return $this->brickId;
    }
}

