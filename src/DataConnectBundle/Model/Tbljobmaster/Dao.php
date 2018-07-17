<?php

namespace DataConnectBundle\Model\Tbljobmaster;

use Pimcore\Model\Dao\AbstractDao;

class Dao extends AbstractDao
{

    protected $tableName = 'tblJobMaster';

    /**
     *
     * @param string $conn
     *            connection name
     * @param string $status
     *            job status
     */
    public function getConnJobDetails($conn, $status, $flag = null) {
        try {
            $sql = "SELECT * FROM " . $this->tableName . " WHERE status='" . $status . "'";
            if ($conn) {
                $sql .= " and find_in_set(BINARY con_name,'" . $conn . "')";
            }
            $columnSource = $this->db->fetchAll($sql);

            if (count($columnSource) > 0) {
                foreach ($columnSource as $job) {
                    if ($flag) {
                        $jobs[$job['con_name']] = $job['last_update_time'];
                    } else {
                        $jobs[$job['user_id']][] = $job['con_name'];
                    }
                }
                return $jobs;
            } else {
                return [];
            }
        } catch (Exception $e) {
            return array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
    }
    
     /**
     *
     * @param type $data            
     * @param type $conn            
     * @return boolean
     */
    public function addJob($data, $conn) {
        try {
            $sql = "INSERT into $this->tableName(job_id,source_records,user_id,con_name, start_time,last_update_time)
                    values('" . $data['job_id'] . "','" . $data['records'] . "','" . $data['user_id'] . "','" . $conn . "','" . $data['start'] . "','" . $data['last_update'] . "')";

            $this->db->query($sql);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get Job Id
     * 
     * @param string $conn            
     * @param string $status            
     * @return array
     */
    public function getJobId($conn, $status)
    {
        try {
            $sql = "SELECT job_id,start_time FROM " . $this->tableName . " WHERE status='" . $status . "'";
            if ($conn) {
                $sql .= " and find_in_set(BINARY con_name,'" . $conn . "')";
            }
            $columnSource = $this->db->fetchAll($sql);
            if (count($columnSource) > 0) {
                foreach ($columnSource as $job) {
                    $jobs[] = $job;
                }
                return $jobs;
            } else {
                return [];
            }
        } catch (Exception $e) {
            return array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
    }
    
    
    /**
     * Update job master
     *
     * @param string $conn            
     * @param array $update            
     * @return json
     */
    public function doJobUpdate($conn, $update) {
        try {
            $set = array();
            $sql = "UPDATE $this->tableName SET ";
            if (isset($update['user_id'])) {
                $set[] = " user_id='" . $update['user_id'] . "' ";
            }
            if (isset($update['records'])) {
                $set[] = " source_records='" . $update['records'] . "' ";
            }
            if (isset($update['status'])) {
                $set[] = " status='" . $update['status'] . "'";
            }
            if (isset($update['start'])) {
                $set[] = " start_time='" . $update['start'] . "'";
            }
            if (isset($update['last_update'])) {
                $set[] = " last_update_time='" . $update['last_update'] . "'";
            }
            if (isset($update['end'])) {
                $set[] = " end_time='" . $update['end'] . "'";
            }
            $sql .= implode(",", $set);
            $sql .= " WHERE status='in-progress' ";
            if ($conn) {
                $sql .= " and find_in_set(BINARY con_name,'" . $conn . "')";
            }
            $this->db->query($sql);

            return json_encode([
                'success' => true
            ]);
        } catch (Exception $e) {
            return json_encode([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

}
