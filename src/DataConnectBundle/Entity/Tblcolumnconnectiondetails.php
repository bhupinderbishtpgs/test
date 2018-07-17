<?php

namespace DataConnectBundle\Entity;

/**
 * Tblcolumnconnectiondetails
 */
class Tblcolumnconnectiondetails
{
    /**
     * @var string
     */
    private $conName;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $display = '1';

    /**
     * @var string
     */
    private $targetCol;

    /**
     * @var string
     */
    private $refClass;

    /**
     * @var string
     */
    private $colType;

    /**
     * @var string
     */
    private $selectedAttr;

    /**
     * @var string
     */
    private $groupNo;


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
     * Set name
     *
     * @param string $name
     *
     * @return Tblcolumnconnectiondetails
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Tblcolumnconnectiondetails
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set display
     *
     * @param string $display
     *
     * @return Tblcolumnconnectiondetails
     */
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }

    /**
     * Get display
     *
     * @return string
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * Set targetCol
     *
     * @param string $targetCol
     *
     * @return Tblcolumnconnectiondetails
     */
    public function setTargetCol($targetCol)
    {
        $this->targetCol = $targetCol;

        return $this;
    }

    /**
     * Get targetCol
     *
     * @return string
     */
    public function getTargetCol()
    {
        return $this->targetCol;
    }

    /**
     * Set refClass
     *
     * @param string $refClass
     *
     * @return Tblcolumnconnectiondetails
     */
    public function setRefClass($refClass)
    {
        $this->refClass = $refClass;

        return $this;
    }

    /**
     * Get refClass
     *
     * @return string
     */
    public function getRefClass()
    {
        return $this->refClass;
    }

    /**
     * Set colType
     *
     * @param string $colType
     *
     * @return Tblcolumnconnectiondetails
     */
    public function setColType($colType)
    {
        $this->colType = $colType;

        return $this;
    }

    /**
     * Get colType
     *
     * @return string
     */
    public function getColType()
    {
        return $this->colType;
    }

    /**
     * Set selectedAttr
     *
     * @param string $selectedAttr
     *
     * @return Tblcolumnconnectiondetails
     */
    public function setSelectedAttr($selectedAttr)
    {
        $this->selectedAttr = $selectedAttr;

        return $this;
    }

    /**
     * Get selectedAttr
     *
     * @return string
     */
    public function getSelectedAttr()
    {
        return $this->selectedAttr;
    }

    /**
     * Set groupNo
     *
     * @param string $groupNo
     *
     * @return Tblcolumnconnectiondetails
     */
    public function setGroupNo($groupNo)
    {
        $this->groupNo = $groupNo;

        return $this;
    }

    /**
     * Get groupNo
     *
     * @return string
     */
    public function getGroupNo()
    {
        return $this->groupNo;
    }
}

