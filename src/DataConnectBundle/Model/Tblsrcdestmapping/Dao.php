<?php

namespace DataConnectBundle\Model\Tblsrcdestmapping;

use Pimcore\Model\Dao\AbstractDao;

class Dao extends AbstractDao
{

    protected $tableName = 'tblSrcDestMapping';

     public function checkMappingExists($dest_id)
    {
        try {
            
            $sql = "SELECT * FROM $this->tableName WHERE dest_id='" . $dest_id . "'";
            $columnSource = $this->db->fetchAll($sql);
            if (count($columnSource) > 0) {
                return $columnSource[0];
            } else {
                return false;
            }
        } catch (Exception $e) {
            return array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
    }

}
