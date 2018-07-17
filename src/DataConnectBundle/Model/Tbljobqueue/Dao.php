<?php

namespace DataConnectBundle\Model\Tbljobqueue;

use Pimcore\Model\Dao\AbstractDao;

class Dao extends AbstractDao
{

    protected $tableName = 'tblJobQueue';

    /**
     *
     * @param string $jobId            
     * @param int $source            
     * @return boolean
     */
    public function checkQueueExists($jobId, $source)
    {
        try {
            
            $sql = "SELECT * FROM $this->tableName WHERE
                    job_id='" . $jobId . "' AND source_id ='" . $source . "' AND status='1'";
            $columnSource = $this->db->fetchAll($sql);
            if (count($columnSource) > 0) {
                return true;
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
    
    
    public function addQueueorMapping($data) {
        try {
            $sql = "INSERT INTO $this->tableName (job_id,source_id,dest_id,status) VALUES('".$data['job_id']."','".$data['source_id']."','".$data['dest_id']."','1')";
            $this->db->query($sql);
            return true;
        } catch (Exception $ex) {
            return array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
    }

}
