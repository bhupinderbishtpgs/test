<?php

namespace ProductBundle\Entity;

/**
 * Tblsqlbrick
 */
class Tblsqlbrick
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $datasql;

    /**
     * @var string
     */
    private $datafrom;

    /**
     * @var string
     */
    private $datawhere;

    /**
     * @var string
     */
    private $groupby;

    /**
     * @var string
     */
    private $sqltext;

    /**
     * @var string
     */
    private $rownumber;


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
     * Set datasql
     *
     * @param string $datasql
     *
     * @return Tblsqlbrick
     */
    public function setDatasql($datasql)
    {
        $this->datasql = $datasql;

        return $this;
    }

    /**
     * Get datasql
     *
     * @return string
     */
    public function getDatasql()
    {
        return $this->datasql;
    }

    /**
     * Set datafrom
     *
     * @param string $datafrom
     *
     * @return Tblsqlbrick
     */
    public function setDatafrom($datafrom)
    {
        $this->datafrom = $datafrom;

        return $this;
    }

    /**
     * Get datafrom
     *
     * @return string
     */
    public function getDatafrom()
    {
        return $this->datafrom;
    }

    /**
     * Set datawhere
     *
     * @param string $datawhere
     *
     * @return Tblsqlbrick
     */
    public function setDatawhere($datawhere)
    {
        $this->datawhere = $datawhere;

        return $this;
    }

    /**
     * Get datawhere
     *
     * @return string
     */
    public function getDatawhere()
    {
        return $this->datawhere;
    }

    /**
     * Set groupby
     *
     * @param string $groupby
     *
     * @return Tblsqlbrick
     */
    public function setGroupby($groupby)
    {
        $this->groupby = $groupby;

        return $this;
    }

    /**
     * Get groupby
     *
     * @return string
     */
    public function getGroupby()
    {
        return $this->groupby;
    }

    /**
     * Set sqltext
     *
     * @param string $sqltext
     *
     * @return Tblsqlbrick
     */
    public function setSqltext($sqltext)
    {
        $this->sqltext = $sqltext;

        return $this;
    }

    /**
     * Get sqltext
     *
     * @return string
     */
    public function getSqltext()
    {
        return $this->sqltext;
    }

    /**
     * Set rownumber
     *
     * @param string $rownumber
     *
     * @return Tblsqlbrick
     */
    public function setRownumber($rownumber)
    {
        $this->rownumber = $rownumber;

        return $this;
    }

    /**
     * Get rownumber
     *
     * @return string
     */
    public function getRownumber()
    {
        return $this->rownumber;
    }
}

