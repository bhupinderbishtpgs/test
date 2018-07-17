<?php

/**
 * ResourceManager
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) 2009-2016 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 *
 * @desc This file is used for connection resource model definition
 *
 */
namespace DataConnectBundle\Model;

/**
 * ResourceManager Class
 */
class ResourceManager
{

    /**
     *
     * @var string
     */
    public $table;

    public $jobMasterTable;

    public $jobQueueTable;

    public $mappingTable;

    /**
     * initialize
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = "tblDataConnection";
        $this->jobMasterTable = "tblJobMaster";
        $this->jobQueueTable = "tblJobQueue";
        $this->mappingTable = "tblSrcDestMapping";
    }

    /**
     * Add new connection
     *
     * @param string $name            
     * @return json
     */
    public function addConnectionName($name)
    {
        try {
            $sql = "INSERT INTO $this->table (con_name) values('" . $name . "')";
            $this->db->query($sql);
            return json_encode([
                'success' => true,
                'id' => $this->db->lastInsertId()
            ]);
        } catch (\Exception $e) {
            return json_encode(array(
                'success' => false,
                'msg' => $e->getMessage()
            ));
        }
    }

    /**
     * Get connections listing
     *
     * @return array
     */
    public function getConnections()
    {
        try {
            $sql = "select con_name from $this->table";
            $resp = $this->db->fetchAll($sql);
            if (count($resp) > 0) {
                return $resp;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     * Delete connection
     *
     * @param string $name            
     * @return json
     */
    public function deleteConnection($name)
    {
        try {
            $sql = "delete from $this->table where BINARY ";
            $sql .= " con_name = '" . $name . "'";
            $this->db->query($sql);
            
            $dsql = "delete from tblColumnConnectionDetails where BINARY ";
            $dsql .= " con_name='" . $name . "'";
            $this->db->query($dsql);
            
            return json_encode([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return json_encode(array(
                'success' => false,
                'msg' => $e->getMessage()
            ));
        }
    }

    /**
     * Update connection and mapping data
     *
     * @param string $name            
     * @param array $data            
     * @return boolean
     */
    public function updateConnectionDetail($name, $data)
    {
        try {
            $dataSource = $data['dataSourceConfig'][0];
            $sql = "UPDATE $this->table set bridge_name='" . $data['bridgeName'] . "', 
                class_name='" . $data['className'] . "',
                datasql='" . str_replace("'", "\'", $dataSource['sql']) . "', datafrom='" . $dataSource['from'] . "',
                datawhere='" . str_replace("'", "\'", $dataSource['where']) . "', 
                groupby='" . $dataSource['groupby'] . "',
                sqlText='" . str_replace("'", "\'", $dataSource['sqlText']) . "', 
                datatype='" . $dataSource['type'] . "',exec_order='" . $data['order'] . "',
                language_key='" . $data['language'] . "',
                target_path='" . $data['targetPath'] . "'
                where BINARY con_name = '" . $name . "'";
            
            $this->db->query($sql);
            
            $getTargetMappings = $this->getSourceData($name);
            
            $targetCol = array();
            foreach ($getTargetMappings as $t) {
                $targetCol[$t['name']] = $t['target_col'];
            }
            
            $sql = "delete from tblColumnConnectionDetails where BINARY ";
            $sql .= " con_name='" . $name . "'";
            $this->db->query($sql);
            
            if (isset($data['columnConfiguration'])) {
                foreach ($data['columnConfiguration'] as $key => $col) {
                    $colDisplay = ($col['display'] == '1') ? '1' : '0';
                    $sql = "INSERT into tblColumnConnectionDetails(con_name, name, label, display, 
                    id,target_col) values('" . $name . "', '" . $col['name'] . "', '" . $col['label'] . "', '" . $colDisplay . "', '" . $col['id'] . "', '" . $targetCol[$col['name']] . "')";
                    $this->db->query($sql);
                }
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get connection data
     *
     * @param string $name            
     * @return array
     */
    public function getConnectionDetail($name)
    {
        try {
            
            $execOrder = $class = $bridge = $keyName = $overwrite = $languageKey = '';
            $dataSourceConfig = [];
            $sql = "SELECT 
                    con_name,
                    bridge_name,
                    class_id,
                    datasql,
                    datafrom,
                    datawhere,
                    groupby,
                    sqlText,
                    datatype,
                    exec_order,
                    data_key_name,
                    overwrite,
                    language_key,
                    target_path,
                    obj_key_prefix
                FROM
                    $this->table
                WHERE
                    BINARY con_name = '" . $name . "'";
            $dataSource = $this->db->fetchAll($sql);
            foreach ($dataSource as $key => $source) {
                $dataSourceConfig['sql'] = $source['datasql'];
                $dataSourceConfig['from'] = $source['datafrom'];
                $dataSourceConfig['where'] = $source['datawhere'];
                $dataSourceConfig['groupby'] = $source['groupby'];
                $dataSourceConfig['sqlText'] = $source['sqlText'];
                $dataSourceConfig['type'] = $source['datatype'];
                $bridge = $source['bridge_name'];
                $class = $source['class_id'];
                $execOrder = $source['exec_order'];
                $keyName = $source['data_key_name'];
                $overwrite = $source['overwrite'];
                $languageKey = $source['language_key'];
                $targetPath = $source['target_path'];
                $obj_key_prefix = $source['obj_key_prefix'];
            }
            
            $sql = "SELECT 
                    name, display, label, id,target_col
                FROM
                    tblColumnConnectionDetails
                WHERE
                    BINARY con_name = '" . $name . "'";
            
            $columnSource = $this->db->fetchAll($sql);
            if (count($columnSource) > 0) {
                
                return [
                    "exec_order" => $execOrder,
                    "class" => $class,
                    "bridgeName" => $bridge,
                    "columnSource" => $columnSource,
                    "dataSource" => [
                        $dataSourceConfig
                    ],
                    "keyName" => $keyName,
                    "overwrite" => $overwrite,
                    'language_key' => $languageKey,
                    'target_path' => $targetPath,
                    'obj_key_prefix' => $obj_key_prefix
                ];
            } else {
                return [
                    "exec_order" => $execOrder,
                    "class" => $class,
                    "bridgeName" => $bridge,
                    "columnSource" => [],
                    "dataSource" => [
                        $dataSourceConfig
                    ],
                    "keyName" => $keyName,
                    "overwrite" => $overwrite,
                    'language_key' => $languageKey,
                    'target_path' => $targetPath,
                    'obj_key_prefix' => $obj_key_prefix
                ];
            }
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     * Get connection mapping data
     *
     * @param string $name            
     * @return array
     */
    public function getSourceData($name)
    {
        try {
            $sql = "SELECT
                    name, display, label, id,target_col
                FROM
                    tblColumnConnectionDetails
                WHERE
                    BINARY con_name = '" . $name . "'  and display=1";
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

    /**
     * Update mapping
     *
     * @param array $data            
     * @param string $name            
     * @return boolean
     */
    public function updateMappingDetails($data, $name)
    {
        try {
            $sql = "UPDATE tblColumnConnectionDetails SET target_col = $data[1]
            WHERE BINARY con_name='$name' AND BINARY name = $data[0]";
            $this->db->query($sql);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update connection key name
     *
     * @param string $name            
     * @param string $keyName            
     * @param string $overwrite            
     * @return boolean
     */
    public function updateDataConnectionKeyName($name, $keyName, $overwrite = null, $objKeyName = null)
    {
        try {
            $sql = "UPDATE $this->table SET data_key_name='$keyName',obj_key_prefix='$objKeyName'";
            
            $overwrite = ($overwrite == 'true') ? 'yes' : 'no';
            $sql .= ",overwrite='$overwrite'";
            
            $sql .= " WHERE BINARY con_name='$name'";
            $this->db->query($sql);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check connection exists
     *
     * @param string $name            
     * @return array
     */
    public function checkDataConnectionKeyName($name)
    {
        try {
            $sql = "SELECT * FROM " . $this->table . " WHERE
                BINARY con_name = '" . $name . "'";
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

    /**
     * Delete mapping
     *
     * @param string $name            
     * @return json
     */
    public function deleteMapping($name)
    {
        try {
            
            $dsql = "delete from tblColumnConnectionDetails where BINARY con_name='" . $name . "'";
            $this->db->query($dsql);
            
            $sql = "UPDATE $this->table SET data_key_name=NULL WHERE BINARY con_name='$name'";
            $this->db->query($sql);
            
            return json_encode([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return json_encode([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
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
            
            $sql = 'SELECT * FROM tblColumnConnectionDetails WHERE
                    BINARY con_name="' . $conn . '" AND BINARY name =' . $data[0];
            $columnSource = $this->db->fetchAll($sql);
            if (count($columnSource) > 0) {
                return $columnSource;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return array(
                'success' => false,
                'msg' => $e->getMessage()
            );
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
            $sql = "INSERT into tblColumnConnectionDetails(con_name, name, id,target_col)
                    values('" . $conn . "', $data[0], $data[2], $data[1] )";
            $this->db->query($sql);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get bridge connections
     *
     * @param string $bridge            
     * @return array
     */
    public function getBridgeConnections($bridge)
    {
        try {
            $sql = "SELECT con_name FROM " . $this->table . " WHERE
                BINARY bridge_name = '" . $bridge . "'";
            $columnSource = $this->db->fetchAll($sql);
            if (count($columnSource) > 0) {
                foreach ($columnSource as $con) {
                    $connections[] = $con['con_name'];
                }
                return $connections;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     *
     * @param string $conn
     *            connection name
     * @param string $status
     *            job status
     */
    public function getConnJobDetails($conn, $status)
    {
        try {
            $sql = "SELECT * FROM " . $this->jobMasterTable . " WHERE status='" . $status . "'";
            if ($conn) {
                $sql .= " and find_in_set(BINARY con_id,'" . $conn . "')";
            }
            $columnSource = $this->db->fetchAll($sql);
            
            if (count($columnSource) > 0) {
                foreach ($columnSource as $job) {
                    // $jobs[$job['user_id']][]['job_id'] = $job['job_id'];
                    $jobs[$job['user_id']][] = $job['con_id'];
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
    public function doJobUpdate($conn, $update)
    {
        try {
            $set = array();
            $sql = "UPDATE $this->jobMasterTable SET ";
            if (isset($update['user_id'])) {
                $set[] = " user_id='" . $update['user_id'] . "' ";
            }
            if (isset($update['status'])) {
                $set[] = " status='" . $update['status'] . "'";
            }
            if (isset($update['start'])) {
                $set[] = " start_time='" . $update['start'] . "'";
            }
            if (isset($update['end'])) {
                $set[] = " end_time='" . $update['end'] . "'";
            }
            $sql .= implode(",", $set);
            $sql .= " WHERE status='in-progress' ";
            if ($conn) {
                $sql .= " and find_in_set(BINARY con_id,'" . $conn . "')";
            }
            // echo $sql; exit;
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

    /**
     *
     * @param type $data            
     * @param type $conn            
     * @return boolean
     */
    public function addJob($data, $conn)
    {
        try {
            $sql = "INSERT into $this->jobMasterTable(job_id,source_records,user_id,con_id, start_time)
                    values('" . $data['job_id'] . "','" . $data['records'] . "','" . $data['user_id'] . "','" . $conn . "','" . $data['start'] . "' )";
            // echo $sql; exit;
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
            $sql = "SELECT job_id,start_time FROM " . $this->jobMasterTable . " WHERE status='" . $status . "'";
            if ($conn) {
                $sql .= " and find_in_set(BINARY con_id,'" . $conn . "')";
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
     *
     * @param string $job            
     * @param int $source            
     * @param int $dest            
     * @return boolean
     */
    public function addQueueorMapping($table, $data)
    {
        try {
            
            $cols = array_keys($data);
            $values = array_values($data);
            $sql = "INSERT INTO $table ( " . implode(',', $cols) . ") VALUES('" . implode("','", $values) . "')";
            
            $this->db->query($sql);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * @param string $jobId            
     * @param int $source            
     * @return boolean
     */
    public function checkQueueExists($jobId, $source)
    {
        try {
            
            $sql = "SELECT * FROM $this->jobQueueTable WHERE
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

    public function checkMappingExists($dest_id)
    {
        try {
            
            $sql = "SELECT * FROM $this->mappingTable WHERE dest_id='" . $dest_id . "'";
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

    public function updateMapping($dest_id)
    {
        try {
            $sql = "UPDATE $this->mappingTable SET created='" . date("Y-m-d H:i:s") . "'";
            
            $sql .= " WHERE dest_id='$dest_id'";
            $this->db->query($sql);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     *
     * @param array $where            
     * @return array
     */
    public function loadMapping($where = array())
    {
        try {
            
            $start = 0;
            if (isset($where['start']) && ! empty($where['start'])) {
                $start = $where['start'];
            }
            
            if (isset($where['limit']) && ! empty($where['limit'])) {
                $limit = $where['limit'];
            }
            
            // for sorting
            $sort = '';
            $order = '';
            if (isset($where['sort']) && ! empty($where['sort'])) {
                $sort = $where['sort'];
                $order = $where['order'];
            }
            
            $listObject = "SELECT
                        (@cnt:=@cnt + 1) AS id,
                        con_name,
                        bridge_name,
                        cls.name as name,
                        sqlText,
                        exec_order,
                        data_key_name,
                        overwrite,
                        language_key,
                        tdc.class_name as classid
                    FROM
                        tblDataConnection tdc
                            JOIN
                        classes cls ON tdc.class_name = cls.id
                            JOIN
                        (SELECT @cnt:=0) AS dummy
                    WHERE
                        data_key_name IS NOT NULL and data_key_name!=''";
            
            if (isset($where['type']) && ! empty($where['type']) && $where['type'] == 'edit') {
                $listObject = $listObject . " Where tim.id='" . $where['id'] . "' ";
            }
            
            $listObjectTotal = $listObject;
            
            // for sorting
            if ($sort != '') {
                $listObject .= " ORDER BY  $sort  $order ";
            }
            
            if (isset($where['start']) && ! empty($where['start']) && isset($where['limit']) && ! empty($where['limit'])) {
                $listObject .= " limit $start,$limit  ";
            } else {
                if (isset($where['type']) && ! empty($where['type'])) {} else {
                    $listObject .= " limit $limit ";
                }
            }
            
            $resultData = $this->db->fetchAll($listObject);
            $listObjectTotal = count($this->db->fetchAll($listObjectTotal));
            
            $return = array();
            $return['resultData'] = $resultData;
            $return['listObjectTotal'] = $listObjectTotal;
            
            return $return;
        } catch (\Exception $e) {
            return array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
    }
}
