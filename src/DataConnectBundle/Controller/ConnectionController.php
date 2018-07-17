<?php

/**
 * DataConnectBundle\ConnectionController
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) 2009-2016 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 * @desc This file is used for data connector related manipulation
 *
 */

namespace DataConnectBundle\Controller;

use Codeception\Lib\Connector\Symfony;
use Pimcore\Config\Config;
use Pimcore\Tool;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Pimcore\Model\Object\Bridge;
use Pimcore\Model\Object\ClassDefinition;
use DataConnectBundle\Model\ResourceManager;
use DataConnectBundle\Entity\Tblcsvbrick;
use DataConnectBundle\Model\Tbldataconnection;
use DataConnectBundle\Model\Tblcolumnconnectiondetails;
use DataConnectBundle\Model\Tbljobmaster;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use DataConnectBundle\Console\Command\ImportCommand;
use DataConnectBundle\DataConnectBundle;
use DataConnectBundle\Event\Log;

/**
 * DataConnectBundle\ConnectionController Class
 */
class ConnectionController extends Controller
{

    /**
     *
     * @var array
     */
    public $config;

    /**
     *
     * @var object
     */
    public $conObj;

    /**
     * Get connectors tree listing
     * @Route("/connection/tree", name="connection_tree")
     * @return json
     */
    public function treeAction()
    {
        try {

            $reports = [];
            $obj = new Tbldataconnection();

            $resp = $obj->getConnections();
            foreach ($resp as $key => $value) {
                $reports[$key]['id'] = $value['con_name'];
                $reports[$key]['text'] = $value['con_name'];
            }

            return $this->json($reports);
        } catch (\Exception $ex) {
            $info = [
                'msg' => "ERROR : " . $ex->getMessage() . "\n" . $ex->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json(
                [
                    "success" => false,
                    'msg' => $ex->getMessage()
                ]
            );
        }
    }

    /**
     *
     * @Route("/connection/check-mapping", name="mapping_check")
     * @return json
     */
    public function checkMappingAction(Request $request)
    {
        try {

            $conName = $request->request->get('name');
            $db = \Pimcore\Db::get();
            $result = $db->fetchAll("SELECT * FROM `tblDataConnection` WHERE `class_name` = '' AND `brick_id` = '0' AND `con_name` = ?", [$conName]);
            if (is_array($result) && count($result) > 0) {
                $success = false;
            } else {
                $success = true;
            }
            return $this->json([
                'success' => $success
            ]);
        } catch (\Exception $ex) {
            return $this->json(
                [
                    "success" => false,
                    'msg' => $ex->getMessage()
                ]
            );
        }
    }

    /**
     * Add folder
     * @Route("/connection/add-folder", name="create_folder")
     */
    public function addFolderAction(Request $request)
    {
        try {
            $name = $request->query->get('name');
            $checkFolder = \Pimcore\Model\Asset::getByPath('/DataConnect');
            if ($checkFolder) {
                $checkName = \Pimcore\Model\Asset::getByPath('/DataConnect/' . $name);
                if (empty($checkName)) {

                    $folder = \Pimcore\Model\Asset::create($checkFolder->getId(), [
                        'filename' => $name,
                        'type' => 'folder',
                        'userOwner' => 1,
                        'userModification' => 1
                    ]);
                } else {
                    $folder = $checkName;
                }
                return $this->json(
                    [
                        "success" => true,
                        'id' => $folder->getId()
                    ]
                );
            } else {
                return $this->json(
                    [
                        "success" => false,
                    ]
                );
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Add data connector
     * @Route("/connection/add", name="add_connection")
     * @return json
     */
    public function addAction(Request $request)
    {
        try {

            $success = true;
            $conn = "";
            $name = $request->query->get('name');
            $connResult = $this->checkConnectionExists($name);
            if ($connResult == true) {
                $obj = new Tbldataconnection();
                $result = $obj->addConnectionName($name);
                $info = [
                    'msg' => "Connection added - " . $name,
                    'type' => 'info',
                    'component' => date("Y-m-d")
                ];
                $this->log($info);
                $conn = true;
            } else {
                $conn = false;
            }
            return $this->json(
                [
                    "success" => $success,
                    "id" => $name,
                    "conn" => $conn
                ]
            );
        } catch (\Exception $ex) {
            $info = [
                'msg' => "ERROR : " . $ex->getMessage() . "\n" . $ex->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json(
                [
                    "success" => false,
                    'msg' => $ex->getMessage()
                ]
            );
        }
    }

    /**
     * check if connection exists
     *
     */
    public function checkConnectionExists($name)
    {

        $obj = new Tbldataconnection();
        $connections = $obj->getConnections();
        if (count($connections) > 0) {
            foreach ($connections as $cKey => $cValue) {
                if (in_array($name, $cValue)) {
                    return false;
                    break;
                }
            }
        }
        return true;
    }

    /**
     * Get connector details
     * @Route("/connection/get-connection-details", name="get_connection")
     * @return json
     */
    public function getConnectionDetailsAction(Request $request)
    {
        try {
            $name = $request->query->get('name');

            $obj = new Tbldataconnection();
            $mappingObj = new Tblcolumnconnectiondetails();
            // Check connection exists
            $checkConn = $obj->checkDataConnectionKeyName($name);
            if (!count($checkConn)) {
                $info = [
                    'msg' => "Error : Connection doesn't exists.",
                    'type' => 'error',
                    'component' => date("Y-m-d")
                ];
                $this->log($info);
                $this->json([
                    "success" => false
                ]);
            }
            // Get details
            $res = $obj->getConnectionDetail($name);

            $brickArr = [];
            $colAttr = [];
            $mapping = $mappingObj->getMapping($name);
            if (!empty($mapping['columnSource'])) {
                $class = ClassDefinition::getById($checkConn[0]['class_name']);
                $classFields = $class->getFieldDefinitions();
                foreach ($classFields as $ckey => $cvalue) {
                    if ($cvalue->getFieldType() == 'objectbricks') {
                        foreach ($cvalue->getAllowedTypes() as $nkey => $nvalue) {
                            if ($nvalue) {
                                $brickArr[] = $cvalue->getName();
                            }
                        }
                    }

                    if ($cvalue->getFieldType() == 'fieldcollections') {
                        foreach ($cvalue->getAllowedTypes() as $nkey => $nvalue) {
                            if ($nvalue) {
                                $colAttr[] = $cvalue->getName();
                            }
                        }
                    }
                }
            }


            $config = \Pimcore\Config::getSystemConfig();
            $validLanguages = explode(",", $config->general->validLanguages);
            $langConfig = array();
            foreach ($validLanguages as $lang) {
                $langConfig[] = $lang;
            }

            $allowedClassData = [];
            if (!empty($res['class'])) {
                $allowedClassData = $this->getAllowedColoumnMappingClasses($res['class']);
            }

            return $this->json(
                [
                    "success" => true,
                    'name' => $name,
                    'allowedClasses' => $allowedClassData,
                    'dataSourceConfig' => $res['dataSource'],
                    'columnConfiguration' => $mapping['columnSource'],
                    'className' => $res['class'],
                    'active' => $res['active'],
                    'brick' => $brickArr,
                    'collection' => $colAttr,
                    'importPath' => $res['importPath'],
                    'order' => $res['exec_order'],
                    'langConfg' => $langConfig,
                    'language_key' => $res['language_key'],
                    'target_path' => $res['target_path'],
                    'obj_key_prefix' => $res['obj_key_prefix'],
                    'logs' => $res['log']
                ]
            );
        } catch (\Exception $ex) {
            $info = [
                'msg' => "ERROR : " . $ex->getMessage() . "\n" . $ex->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json(
                [
                    "success" => false,
                    'msg' => $ex->getMessage()
                ]
            );
        }
    }

    /**
     * this will get the data for classes of objects and meta
     *
     */
    public function getAllowedColoumnMappingClasses($class)
    {
        try {
            $classArr = [];
            $classDef = ClassDefinition::getById($class);
            $fieldData = $classDef->getFieldDefinitions();
            if (!empty($fieldData)) {
                $i = 0;
                foreach ($fieldData as $fkey => $fvalue) {
                    if ($fvalue->getFieldType() == "objects") {
                        foreach ($fvalue->getClasses() as $nkey => $nvalue) {
                            $classArr[$i] = [
                                $nvalue['classes'], $nvalue['classes']
                            ];
                            $i++;
                        }
                    }
                }
            }
            return $classArr;
        } catch (\Exception $e) {
            $info = [
                'msg' => "Error in getting mapped data: " . $e->getMessage(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * @Route("/connection/get-allowed-classes")
     */
    public function getAllowedColumnClassesAction(Request $request)
    {
        try {
            //$field = $request->request->get('field');
            $class = $request->request->get('classId');
            $classArr = [];
            $classDef = ClassDefinition::getById($class);
            $fieldData = $classDef->getFieldDefinitions();

            if (!empty($fieldData)) {
                $i = 0;
                foreach ($fieldData as $fkey => $fvalue) {
                    if ($fvalue->getFieldType() == "objects") {
                        foreach ($fvalue->getClasses() as $nkey => $nvalue) {
                            $classArr[$i] = [
                                $nvalue['classes'], $nvalue['classes']
                            ];
                            $i++;
                        }
                    }
                }
            }

            return $this->json(["class" => $classArr, "success" => true]);
        } catch (\Exception $e) {
            $info = [
                'msg' => "Error in getting mapped data: " . $e->getMessage(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get classes listing
     *
     * @return array
     */
    protected function getClassDetails()
    {
        try {

            $className = [];
            $class = new ClassDefinition\Listing();
            $classList = $class->load();
            $i = 0;
            foreach ($classList as $cls) {
                $className[$i]["class_name"] = $cls->getName();
                $className[$i]["class_id"] = $cls->getId();
                $i++;
            }
            return $className;
        } catch (\Exception $ex) {
            $info = [
                'msg' => "ERROR : " . $ex->getMessage() . "\n" . $ex->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return array();
        }
    }

    /**
     * Delete data connector
     * @Route("/connection/delete", name="delete_connection")
     * @return json
     */
    public function deleteAction(Request $request)
    {
        try {

            $name = $request->query->get('name');
            $obj = new Tbldataconnection();
            $mappingObj = new Tblcolumnconnectiondetails();
            $obj->deleteConnection($name);
            $mappingObj->deleteMapping($name);
            $info = [
                'msg' => "Connection deleted - " . $name,
                'type' => 'info',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json(
                [
                    "success" => true
                ]
            );
        } catch (\Exception $ex) {
            $info = [
                'msg' => "ERROR : " . $ex->getMessage() . "\n" . $ex->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json(
                [
                    "success" => false,
                    'msg' => $ex->getMessage()
                ]
            );
        }
    }

    /**
     * Duplicate data connector
     * @Route("/connection/duplicate", name="duplicate_connection")
     * @return json
     */
    public function duplicateAction(Request $request)
    {

        $resTblCSVBrick = "";
        $newBrickId = "";
        $panelOldId = $request->query->get('panelId');
        $newPanelId = $panelOldId . "_copy";
        $tblConnDetObj = new Tblcolumnconnectiondetails();
        $db = \Pimcore\Db::get();

        $sqlTblDataConn = "SELECT * FROM `tblDataConnection` WHERE `con_name` = ?";
        $resTblDataConnNew = $db->fetchRow($sqlTblDataConn, [$newPanelId]);

        if ($resTblDataConnNew['con_name'] != $newPanelId) {
            $resTblDataConn = $db->fetchRow($sqlTblDataConn, [$panelOldId]);
            $brickId = $resTblDataConn['brick_id'];

            if (!empty($brickId)) {
                $sqlTblCSVBrick = "SELECT * FROM `tblCSVBrick` WHERE `id` = ?";
                $resTblCSVBrick = $db->fetchRow($sqlTblCSVBrick, [$brickId]);

                $tblCsvBrickArr = array(
                    'file_path' => $resTblCSVBrick['file_path'],
                    'delimiter' => $resTblCSVBrick['delimiter']
                );
                $newBrickId = $tblConnDetObj->getSaveData("tblCSVBrick", $tblCsvBrickArr);
            }

            $sqlTblDataConnDetails = "SELECT * FROM `tblColumnConnectionDetails` WHERE `con_name`=?";
            $resTblDataConnDetails = $db->fetchAll($sqlTblDataConnDetails, [$panelOldId]);

            foreach ($resTblDataConnDetails as $tblDetRecord) {
                $tblDetArr = array("con_name" => $newPanelId,
                    "name" => $tblDetRecord['name'],
                    "label" => $tblDetRecord['label'],
                    "display" => $tblDetRecord['display'],
                    "target_col" => $tblDetRecord['target_col'],
                    "ref_class" => $tblDetRecord['ref_class'],
                    "ref_field" => $tblDetRecord['ref_field'],
                    "col_type" => $tblDetRecord['col_type'],
                    "selected_attr" => $tblDetRecord['selected_attr'],
                    "group_no" => $tblDetRecord['group_no']
                );

                $tblConnDetObj->getSaveData("tblColumnConnectionDetails", $tblDetArr);
            }

            $tblDataArr = array(
                "con_name" => $newPanelId,
                "bridge_id" => $resTblDataConn['bridge_id'],
                "class_name" => $resTblDataConn['class_name'],
                "brickType" => $resTblDataConn['brickType'],
                "exec_order" => $resTblDataConn['exec_order'],
                "data_key_name" => $resTblDataConn['data_key_name'],
                "overwrite" => $resTblDataConn['overwrite'],
                "language_key" => $resTblDataConn['language_key'],
                "target_path" => $resTblDataConn['target_path'],
                "obj_key_prefix" => $resTblDataConn['obj_key_prefix'],
                "total_records" => $resTblDataConn['total_records'],
                "brick_id" => $newBrickId,
                "logs" => $resTblDataConn['logs'],
                "import_path" => $resTblDataConn['import_path'],
                "is_active" => '0',
                "bridge_type" => $resTblDataConn['bridge_type']

            );
            $tblConnDetObj->getSaveData("tblDataConnection", $tblDataArr);
            return $this->json([
                "success" => true,
                "id" => $newPanelId,
                "errorMessage" => $errorMessage
            ]);
        } else {
            return $this->json([
                "success" => false,
                "errorMessage" => "Duplicate Already Exists"
            ]);
        }


    }

    /**
     * Rename Connector
     * @Route("/connection/rename", name="rename_connection")
     * @param Request $request
     * @return JsonResponse
     */
    public function renameAction(Request $request)
    {
        try {
            $success = true;
            $oldName = $request->query->get('oldName');
            $updatedName = $request->query->get('updatedName');
            $connResult = $this->checkConnectionExists($updatedName);
            if($connResult){
                $obj = new Tbldataconnection();
                $mappingObj = new Tblcolumnconnectiondetails();
                $obj->renameConnection($oldName, $updatedName);
                $mappingObj->renameMapping($oldName, $updatedName);
                $info = [
                    'msg' => "Connection updated - " . $updatedName,
                    'type' => 'info',
                    'component' => date("Y-m-d")
                ];
                $this->log($info);
                $name = $updatedName;
                $conn = true;
            } else {
                $info = [
                    'msg' => "Connection name exists - " . $updatedName,
                    'type' => 'info',
                    'component' => date("Y-m-d")
                ];
                $name = $oldName;
                $this->log($info);
                $conn = false;
            }
            return $this->json(
                [
                    "success" => $success,
                    "id" => $name,
                    "conn" => $conn
                ]
            );
        } catch (\Exception $ex) {
            $info = [
                'msg' => "ERROR : " . $ex->getMessage() . "\n" . $ex->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json(
                [
                    "success" => false,
                    'msg' => $ex->getMessage()
                ]
            );
        }
    }


    /**
     * Get columns based on query
     * @Route("/connection/get-data-column", name="get_data_column")
     * @return json
     */
    public function getDataColumnAction(Request $request)
    {
        $bridgeType = $request->request->get('bridgeType');
        $configuration = $request->request->get('configuration');


        if (!empty($bridgeType)) {
            $bridge = \Object::getByPath($bridgeType);

            if ($bridge->getDataSource() == 'Pdo_Mysql') {
                $pdoType = 'mysql';
            } else {
                $pdoType = 'dblib'; // ms-sql server
            }


            $config = new \Zend_Config(
                array(
                    'database' => array(
                        'adapter' => $bridge->getDataSource(), // Set the Database adapter
                        'params' => array(
                            'host' => $bridge->getHostName(),
                            'dbname' => $bridge->getDatabaseName(),
                            'username' => $bridge->getUsername(),
                            'password' => $bridge->getPassword(),
                            'pdoType' => $pdoType,
                            'port' => $bridge->getPort()
                        )
                    )
                )
            );
        }


        $result = [];
        $errorMessage = '';

        $configuration = json_decode($configuration);
        $configuration = $configuration[0];
        $success = false;
        try {
            if ($bridgeType != '') {
                $db = \Zend_Db::factory($config->database);
                $db->getConnection();
                $col = $this->getColumns($configuration, $db);
                if (!isset($col['error']) && $col) {
                    foreach ($col as $key => $colname) {
                        array_push($result, $colname);
                    }
                    $success = true;
                } else {
                    $success = false;
                    $errorMessage = $col['error'];
                }
            } else {
                $col = $this->getCSVColumns($configuration);
                if (!isset($col['error']) && $col) {
                    foreach ($col as $key => $colname) {
                        array_push($result, $colname);
                    }
                    $success = true;
                } else {
                    $success = false;
                    $errorMessage = $col['error'];
                }
            }
        } catch (\Exception $ex) {
            $info = [
                'msg' => "ERROR : " . $ex->getMessage() . "\n" . $ex->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            $errorMessage = $ex->getMessage();
        }
        return $this->json(
            [
                "success" => $success,
                "columns" => $result,
                "errorMessage" => $errorMessage
            ]
        );
    }

    public function getCSVColumns($configuration)
    {
        try {
            $firstRowData = array();
            if (empty($configuration->csvFilePath)) {
                return $firstRowData;
            }
            $fileObj = \Pimcore\Model\Asset::getByPath($configuration->csvFilePath);


            if ($configuration->fromDataImportBundle) {
                $csvFilePath = $configuration->csvFilePath;
            } else {
                // handeling excel files
                $extension = pathinfo($fileObj->getFilename(), PATHINFO_EXTENSION);
                if (in_array($extension, array('xlsx', 'xls'))) {

                    $bundObj = new DataConnectBundle();
                    $config = $bundObj->getConfig();
                    $csvFolderPath = PIMCORE_PROJECT_ROOT . $config['mappingCsvFolderPath'];
                    $filePath = $csvFolderPath . '/' . $fileObj->getId() . '.csv';
                    if (!file_exists($filePath)) {
                        $conversionObj = new \DataImportBundle\Model\CsvConversion($fileObj, $csvFolderPath);
                    }
                    $csvFilePath = $filePath;
//                    $csvFilePath = \DataImportBundle\Model\CsvConversion::$conversionPath . "/" . $fileObj->getId() . ".csv";
                } else {
                    $csvFilePath = PIMCORE_ASSET_DIRECTORY . $configuration->csvFilePath;
                }


//                $csvFilePath = PIMCORE_ASSET_DIRECTORY . $configuration->csvFilePath;
            }


//            $csvFilePath = PIMCORE_ASSET_DIRECTORY . $configuration->csvFilePath;
            //echo $csvFilePath;
            $dialect = Tool\Admin::determineCsvDialect($csvFilePath);

            $count = 0;
            if (($handle = fopen($csvFilePath, 'r')) !== false) {
                while (($rowData = fgetcsv($handle, 0, $dialect->delimiter, $dialect->quotechar, $dialect->escapechar)) !== false) {
                    if ($count == 0) {
                        $actualHeaders = array();
                        for ($i = 0; $i < count($rowData); $i++) {
                            if ($rowData[$i] && (trim($rowData[$i]) != '')) {
                                $actualHeaders[] = utf8_decode(trim($rowData[$i]));
                            }
                        }
                        $firstRowData = $actualHeaders;
                    }
                    break;
                    /* $tmpData = [];
                      foreach ($rowData as $key => $value) {
                      $tmpData['field_' . $key] = $value;
                      }
                      $data[] = $tmpData;
                      $cols = count($rowData);

                      $count++;

                      if ($count > 18) {
                      break;
                      } */
                }
                fclose($handle);
            }

            return $firstRowData;
        } catch (\Exception $ex) {
            $info = [
                'msg' => "ERROR : " . $ex->getMessage() . "\n" . $ex->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return array(
                'error' => $ex->getMessage()
            );
        }
    }

    /**
     * Update data connector
     * @Route("/connection/update", name="update_connection")
     * @return json
     */
    public function updateAction(Request $request)
    {
        try {
            $configuration = $request->request->get('configuration');
            $name = $request->request->get('name');
            $decode = new JsonDecode();
            $data = $decode->decode($configuration, JSON);

            $obj = new Tbldataconnection();
            // check connection exists
            $checkConn = $obj->checkDataConnectionKeyName($name);
        
            if (count($checkConn)) {
                $obj->updateConnectionDetail($name, $data); //TO DO - bridge id need to be added
                $info = [
                    'msg' => "Connection updated - " . $name,
                    'type' => 'info',
                    'component' => date("Y-m-d")
                ];
                $this->log($info);
                return $this->json(
                    [
                        "success" => true
                    ]
                );
            } else {
                $info = [
                    'msg' => "Connection updation error - " . $name,
                    'type' => 'error',
                    'component' => date("Y-m-d")
                ];
                $this->log($info);
                return $this->json(
                    [
                        "success" => false
                    ]
                );
            }
        } catch (\Exception $ex) {
            $info = [
                'msg' => "Connection updation error - " . $name,
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json(
                [
                    "success" => false,
                    'msg' => $ex->getMessage()
                ]
            );
        }
    }

    public function getHostUrl()
    {
        try {
            if ($_SERVER['HTTPS'] == "on") {
                $http = "https://";
            } else {
                $http = "http://";
            }
            $url = $http . $_SERVER['HTTP_HOST'];
            return $url;
        } catch (\Exception $ex) {

            return $this->json(
                [
                    "success" => false,
                    "msg" => $ex->getMessage(),
                ]
            );
        }
    }

    /**
     * @Route("/connection/checkauth", name="checkauth")
     * @param Request $request
     * @throws \Exception
     */
    public function checkauthAction(Request $request)
    {
        try {

            $bundObj = new DataConnectBundle();
            $configData = $bundObj->getConfig();
            $userId = \Pimcore\Tool\Authentication::authenticateSession()->getId();
            $status = 'in-progress';
            $dataConnection = new Tbldataconnection();
            if ($request->query->get("object_id") != '') {

                $conn = $dataConnection->getBridgeConnectionsData($request->query->get("name"));
                $conn = implode(",", $conn);
            } else {
                $conn = $request->query->get("name");
            }
            $timeoutFlag = true;
            $jobmaster = new Tbljobmaster();
            $checkJobExists = $jobmaster->getConnJobDetailsData($conn, $status);
            $checkTimeout = $jobmaster->getConnJobDetailsData($conn, $status, 1);

            // Check connection timeout
            foreach ($checkTimeout as $chtime) {
                if (((strtotime(date("Y-m-d H:i:s")) - strtotime($chtime)) / ($configData['timeout'])) < 60) {
                    $timeoutFlag = false;
                    break;
                }
            }
            $msg = "This connection is already in progress. Please wait for this to finish to run it again.";
            if (count($checkJobExists)) {

                if (!isset($checkJobExists[$userId])) {
                    // locking enable
                    if ($configData['execlock'] == 'enable') {
                        if ($timeoutFlag) {
                            return $this->json(
                                [
                                    "success" => false,
                                    "checkresume" => true,
                                    "conn" => $conn,
                                ]
                            );
                        } else {
                            throw new \Exception($msg);
                        }
                    } // locking disable 
                    else {
                        if ($timeoutFlag) {
                            return $this->json(
                                [
                                    "success" => false,
                                    "checkresume" => true,
                                    "conn" => $conn,
                                ]
                            );
                        } else {
                            throw new \Exception($msg);
                        }
                    }
                } else if (isset($checkJobExists[$userId])) {
                    // locking enable
                    if (count($checkJobExists) > 1 && $configData['execlock'] == 'enable') {

                        if ($timeoutFlag) {
                            return $this->json(
                                [
                                    "success" => false,
                                    "checkresume" => true,
                                    "conn" => $conn,
                                ]
                            );
                        } else {
                            throw new \Exception($msg);
                        }
                    } // locking disable 
                    else {

                        if ($timeoutFlag) {
                            return $this->json(
                                [
                                    "success" => false,
                                    "checkresume" => true,
                                    "conn" => $conn,
                                ]
                            );
                        } else {
                            throw new \Exception($msg);
                        }
                    }
                }
            } else {

                $connId = explode(",", $conn);
                foreach ($connId as $con) {
                    $job = substr(microtime(), 2, 6);
                    $insert['job_id'] = $job;
                    $insert['records'] = 0;
                    $insert['user_id'] = $userId;
                    $insert['start'] = '0000-00-00 00:00:00';
                    $insert['last_update'] = '0000-00-00 00:00:00';
                    $jobmaster->addJobData($insert, $con);
                }

                return $this->json(
                    [
                        "success" => true,
                        "conn" => $conn,
                    ]
                );
            }
        } catch (\Exception $ex) {
            $info = [
                'msg' => "ERROR : " . $ex->getMessage() . "\n" . $ex->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);

            return $this->json(
                [
                    "success" => false,
                    "msg" => $ex->getMessage(),
                ]
            );
        }
    }

    /**
     * Execute import
     * @Route("/connection/run", name="run")
     * @return json
     */
    public function runAction(Request $request)
    {
        try {

            $status = $request->query->get("status");
            if ($request->query->get("object_id") != '') {
                $dataConnection = new Tbldataconnection();
                $conn = $dataConnection->getBridgeConnectionsData($request->query->get("name"));
                $conn = implode(",", $conn);
            } else {
                $conn = $request->query->get("name");
            }
            $cmd = new ImportCommand();
            $return = $cmd->webExecute($conn, $status);

            if ($return['success']) {
                return $this->json(
                    [
                        "success" => true,
                        "msg" => $return['msg']
                    ]
                );
            } else {
                return $this->json(
                    [
                        "success" => false,
                        "msg" => $return['msg']
                    ]
                );
            }
        } catch (\Exception $ex) {
            $info = [
                'msg' => "ERROR : " . $ex->getMessage() . "\n" . $ex->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json(
                [
                    "success" => false,
                    "msg" => $ex->getMessage(),
                ]
            );
        }
    }

    public function log($info)
    {
        try {

            $dispatcher = $this->get('event_dispatcher');
            $logEvent = new Log();
            $logEvent->setInfo($info);
            $dispatcher->dispatch('log_event', $logEvent);
        } catch (Exception $ex) {

        }
    }

    /**
     * Activate Mapping
     * @Route("/connection/activate-mapping",name="activate-mapping")
     */
    public function activateMappingAction(Request $request)
    {
        try {
            $configuration = $request->request->get('configuration');
            $name = $request->request->get('name');
            $active = $request->request->get('active');
            $decode = new JsonDecode();
            $data = $decode->decode($configuration, JSON);
            $obj = new Tbldataconnection();
            if (!empty($data)) {
                $resultData = $obj->checkActiveMappingDetails($name, $data->importPath);
                if ($resultData && $resultData[0]['con_name'] != $name) {
                    $update = $obj->updateOldActiveMapping($name, $resultData[0]['con_name']);
                } else if ($resultData && $resultData[0]['con_name'] == $name) {
                    $doActive = $obj->activateFirstTimeMapping($name, $active);
                } else {
                    $doFirstTimeActive = $obj->activateFirstTimeMapping($name, $active);
                }
                return $this->json(
                    [
                        "success" => true,
                    ]
                );
            }
        } catch (Exception $ex) {
            $info = [
                'msg' => "ERROR : " . $ex->getMessage() . "\n" . $ex->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json(
                [
                    "success" => false,
                    "msg" => $ex->getMessage(),
                ]
            );
        }
    }

    /**
     * Activate Mapping
     * @Route("/connection/check-activate-mapping",name="check-activate-mapping")
     */
    public function checkActivateMappingAction(Request $request)
    {
        try {
            $configuration = $request->request->get('configuration');
            $name = $request->request->get('name');
            $decode = new JsonDecode();
            $data = $decode->decode($configuration, JSON);
            $obj = new Tbldataconnection();
            if (!empty($data)) {
                $resultData = $obj->checkActiveMappingDetails($name, $data->importPath);
                if ($resultData) {
                    return $this->json(
                        [
                            "success" => true,
                        ]
                    );
                } else {
                    return $this->json(
                        [
                            "success" => false,
                        ]
                    );
                }
            }
        } catch (Exception $ex) {
            $info = [
                'msg' => "ERROR : " . $ex->getMessage() . "\n" . $ex->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json(
                [
                    "success" => false,
                    "msg" => $ex->getMessage(),
                ]
            );
        }
    }
}
