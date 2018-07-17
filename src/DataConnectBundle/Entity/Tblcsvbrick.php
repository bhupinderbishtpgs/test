<?php

namespace ProductBundle\Entity;

/**
 * Tblcsvbrick
 */
class Tblcsvbrick
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $delimiter;

    /**
     * @var string
     */
    private $filePath;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set delimiter
     *
     * @param string $delimiter
     *
     * @return Tblcsvbrick
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * Get delimiter
     *
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * Set filePath
     *
     * @param string $filePath
     *
     * @return Tblcsvbrick
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }
}
