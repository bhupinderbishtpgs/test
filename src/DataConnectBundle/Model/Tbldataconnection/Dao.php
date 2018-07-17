<?php

namespace DataConnectBundle\Model\Tbldataconnection;

use Pimcore\Model\Dao\AbstractDao;

class Dao extends AbstractDao {

    protected $tableName = 'tblDataConnection';

    public function getConnections() {

        try {
            $sql = "select con_name from $this->tableName";

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

    public function addConnection($name) {
        try {
            $sql = "INSERT INTO $this->tableName (con_name) values('" . $name . "')";

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

    public function checkDataConnection($name) {

        try {
            $sql = "SELECT * FROM " . $this->tableName . " WHERE BINARY con_name = '" . $name . "'";
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

    public function getConnectionDetail($name) {
        try {

            $sql = "SELECT con_name,bridge_id,class_name,brickType,
                    exec_order,data_key_name,overwrite,language_key,
                    target_path,obj_key_prefix,brick_id,bridge_type,logs,import_path,is_active
                FROM $this->tableName
                WHERE BINARY con_name = '" . $name . "'";

            $dataSource = $this->db->fetchAll($sql);

            return $dataSource;
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     * 
     * @param sring $name
     * @return array
     */
    public function delete($name) {
        try {
            $sql = "delete from $this->tableName where BINARY ";
            $sql .= " con_name = '" . $name . "'";
            $this->db->query($sql);
            return array(
                'success' => true
            );
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     * @param $oldName
     * @param $updatedName
     * @return array
     */
    public function rename($oldName, $updatedName) {
        try {
            $sql = "UPDATE $this->tableName SET ";
            $sql .= " con_name = '" . $updatedName . "'";
            $sql .= " where BINARY con_name = '" . $oldName . "'";
            $this->db->query($sql);
            return array(
                'success' => true
            );
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    public function updateConnectionDetail($name, $data, $bridgeId = 0) {
        try {
            if (!empty($data->logs)) {
                $data->logs = '1';
            } else {
                $data->logs = '0';
            }
            $dataSource = $data->dataSourceConfig[0];
            $sql = "UPDATE $this->tableName set";
            $sql .= " bridge_id = '" . $bridgeId . "',";
            $sql .= " class_name='" . $data->className . "',";
            $sql .= " brickType='" . $dataSource->type . "',";
            $sql .= " exec_order='" . $data->order . "',";
            $sql .= " language_key='" . $data->language . "',";
            $sql .= " target_path='" . $data->targetPath . "',";
            $sql .= " brick_id='" . $data->brick_id . "',";
            $sql .= " logs='" . $data->logs . "',";
            $sql .= " import_path='" . $data->importPath . "'";
            $sql .= " where BINARY con_name = '" . $name . "'";
            //echo $sql."<br/>"; exit; 
            $this->db->query($sql);
        } catch (Exception $ex) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     *
     * @param array $where            
     * @return array
     */
    public function loadMappingData($where = array()) {
        try {

            $start = 0;
            if (isset($where['start']) && !empty($where['start'])) {
                $start = $where['start'];
            }

            if (isset($where['limit']) && !empty($where['limit'])) {
                $limit = $where['limit'];
            }

            // for sorting
            $sort = '';
            $order = '';
            if (isset($where['sort']) && !empty($where['sort'])) {
                $sort = $where['sort'];
                $order = $where['order'];
            }

//            $listObject = "SELECT
//                        (@cnt:=@cnt + 1) AS id,
//                        con_name,
//                        bridge_id,
//                        cls.name as name,
//                        sqlText,
//                        exec_order,
//                        data_key_name,
//                        overwrite,
//                        language_key,
//                        tdc.class_name as classid
//                    FROM
//                        tblDataConnection tdc
//                            JOIN
//                        classes cls ON tdc.class_name = cls.id
//                            JOIN
//                        (SELECT @cnt:=0) AS dummy
//                    WHERE
//                        data_key_name IS NOT NULL and data_key_name!=''";


            $listObject = "SELECT (@cnt:=@cnt + 1) AS id,
                            tdc.con_name,
                            bridge_id,
                            cls.name as name,
                            if(tjm.status = 'done', 'Complete', If(tjm.status ='in-complete', 'Failed', '')) as status,
                            tjm.datetime as datetime,
                            exec_order,
                            overwrite,
                            language_key,
                            tdc.class_id as classid
                            ,CASE tdc.brickType
                           WHEN 'csv'
                              THEN
                                  (SELECT CONCAT_WS(',', id, file_path, `delimiter`)
                              FROM tblCSVBrick
                            WHERE
                              tblCSVBrick.id = tdc.brick_id)
                            WHEN 'sql'
                              THEN
                                  (SELECT CONCAT_WS(',', id)
                             FROM tblSqlBrick
                            WHERE
                             tblSqlBrick.id = tdc.brick_id)
                            WHEN 'mysql'
                              THEN
                                  (SELECT CONCAT_WS(',', id)
                             FROM tblMysqlBrick
                            WHERE
                             tblMysqlBrick.id = tdc.brick_id) 
                            END AS data
                             FROM tblDataConnection AS tdc
                             JOIN
                             classes cls ON tdc.class_id = cls.id
                             JOIN
                             (SELECT @cnt:=0) AS dummy
                             LEFT JOIN
                              (SELECT status, con_name, last_update_time as datetime from tblJobMaster 
                              ORDER BY con_name,last_update_time DESC ) as tjm ON tjm.con_name =  tdc.con_name   
                            WHERE
                            data_key_name IS NOT NULL and data_key_name!=''";

            if (isset($where['type']) && !empty($where['type']) && $where['type'] == 'edit') {
                $listObject = $listObject . " Where tim.id='" . $where['id'] . "' ";
            }

            $listObjectTotal = $listObject;

            // for sorting
            if ($sort != '') {
                $listObject .= " ORDER BY  $sort  $order ";
            } else {
                $listObject .= " GROUP BY tdc.con_name";
            }


            if (isset($where['start']) && !empty($where['start']) && isset($where['limit']) && !empty($where['limit'])) {
                $listObject .= " limit $start,$limit  ";
            } else {
                if (isset($where['type']) && !empty($where['type'])) {
                    
                } else {
                    $listObject .= " limit $limit ";
                }
            }
            $resultData = $this->db->fetchAll($listObject);

            $i = 0;
            if (count($resultData) > 0) {
                foreach ($resultData as $rkey => $rValue) {
                    $resultData[$i]['id'] = $i + 1;
                    $i++;
                }
            }

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

    public function updateDataConnectionKeyName($name, $keyName, $overwrite = null, $objKeyName = null) {
        try {
            $sql = "UPDATE $this->tableName SET data_key_name='$keyName',obj_key_prefix='$objKeyName'";

            $overwrite = ($overwrite == 'true') ? 'yes' : 'no';
            $sql .= ",overwrite='$overwrite'";

            $sql .= " WHERE BINARY con_name='$name'";

            $this->db->query($sql);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateMappingData($name, $update = array()) {

        try {
            $sql = "UPDATE $this->tableName SET ";
            if (isset($update['data_key_name'])) {
                $sql .= " data_key_name=" . $update['data_key_name'];
            } else {
                $sql .= " data_key_name=NULL";
            }

            $sql .= " WHERE BINARY con_name='$name'";
            $this->db->query($sql);
            return array(
                'success' => true
            );
        } catch (\Exception $e) {
            return array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     * Get bridge connections
     *
     * @param string $bridge            
     * @return array
     */
    public function getBridgeConnections($bridge) {
        try {
            $sql = "SELECT con_name FROM " . $this->tableName . " WHERE
                BINARY con_name = '" . $bridge . "'";
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

    public function getConnectionDetailsData($connIds) {

        try {
            $sql = "select con_name, bridge_id, class_name, exec_order, brickType,brick_id, data_key_name,overwrite,language_key,target_path,obj_key_prefix,total_records,logs,import_path
                    from $this->tableName";
            $sql .= " where data_key_name is not null";
            if ($connIds) {
                $sql .= " and find_in_set(BINARY con_name,'" . $connIds . "')";
            }
            $sql .= " order by exec_order";
            $resp = $this->db->fetchAll($sql);

            if (count($resp) > 0) {
                return $resp;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function checkActiveMapping($name, $importPath) {

        try {
            $sql = "select con_name, import_path,is_active
                    from $this->tableName";
            $sql .= " where import_path = '" . $importPath . "' ";

            $sql .= " and is_active = '1'";
            $resp = $this->db->fetchAll($sql);
            if (count($resp) > 0) {
                return $resp;
            } else {
                return [];
            }
        } catch (Exception $exc) {
            return false;
        }
    }

    public function updateActiveMapping($name, $oldname) {
        try {
            if (!empty($oldname)) {
                $sql = "UPDATE $this->tableName set";
                $sql .= " is_active='0'";
                $sql .= " where BINARY con_name = '" . $oldname . "'";
                //echo $sql."<br/>"; exit;
                $this->db->query($sql);
            }
            if (!empty($name)) {
                $sql = "UPDATE $this->tableName set";
                $sql .= " is_active='1'";
                $sql .= " where BINARY con_name = '" . $name . "'";
                //echo $sql."<br/>"; exit;
                $this->db->query($sql);
            }
            return true;
        } catch (Exception $ex) {
            
        }
    }

    public function activateFirstTimeMapping($name, $active) {
        try {
            if (!empty($name)) {
                $sql = "UPDATE $this->tableName set";
                $sql .= " is_active='" . $active . "'";
                $sql .= " where BINARY con_name = '" . $name . "'";
                //echo $sql."<br/>"; exit;
                $this->db->query($sql);
            }
            return true;
        } catch (Exception $ex) {
            
        }
    }

    public function getConnection($where = null) {

        try {
            $sql = "select * from {$this->tableName} ";
            if ($where) {
                $sql .= "WHERE {$where}";
            }
            $resp = $this->db->fetchAll($sql);

            if (count($resp) > 0) {
                return $resp;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return false;
        }
    }

}
