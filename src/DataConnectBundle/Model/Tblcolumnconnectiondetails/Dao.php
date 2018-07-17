<?php

namespace DataConnectBundle\Model\Tblcolumnconnectiondetails;

use Pimcore\Model\Dao\AbstractDao;

class Dao extends AbstractDao {

    protected $tableName = 'tblColumnConnectionDetails';

    public function getMappingDetail($name) {
        try {

            $sql = "SELECT name, display, label,target_col,col_type,ref_class,ref_field
                FROM 
                    tblColumnConnectionDetails
                WHERE
                    BINARY con_name = '" . $name . "' and 
                    (col_type != 'objectbrick' or col_type != 'fieldcollection')";

            $dataSource = $this->db->fetchAll($sql);
            return $dataSource;
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    public function save($name, $col, $targetCol) {
        try {
            $colDisplay = ($col->display == '1') ? '1' : '0';
            $sql = "INSERT into $this->tableName(con_name, name, label, display,";
            $sql .= "target_col,ref_class,ref_field,col_type,selected_attr,group_no) ";
            $sql .= " values('" . $name . "', '" . $col->name . "', 
                    '" . $col->label . "', '" . $colDisplay . "', 
                    '" . $targetCol[$col->name]['target'] . "',
                    '" . $targetCol[$col->name]['ref_class'] . "',
                    '" . $targetCol[$col->name]['ref_field'] . "',
                    '" . $targetCol[$col->name]['col_type'] . "',
                    '" . $targetCol[$col->name]['selected_attr'] . "',
                    '" . $targetCol[$col->name]['group_no'] . "')";
            $this->db->query($sql);
        } catch (Exception $ex) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    public function delete($name) {
        try {

            $sql = "delete from $this->tableName where BINARY ";
            $sql .= " con_name='" . $name . "'";
            //echo $sql;
            $this->db->query($sql);
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

    public function getSourceDataMapping($name, $value = null) {

        try {

            if (!empty($value)) {
                $sql = "SELECT
                    name, display, label, target_col,ref_class,ref_field,col_type,selected_attr,group_no
                FROM
                    $this->tableName
                WHERE
                    BINARY con_name = '" . $name . "'  and display=1";
            } else {
                $sql = "SELECT
                    name, display, label, target_col,ref_class,ref_field
                FROM
                    $this->tableName
                WHERE
                    BINARY con_name = '" . $name . "'  and display=1 and col_type = '' ";
            }
//            echo $sql;    exit;
            $columnSource = $this->db->fetchAll($sql);
            
            if (count($columnSource) > 0) {
                return $columnSource;
            } else {
                return [];
            }
        } catch (Exception $ex) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    public function checkMappingConnectionName($conn, $name) {
        try {

            $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE
                    BINARY con_name="' . $conn . '" AND BINARY name =' . $name;
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

    public function updateMappingDetails($data, $name, $col_type = null, $attr = null) {
        try {
            $group_no = $refClass = $ref_field = "''";
            if ($col_type == "objectbrick") {
                $refClass = (isset($data[2]) && $data[2] != '') ? $data[2] : "''";
                $ref_field = (isset($data[5]) && $data[5] != '') ? $data[5] : "''";
            } elseif ($col_type == "fieldcollection") {
                $refClass = (isset($data[2]) && $data[2] != '') ? $data[2] : "''";
                $group_no = (isset($data[3]) && $data[3] != '') ? $data[3] : '';
            } elseif (empty($col_type)) {
                $refClass = (isset($data[2]) && $data[2] != '') ? $data[2] : "''";
                $ref_field = (isset($data[4]) && $data[4] != '') ? $data[4] : "''";
            }
            

            if ($data[1] != '""') {
                if (in_array(str_replace('"', '', $data[1]), array(
                            'objectbrick',
                            'fieldcollection'
                        ))) {
                    $col_type = str_replace('"', '', $data[1]);
                    $data[1] = "''";
                }
                $sql = "UPDATE tblColumnConnectionDetails SET target_col = $data[1],ref_class = $refClass," . "col_type = '" . $col_type . "', group_no = $group_no ,selected_attr = '$attr',ref_field = $ref_field
            WHERE BINARY con_name='$name' AND BINARY name = $data[0]";
            } else {
                $sql = "UPDATE tblColumnConnectionDetails SET target_col = $data[1],ref_class = $refClass," . "col_type = '',group_no = $group_no,selected_attr = '$attr',ref_field = $ref_field
            WHERE BINARY con_name='$name' AND BINARY name = $data[0]";
            }
            //echo '<pre/>'; print_r($sql); echo "\n";
            $this->db->query($sql);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function saveMappingConnectionName($data, $conn) {
        try {
            $col_type = "";
            $data[2] = (isset($data[2]) && $data[2] != '') ? $data[2] : '';
            $data[4] = (isset($data[4]) && $data[4] != '') ? $data[4] : '';
            if (in_array($data[1], array(
                        'objectbrick',
                        'fieldcollection'
                    ))) {
                $col_type = $data[1];
                $data[1] = "";
            }
            $sql = "INSERT into $this->tableName(con_name, name,target_col,ref_class,ref_field,col_type)
                    values('" . $conn . "', $data[0], $data[1],$data[2],$data[4],$col_type)";
            $this->db->query($sql);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getSelectedAttr($name) {

        try {
            $sql = "SELECT selected_attr FROM $this->tableName WHERE
                    BINARY con_name = '" . $name . "'  and display=1";

            $data = $this->db->fetchAll($sql);
            if (count($data) > 0) {
                return $data;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    public function getPreselectAttr($name, $type) {

        try {
            if ($type == 'brick') {
                $sql = "SELECT selected_attr FROM $this->tableName WHERE
                    BINARY con_name = '" . $name . "'  and col_type='objectbrick' LIMIT 0,1";
            } else {
                $sql = "SELECT selected_attr FROM $this->tableName WHERE
                    BINARY con_name = '" . $name . "'  and col_type='fieldcollection' LIMIT 0,1";
            }


            $data = $this->db->fetchAll($sql);
            if (count($data) > 0) {
                return $data;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    public function getSavedMapping($conName, $type) {
        try {
            if ($type == "objectbrick") {
                $sql = "SELECT con_name, name, label, target_col, ref_class,ref_field, display
                    FROM
                        $this->tableName
                    WHERE
                        BINARY con_name = '$conName'
                        AND display = '1'   
                        AND (target_col IS NULL or target_col='' or col_type = 'objectbrick') 
                        AND col_type != 'fieldcollection'
                    ";
            } elseif ($type == "fieldcollection") {

                $sql = "SELECT con_name, name, label, target_col, ref_class, display, group_no
                    FROM
                        $this->tableName
                    WHERE
                        BINARY con_name = '$conName'
                        AND display = '1'       
                        AND (target_col IS NULL or target_col='' or col_type = 'fieldcollection')
                        AND col_type != 'objectbrick'
                    ";
            }
            
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

    /* Get mapping details
     * 
     * @param string $conName            
     * @return mixed
     */

    public function getDataMappingDetails($conName) {
        try {
            $sql = "SELECT con_name, name, label, target_col,ref_class,ref_field, col_type, selected_attr, group_no
                FROM
                    $this->tableName
                WHERE
                    BINARY con_name = '$conName'";

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

    public function getMappedDataColumns($con) {
        try {

            $sql = "SELECT a.name,b.data_key_name,b.overwrite,b.obj_key_prefix";
            $sql .= " FROM " . $this->tableName . " as a inner join tblDataConnection";
            $sql .= " as b on a.con_name = b.con_name";
            $sql .= " WHERE a.target_col !=' ' and a.col_type != 'fieldcollection' and a.col_type != 'objectbrick' and a.con_name = '" . $con . "'";
            $columnSource = $this->db->fetchAll($sql);
            if (count($columnSource) > 0) {
                return $columnSource;
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
    
    public function saveData ($tableName, $fieldValArr) {
        $valString = "";
        $columnString = "";
        foreach($fieldValArr as $key => $val) {
            $columnString .= $key.",";
            $valString .= "'".$val."',";
        }
        $columnString = rtrim($columnString, ',');
        $valString = rtrim($valString, ',');
        $insertQuery = "INSERT INTO ".$tableName." (".$columnString.") VALUES (".$valString.")";
        $this->db->query($insertQuery);
        return $this->db->lastInsertId();
    }

}
