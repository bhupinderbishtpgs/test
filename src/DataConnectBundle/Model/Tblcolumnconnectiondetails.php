<?php

namespace DataConnectBundle\Model;

use Pimcore\Model\AbstractModel;

/**
 * Tblcolumnconnectiondetails
 */
class Tblcolumnconnectiondetails extends AbstractModel
{

    protected $entity;

    public function __construct()
    {
        //$this->entity = new \DataConnectBundle\Entity\Tblcolumnconnectiondetails();
    }

    public function saveMapping($name, $col, $targetCol)
    {

        $obj = new self;
        $obj->getDao()->save($name, $col, $targetCol);
    }

    public function getMapping($name)
    {
        try {
            $obj = new self;
            $columnSource = $obj->getDao()->getMappingDetail($name);
            if (count($columnSource) > 0) {

                return [
                    "columnSource" => $columnSource,
                ];
            } else {
                return [
                    "columnSource" => [],
                ];
            }
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }

    public function deleteMapping($name)
    {
        try {
            $obj = new self;
            $obj->getDao()->delete($name);
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }


    public function renameMapping($oldName, $updatedName)
    {
        try {
            $obj = new self;
            $obj->getDao()->rename($oldName, $updatedName);
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }

    public function getPreselectAttr($name, $type)
    {
        try {
            $obj = new self;
            $data = $obj->getDao()->getPreselectAttr($name, $type);            
            return $data;
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }

    /**
     * Get connection mapping data
     *
     * @param string $name
     * @return array
     */
    public function getSourceData($name, $value = NULL)
    {
        try {
            $obj = new self;
            $columnSource = $obj->getDao()->getSourceDataMapping($name, $value);
            return $columnSource;
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     * Check mapping exists
     *
     * @param string $conn
     * @param array $data
     * @return array
     */
    public function checkMappingConnectionName($conn, $data)
    {
        try {

            $obj = new self;
            $columnSource = $obj->getDao()->checkMappingConnectionName($name, $value);
            return $columnSource;
        } catch (\Exception $e) {
            return array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     * Update mapping
     *
     * @param array $data
     * @param string $name
     * @return boolean
     */
    public function updateMappingDetails($data, $name, $col_type = null, $attr = null)
    {
        try {
            $obj = new self;
            $columnSource = $obj->getDao()->updateMappingDetails($data, $name, $col_type, $attr);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Save mapping
     *
     * @param array $data
     * @param string $conn
     * @return boolean
     */
    public function saveMappingConnectionName($data, $conn)
    {
        try {
            $obj = new self;
            $columnSource = $obj->getDao()->saveMappingConnectionName($data, $conn);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * this function is for the selected attr of brick
     *
     * @param string $name
     * @return array
     */
    public function getSelectedAttr($name)
    {
        try {
            $obj = new self;
            $data = $obj->getDao()->getSelectedAttr($name);
            return $data;
        } catch (Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }

    public function getDataMappingDetails($conName)
    {
        try {
            $obj = new self;
            $columnSource = $obj->getDao()->getDataMappingDetails($conName);
            return $columnSource;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * this is used to get the saved mapping from the coloumnconfiguration details
     * @param string $conName
     * @param string $type
     * @return array
     */
    public function getSavedMapping($conName, $type)
    {
        try {
            $obj = new self;
            $columnSource = $obj->getDao()->getSavedMapping($conName, $type);
            return $columnSource;
        } catch (Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }

    /*
     * this will get the mapped data for setting panel
     * 
     */

    public function getMappedDataColumns($con)
    {
        try {
            $obj = new self;
            $columnSource = $obj->getDao()->getMappedDataColumns($con);
            return $columnSource;
        } catch (Exception $ex) {
            return array(
                'success' => false,
                'msg' => $ex->getMessage()
            );
        }
    }

    /**
     * this is used to get the saved mapping from the coloumnconfiguration details
     * @param string $conName
     * @param string $type
     * @return array
     */
    public function getSaveData($tableName, $fieldValArr)
    {
        try {
            $obj = new self;
            $returnVal = $obj->getDao()->saveData($tableName, $fieldValArr);
            return $returnVal;
        } catch (Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }
}

