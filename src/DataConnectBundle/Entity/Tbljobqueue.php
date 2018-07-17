<?php

namespace ProductBundle\Entity;

/**
 * Tbljobqueue
 */
class Tbljobqueue
{
    /**
     * @var string
     */
    private $sourceId;

    /**
     * @var integer
     */
    private $destId;

    /**
     * @var string
     */
    private $status = '1';

    /**
     * @var \ProductBundle\Entity\Tbljobmaster
     */
    private $job;


    /**
     * Set sourceId
     *
     * @param string $sourceId
     *
     * @return Tbljobqueue
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;

        return $this;
    }

    /**
     * Get sourceId
     *
     * @return string
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * Set destId
     *
     * @param integer $destId
     *
     * @return Tbljobqueue
     */
    public function setDestId($destId)
    {
        $this->destId = $destId;

        return $this;
    }

    /**
     * Get destId
     *
     * @return integer
     */
    public function getDestId()
    {
        return $this->destId;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Tbljobqueue
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
     * Set job
     *
     * @param \ProductBundle\Entity\Tbljobmaster $job
     *
     * @return Tbljobqueue
     */
    public function setJob(\ProductBundle\Entity\Tbljobmaster $job = null)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return \ProductBundle\Entity\Tbljobmaster
     */
    public function getJob()
    {
        return $this->job;
    }
}

