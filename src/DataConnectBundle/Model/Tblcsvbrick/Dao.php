<?php

namespace DataConnectBundle\Model\Tblcsvbrick;

use Pimcore\Model\Dao\AbstractDao;

class Dao extends AbstractDao {

    protected $tableName = 'tblCSVBrick';

    public function save($delimiter,$filePath) {
        try {
            $sql = "INSERT INTO $this->tableName (delimiter,file_path) values('" . $delimiter . "','".$filePath."')";

            $this->db->query($sql);
            return array(
                'success' => true,
                'id' => $this->db->lastInsertId()
            );
        } catch (\Exception $e) {

            return array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
    }
    
    public function checkBrick($delimiter = null,$filePath =  null) {

        try {
            $sql = "SELECT * FROM " . $this->tableName . " WHERE BINARY file_path = '" . $filePath . "' and delimiter = '".$delimiter."'";
            //echo $sql;
            $columnSource = $this->db->fetchAll($sql);

            if (count($columnSource) > 0) {
                return $columnSource;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }
    
    
    public function getBrick($brickData) {

        try {
            $sql = "SELECT * FROM " . $this->tableName . " WHERE id = '". $brickData ."'";
            //echo $sql;
            $columnSource = $this->db->fetchAll($sql);

            if (count($columnSource) > 0) {
                return $columnSource;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

}
