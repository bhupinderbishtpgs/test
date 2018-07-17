<?php

namespace ProductBundle\Entity;

/**
 * Tbljobmaster
 */
class Tbljobmaster
{
    /**
     * @var integer
     */
    private $jobId;

    /**
     * @var integer
     */
    private $sourceRecords;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $bridgeId;

    /**
     * @var string
     */
    private $conName;

    /**
     * @var \DateTime
     */
    private $startTime;

    /**
     * @var \DateTime
     */
    private $endTime;

    /**
     * @var string
     */
    private $status = 'in-progress';

    /**
     * @var \DateTime
     */
    private $lastUpdateTime;


    /**
     * Get jobId
     *
     * @return integer
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * Set sourceRecords
     *
     * @param integer $sourceRecords
     *
     * @return Tbljobmaster
     */
    public function setSourceRecords($sourceRecords)
    {
        $this->sourceRecords = $sourceRecords;

        return $this;
    }

    /**
     * Get sourceRecords
     *
     * @return integer
     */
    public function getSourceRecords()
    {
        return $this->sourceRecords;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Tbljobmaster
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set bridgeId
     *
     * @param integer $bridgeId
     *
     * @return Tbljobmaster
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
     * Set conName
     *
     * @param string $conName
     *
     * @return Tbljobmaster
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
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return Tbljobmaster
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     *
     * @return Tbljobmaster
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Tbljobmaster
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set lastUpdateTime
     *
     * @param \DateTime $lastUpdateTime
     *
     * @return Tbljobmaster
     */
    public function setLastUpdateTime($lastUpdateTime)
    {
        $this->lastUpdateTime = $lastUpdateTime;

        return $this;
    }

    /**
     * Get lastUpdateTime
     *
     * @return \DateTime
     */
    public function getLastUpdateTime()
    {
        return $this->lastUpdateTime;
    }
}

