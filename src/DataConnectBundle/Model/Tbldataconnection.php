<?php

namespace DataConnectBundle\Model;

use Pimcore\Model\AbstractModel;

/**
 * Tbldataconnection
 */
class Tbldataconnection extends AbstractModel {

    protected $entity;

    public function __construct() {
        //$this->entity = new \DataConnectBundle\Entity\Tbldataconnection();
    }

    /**
     * Get connections listing
     *
     * @return array
     */
    public function getConnections() {
        try {
            $obj = new self;
            $data = $obj->getDao()->getConnections();
            return $data;
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }

    /**
     * Add new connection
     *
     * @param string $name
     * @return json
     */
    public function addConnectionName($name) {
        try {
            $obj = new self;
            $data = $obj->getDao()->addConnection($name);

            return $data;
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }

    /**
     * Check connection exists
     *
     * @param string $name
     * @return array
     */
    public function checkDataConnectionKeyName($name) {
        try {
            $obj = new self;
            $data = $obj->getDao()->checkDataConnection($name);

            return $data;
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }

    /**
     * Get connection data
     *
     * @param string $name
     * @return array
     */
    public function getConnectionDetail($name) {
        try {
            $obj = new self;

            $execOrder = $class = $bridge = $keyName = $overwrite = $languageKey = '';
            $dataSourceConfig = [];

            $dataSource = $obj->getDao()->getConnectionDetail($name);

            foreach ($dataSource as $key => $source) {

                $dataSourceConfig['type'] = $source['brickType'];
                if (!empty($source['brickType']) && $source['brickType'] == 'csv') {
                    $csvObj = new Tblcsvbrick();
                    $csvData = $csvObj->getBrickData($source['brick_id']);

                    $dataSourceConfig['delimiter'] = $csvData[0]['delimiter'];
                    $dataSourceConfig['csvFilePath'] = $csvData[0]['file_path'];
                }
                $bridge = $source['bridge_id'];
                $class = $source['class_name'];
                $execOrder = $source['exec_order'];
                $keyName = $source['data_key_name'];
                $overwrite = $source['overwrite'];
                $languageKey = $source['language_key'];
                $targetPath = $source['target_path'];
                $obj_key_prefix = $source['obj_key_prefix'];
                $logs = $source['logs'];
                $importPath = $source['import_path'];
                $is_active = $source['is_active'];
            }

            return array(
                "exec_order" => $execOrder,
                "class" => $class,
                "bridge_id" => $bridge,
                "dataSource" => [
                    $dataSourceConfig
                ],
                "keyName" => $keyName,
                "overwrite" => $overwrite,
                'language_key' => $languageKey,
                'target_path' => $targetPath,
                'obj_key_prefix' => $obj_key_prefix,
                'log' => $logs,
                'importPath' => $importPath,
                'active' => $is_active
            );
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    public function deleteConnection($name) {
        try {

            $obj = new self;
            $obj->getDao()->delete($name);
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    public function renameConnection($oldName, $updatedName)
    {
        try {
            $obj = new self;
            $obj->getDao()->rename($oldName, $updatedName);
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    public function loadMapping($where = array()) {

        try {
            $obj = new self;
            $return = $obj->getDao()->loadMappingData($where);
            return $return;
        } catch (Exception $ex) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     * Update mapping
     *
     * @param string $name            
     * @return json
     */
    public function updateMapping($name, $update = array()) {
        try {

            $obj = new self;
            $data = $obj->getDao()->updateMappingData($name, $update);
            return $data;
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     * Update connection and mapping data
     *
     * @param string $name            
     * @param array $data            
     * @return boolean
     */
    public function updateConnectionDetail($name, $data, $bridgeId = 0) {
        try {

            $obj = new self;
            $dataSource = $data->dataSourceConfig[0];
            if ($dataSource->type == 'csv') {
                $brickObj = new Tblcsvbrick();
                $checkCSVBrick = $brickObj->checkBrick($dataSource->delimiter, $dataSource->csvFilePath);

                if (count($checkCSVBrick)) {
                    $brickData = $checkCSVBrick[0];
                } else {
                    $brickData = $brickObj->save($dataSource->delimiter, $dataSource->csvFilePath);
                }

                $data->brick_id = $brickData['id'];
            }



            $obj->getDao()->updateConnectionDetail($name, $data);
            

            $mapobj = new Tblcolumnconnectiondetails();
            $update = "update";
            $getTargetMappings = $mapobj->getSourceData($name, $update);

            $targetCol = array();
            if (count($getTargetMappings) > 0) {
                foreach ($getTargetMappings as $t) {
                    $targetCol[$t['name']]['target'] = $t['target_col'];
                    $targetCol[$t['name']]['ref_class'] = $t['ref_class'];
                    $targetCol[$t['name']]['col_type'] = $t['col_type'];
                    $targetCol[$t['name']]['selected_attr'] = $t['selected_attr'];
                    $targetCol[$t['name']]['group_no'] = $t['group_no'];
                    $targetCol[$t['name']]['ref_field'] = $t['ref_field'];
                }
                $mapobj->deleteMapping($name);
            }
            

            
            if (isset($data->columnConfiguration)) {
                foreach ($data->columnConfiguration as $key => $col) {
                    $mapobj->saveMapping($name, $col, $targetCol);
                }
            }

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
    public function updateDataConnectionKeyName($name, $keyName, $overwrite = null, $objKeyName = null) {
        try {
            $obj = new self;
            $obj->getDao()->updateDataConnectionKeyName($name, $keyName, $overwrite, $objKeyName);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getBridgeConnectionsData($bridge) {
        try {

            $obj = new self;
            $data = $obj->getDao()->getBridgeConnections($bridge);
            return $data;
        } catch (Exception $ex) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     * Get data connection details
     * 
     * @param string $connIds            
     * @return mixed
     * @throws Exception
     */
    public function getDataConnectionDetails($connIds = null) {
        try {

            $obj = new self;
            $data = $obj->getDao()->getConnectionDetailsData($connIds);
            return $data;
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     * get Active Mapping
     * 
     * @param string $connIds            
     */
    public function checkActiveMappingDetails($name, $importPath) {
        try {

            $obj = new self;
            $data = $obj->getDao()->checkActiveMapping($name, $importPath);
            return $data;
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     * update mapping
     * @param type $name Description
     */
    public function updateOldActiveMapping($name, $oldname) {
        try {

            $obj = new self;
            $data = $obj->getDao()->updateActiveMapping($name, $oldname);
            return $data;
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     * update mapping
     * @param type $name Description
     */
    public function activateFirstTimeMapping($name, $active) {
        try {

            $obj = new self;
            $data = $obj->getDao()->activateFirstTimeMapping($name, $active);
            return $data;
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

    public function getConnection($where = null) {
        try {
            $obj = new self;
            $data = $obj->getDao()->getConnection($where);
            return $data;
        } catch (\Exception $e) {
            return array(
                'msg' => $e->getMessage()
            );
        }
    }

}
