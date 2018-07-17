<?php

namespace ProductBundle\Entity;

/**
 * Tblsrcdestmapping
 */
class Tblsrcdestmapping
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
     * @var \DateTime
     */
    private $created = 'CURRENT_TIMESTAMP';


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
     * @return Tblsrcdestmapping
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Tblsrcdestmapping
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}

