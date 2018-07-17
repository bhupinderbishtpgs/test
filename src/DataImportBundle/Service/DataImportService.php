<?php

/**
 * DataImportService
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
 * @desc This file is used to run the import service
 *
 */

namespace DataImportBundle\Service;

use DataImportBundle\DataImportBundle;
use Pimcore\Model\Asset;
use Pimcore\Model\Object\ClassDefinition;
use DataImportBundle\Service\DataRetriever\CSVDataRetriever;
use DataConnectBundle\Model\Tbljobqueue;
use DataConnectBundle\Model\Tblsrcdestmapping;
use DataConnectBundle\Model\Tbljobmaster;
use Pimcore\Model\Object\Fieldcollection;
use DataConnectBundle\Event\Log;
use DataConnectBundle\Service\DataMappingService;
use Pimcore\Model\DataObject\Job;

/**
 * DataImportService Class
 */
class DataImportService extends DataMappingService
{
    /** @var mixed */
    private $config;

    /** @var string */
    private $languageKey;

    /** @var int */
    private $limit = 10;

    /** @var boolean */
    private $overwrite;

    /** @var string */
    private $separator = ",";

    /** @var string */
    private $brickType;

    /** @var int */
    private $numRecord = 0;

    /** @var string */
    private $connName;

    /** @var string */
    private $parentId;

    /** @var array */
    private $classId;

    /** @var string */
    private $keyName;

    /** @var string */
    private $jobId;

    /** @var int */
    private $failedRecordsCount = 0;

    /** @var int */
    private $createdRecordsCount = 0;

    /** @var int */
    private $updatedRecordsCount = 0;

    /** @var string */
    private $logComponent = "data-import-";

    /** @var array */
    private $records = [];

    /** @var array */
    private $mapper = [];

    /** @var array */
    private $errorMessage = [];

    /** @var string */
    private $className;

    /** @var object */
    private $clsObj;

    /**
     * initialize
     *
     * @param string $conName
     */
    public function __construct($conName)
    {
        parent::__construct($conName);
        $this->config = DataImportBundle::getConfig();
        if (!empty($this->config['importlimit'])) {
            $this->limit = $this->config['importlimit'];
        }

        if (!empty($this->config['separator'])) {
            $this->separator = $this->config['separator'];
        }

    }

    /**
     * @param $queue
     * @param $con
     * @param $jobId
     * @return array
     */
    public function runImport($queue, $con, $jobId)
    {
        try {
            $info = [
                'msg' => "Execution start",
                'type' => 'info',
                'component' => date("Y-m-d")
            ];
            $this->log($info);

            $this->languageKey = $con['language_key'];
            $this->connName = $queue->getMapping();
            $this->overwrite = $con['overwrite'];
            $this->brickType = $con['dataSource'][0]['type'];
            $this->keyName = $con['keyName'];
            $this->classId = $con['class'];
            $this->jobId = $jobId;

            $targetPath = $con['target_path'];
            $parentObj = \Pimcore\Model\DataObject::getByPath($targetPath);


            if (empty($parentObj)) {
                return array(
                    'success' => false,
                    'msg' => "Error : Target Path folder specified not exists."
                );
            }
            $this->parentId = $parentObj->o_id;

            $return = $this->getData($queue->getAsset());
            if ($return['success']) {
                $total = "Total : $this->numRecord records processed.";
                $created = "Created : $this->createdRecordsCount";
                $updated = "Updated: $this->updatedRecordsCount";
                $failed = "Failed: $this->failedRecordsCount";
                $msg = $total . "\n" . $created . "\n" . $updated . "\n" . $failed;

                $info = [
                    "msg" => $msg,
                    'type' => 'info',
                    'component' => $this->logComponent . date("Y-m-d")
                ];
                $this->log($info);

                $this->records[count($this->records)][0] = $total;
                $this->records[count($this->records)][1] = $created;
                $this->records[count($this->records)][2] = $updated;
                $this->records[count($this->records)][3] = $failed;
                $info = [
                    'msg' => "Execution end.",
                    'type' => 'info',
                    'component' => $this->logComponent . date("Y-m-d")
                ];
                $this->log($info);

                if ($con['log'] == '1') {
                    $this->createCsv($this->records, $this->jobId);
                }

                // job update details after completion
                $job = Job::getById($this->jobId, true);

                if (is_object($job)) {
                    $job->setCreated($this->createdRecordsCount);
                    $job->setUpdated($this->updatedRecordsCount);
                    $job->setFailed($this->failedRecordsCount);
                    $job->save();
                }

                return array(
                    'success' => true,
                    'msg' => "Job successfully executed"
                );
            } else {
                throw new \Exception($return['msg']);
            }
        } catch (\Exception $exception) {
            $info = [
                'msg' => "Error execution : " . $exception->getMessage(),
                'type' => 'error',
                'component' => $this->logComponent . date("Y-m-d")
            ];
            $this->log($info);

            return array(
                'success' => false,
                'msg' => $exception->getMessage() . "\n" . $exception->getTraceAsString()
            );
        }
    }


    /**
     * @param Asset $asset
     * @return array
     */
    protected function getData($asset)
    {
        try {
            $assetPath = $asset->getFullpath();
            $extension = pathinfo($assetPath, PATHINFO_EXTENSION);
            if (in_array($extension, array('xlsx', 'xls'))) {
                $excelFlag = true;
                $assetPath = \DataImportBundle\Model\CsvConversion::$conversionPath . "/" . $asset->getId() . ".csv";
            }

            $CSVObj = new CSVDataRetriever();
            $dataSet = $CSVObj->getCSVData($assetPath, $excelFlag);
            if (isset($dataSet['error'])) {
                throw new \Exception($dataSet['error']);
            }

            $count = count($dataSet);
            $this->numRecord = $this->numRecord + $count;

            if ($count > 0) {
                $setMapper = $this->setDataMapper($dataSet[0]);
                if ($setMapper['success']) {
                    $setDataStatus = $this->setData($dataSet);                
                    if ($setDataStatus['success']) {
                        return array(
                            'success' => true,
                        );
                    } else {
                        throw new \Exception($setDataStatus['msg']);
                    }
                } else {
                    throw new \Exception($setMapper['msg']);
                }
            }
        } catch (\Exception $exception) {
            $info = [
                'msg' => "Object failure : " . $exception->getMessage() . "\n" . $exception->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);

            return array(
                'success' => false,
                'msg' => $exception->getMessage()
            );
        }
    }


    /**
     * @param $data
     * @return array
     */
    protected function setData($data)
    {
        try {
            $this->className = ClassDefinition::getById($this->classId)->getName();
            $classdef = $this->getClassDefinition($this->className);
            if ((strtolower($this->className) == 'product') && class_exists("\\AppBundle\\Model\\DataObject\\Product")) {
                $class = "\\AppBundle\\Model\\DataObject\\Product";
            } else {
                $class = "\\Pimcore\\Model\\DataObject\\" . ucfirst($this->className);
            }
            foreach ($data as $sn => $dataSet) {

                $this->records[$sn][0] = $dataSet[$this->keyName];
                $this->errorMessage = [];

                $this->clsObj = new $class();
                $checkobj = $this->checkObjectExist($dataSet, $class, $classdef);
                $checkFlag = false;
                if ($checkobj) {
                    $this->clsObj = $checkobj;
                    $checkFlag = true;
                    if (isset($dataSet['modificationdate'])) {
                        $srcDestMapping = new Tblsrcdestmapping();
                        $checkMapping = $srcDestMapping->checkMappingExists($clsObj->o_id);
                        if (strtotime($dataSet['modificationdate']) < $checkMapping['created']) {
                            continue;
                        }
                    }
                }

                $this->clsObj->setOmitMandatoryCheck(true);

                // set object data relationship
                $this->setDataRelationship($dataSet, $classdef);

                $key = \Pimcore\File::getValidFilename($dataSet[$this->keyName]);

                if ($checkFlag) {
                    if ($this->overwrite === 'yes') {
                        $this->clsObj->setKey($key);
                        try {
                            $this->clsObj->setPublished(false);
                            $this->clsObj->save();
                            $this->updatedRecordsCount++;
                            $this->records[$sn][1] = $this->clsObj->getId();
                            if (count($this->errorMessage)) {
                                $this->records[$sn][2] = "Relation Data Failed.";
                                $this->records[$sn][3] = "Object updated successfully with missing relation data for " . implode(",", $this->errorMessage);
                            } else {
                                $this->records[$sn][2] = "Success";
                                $this->records[$sn][3] = "Object updated successfully.";
                            }
                        } catch (\Exception $ex) {

                            $errorMessage = "Object failure : " . $this->clsObj->getId() . " - \n" . $ex->getMessage() . "\n" . $ex->getTraceAsString();
                            $info = [
                                'msg' => $errorMessage,
                                'type' => 'error',
                                'component' => $this->logComponent . date("Y-m-d")
                            ];
                            $this->failedRecordsCount++;
                            $this->records[$sn][1] = $this->clsObj->getId();
                            $this->records[$sn][2] = "Failure";
                            $this->records[$sn][3] = $errorMessage;
                            $this->clsObj->rollBack();
                            $this->log($info);
                        }
                    }
                } else {
                    // Set object parent-id
                    $this->setObjectParentId($dataSet, $class);
                    $this->clsObj->setPublished(false);
                    // Set type either 'variant' or 'object'
                    $this->setObjectType($dataSet);
                    $this->clsObj->setKey($key);
                    try {
                        $this->clsObj->save();
                        $this->createdRecordsCount++;
                        $this->records[$sn][1] = $this->clsObj->getId();

                        if (!empty($this->errorMessage)) {
                            $this->records[$sn][2] = "Relation Data Failed.";
                            $this->records[$sn][3] = "Object updated successfully with missing relation data for " . implode(",", $this->errorMessage);
                        } else {
                            $this->records[$sn][2] = "Success";
                            $this->records[$sn][3] = "Object created successfully.";
                        }
                    } catch (\Exception $ex) {
                        $errorMessage = "Object failure : " . $this->clsObj->getId() . " - \n" . $ex->getMessage() . "\n" . $ex->getTraceAsString();
                        $info = [
                            'msg' => $errorMessage,
                            'type' => 'error',
                            'component' => $this->logComponent . date("Y-m-d")
                        ];
                        $this->failedRecordsCount++;
                        $this->records[$sn][1] = $this->clsObj->getId();
                        $this->records[$sn][2] = "Failure";
                        $this->records[$sn][3] = $errorMessage;
                        $this->log($info);
                    }
                }
                $insert['job_id'] = $this->jobId;
                $insert['source_id'] = $dataSet[$this->keyName];
                $insert['dest_id'] = $this->clsObj->o_id;
                $this->queuedJobs('queue', $insert, $this->connName);
            } // End of foreach
            return array(
                'success' => true,
            );
        } catch (\Exception $excp) {
            $errorMessage = " , Function name:" . __FUNCTION__ . " and className:" . __CLASS__;
            $info = [
                'msg' => $errorMessage,
                'type' => 'error',
                'component' => $this->logComponent . date("Y-m-d")
            ];
            $this->log($info);
            return array(
                'success' => false,
                'msg' => $excp->getMessage()
            );
        }
    }


    /**
     * @param $dataset
     * @param $class
     * @param $classdef
     * @return mixed
     */
    protected function checkObjectExist($dataset, $class, $classdef)
    {
        if ($classdef[$this->className][$this->mapper[$this->keyName]['target']]['type'] == 'localizedfields') {
            $checkobj = $class::getByLocalizedfields($this->mapper[$this->keyName]['target'], $dataset[$this->keyName], null, 1);
        } else {
            if (isset($dataset['parentid']) && $dataset['parentid'] && isset($dataset['datatype']) && $dataset['datatype'] == 'variant') {
                $list = $class . "\\Listing";
                $variant = new $list();
                $variant->setObjectTypes([
                    'variant'
                ]);
                $variant->setCondition($this->mapper[$this->keyName]['target'] . " = ?", $dataset[$this->keyName]);
                $result = $variant->load();
                if (count($result)) {

                    foreach ($result as $obj) {
                        $checkobj = $obj;
                        break;
                    }
                }
            } else {
                $checkCond = "getBy" . ucfirst($this->mapper[$this->keyName]['target']);
                $checkobj = $class::{$checkCond}($dataset[$this->keyName], ['unpublished' => true, 'limit' => 1]);
            }
        }
      
        return $checkobj;
    }

    /**
     * set object parent-id
     *
     * @param array $dataset
     * @param object $class
     */
    protected function setObjectParentId($dataset, $class)
    {
        if (isset($dataset['parentid']) && $dataset['parentid']) {
            $checkCond = "getBy" . ucfirst($this->mapper[$this->keyName]['target']);
            $ob = $class::{$checkCond}($dataset['parentid'], true);
            //$object1 = \Pimcore\Model\DataObject\Product::getBySubclass_id($dataset['parentid']);
            if ($ob) {
                $this->clsObj->setParentId($ob->getId());
            } else {
                $this->clsObj->setParentId($this->parentId);
            }
        } else {
            $this->clsObj->setParentId($this->parentId);
        }
    }

    /**
     * set object type
     *
     * @param array $dataset
     */
    protected function setObjectType($dataset)
    {
        if (isset($dataset['datatype']) && $dataset['datatype']) {
            if (in_array($dataset['datatype'], array(
                'variant',
                'object'
            ))) {
                $this->clsObj->setType($dataset['datatype']);
            } else {
                $this->clsObj->setType("object");
            }
        } else {
            $this->clsObj->setType("object");
        }
    }

    /**
     * set data relationship
     *
     * @param $dataset
     * @param $classdef
     * @return array|bool
     * @throws \Exception
     */
    protected function setDataRelationship($dataset, $classdef)
    {
        $brickAttr = [];
        $fcAttr = [];
        foreach ($this->mapper as $source => $target) {
            $dataset[$source] = trim($dataset[$source]);
            if ($target['col_type'] == "") {
                $method = "set" . ucfirst($target['target']);
                $getMethod = "get" . ucfirst($target['target']);
                // condition to check field types from class attribute
                if (isset($classdef[$this->className][$target['target']]['type']) && $classdef[$this->className][$target['target']]['type'] == 'checkbox') {
                    $value = $this->handleCheckBoxField($dataset[$source]);
                    $this->clsObj->{$method}($value);
                } elseif (isset($classdef[$this->className][$target['target']]['type']) && $classdef[$this->className][$target['target']]['type'] == 'select') {

                    $value = $this->handleSelectField($this->clsObj, $getMethod, $classdef[$this->className][$target['target']]['options'], $dataset[$source]);
                    $this->clsObj->{$method}($value, $this->languageKey);
                } elseif (isset($classdef[$this->className][$target['target']]['type']) && $classdef[$this->className][$target['target']]['type'] == 'quantityValue') {

                    $value = $this->handleQuantityValueField($dataset[$source]);
                    $this->clsObj->{$method}($value, $this->languageKey);
                } elseif (isset($classdef[$this->className][$target['target']]['type']) && $classdef[$this->className][$target['target']]['type'] == 'multiselect') {

                    $value = $this->handleMultiselectField($this->clsObj, $getMethod, $classdef[$this->className][$target['target']]['options'], $dataset[$source]);
                    $this->clsObj->{$method}($value, $this->languageKey);
                } elseif (isset($classdef[$this->className][$target['target']]['type']) && $classdef[$this->className][$target['target']]['type'] == 'objects') {

                    $value = $this->handleObjectField($classdef[$this->className][$target['target']]['classes'], $dataset[$source], $source);
                    if (!is_array($value) || count($value) == 0) {
                        $this->errorMessage[] = $target['target'];
                    }
                    $this->clsObj->{$method}($value, $this->languageKey);
                } elseif (isset($classdef[$this->className][$target['target']]) && $classdef[$this->className][$target['target']]['type'] == 'localizedfields') {

                    $this->setLocalizedDataRelationship($dataset, $classdef, $target['target'], $source);
                } elseif (isset($classdef[$this->className][$target['target']]['type']) && ($classdef[$this->className][$target['target']]['type'] == 'date' || $classdef[$this->className][$target['target']]['type'] == 'datetime')) {

                    $value = $this->handleDateField($dataset[$source]);
                    $this->clsObj->{$method}($value, $this->languageKey);
                } elseif (isset($classdef[$this->className][$target['target']]['type']) && $classdef[$this->className][$target['target']]['type'] == 'image') {

                    $value = $this->handleImageField($dataset[$source]);
                    $this->clsObj->{$method}($value, $this->languageKey);
                } elseif (isset($classdef[$this->className][$target['target']]['type']) && $classdef[$this->className][$target['target']]['type'] == 'externalImage') {

                    $value = $this->handleExtImageField($dataset[$source]);
                    $this->clsObj->{$method}($value, $this->languageKey);
                } elseif (isset($classdef[$this->className][$target['target']]['type']) && $classdef[$this->className][$target['target']]['type'] == 'dynamicDropdown') {

                    $value = $this->handleDyanmicDropDownField($dataset[$source], $target['target'], $classdef[$this->className][$target['target']]);
                    $this->clsObj->{$method}($value, $this->languageKey);
                } elseif (isset($classdef[$this->className][$target['target']]['type']) && $classdef[$this->className][$target['target']]['type'] == 'video') {
                    $value = $this->handleVideoField($dataset[$source]);
                    $this->clsObj->{$method}($value, $this->languageKey);
                } elseif (isset($classdef[$this->className][$target['target']]['type']) && $classdef[$this->className][$target['target']]['type'] == 'link') {
                    $value = $this->handleLinkField($dataset[$source]);
                    $this->clsObj->{$method}($value, $this->languageKey);
                } elseif (isset($classdef[$this->className][$target['target']]['type']) && $classdef[$this->className][$target['target']]['type'] == 'objectsMetadata') {
                    $value = $this->handleObjectMetadataField($classdef[$this->className][$target['target']]['classid'], $dataset[$source], $source);
                    $this->clsObj->{$method}($value, $this->languageKey);
                } elseif ($classdef[$this->className][$target['target']]['type'] != 'checkbox' && $classdef[$this->className][$target['target']]['type'] != 'select') {

                    $this->clsObj->{$method}($dataset[$source], $this->languageKey);
                }
            } else {
                // This is to handle object-brick and field-collection types
                if ($target['col_type'] == 'objectbrick') {
                    $target['value'] = $dataset[$source];
                    $brickAttr[] = $target;
                } else if ($target['col_type'] == 'fieldcollection') {
                    // case for the field-collection
                    $target['value'] = $dataset[$source];
                    $fcAttr[] = $target;
                }
            }
        }
        if (count($brickAttr) > 0) {
            $res = $this->setObjectBrickDataType($brickAttr);
        }

        if (count($fcAttr) > 0) {
            $res = $this->setFieldCollectionDataType($fcAttr);
        }
        return $res;
    }

    /**
     * @param $brickAttrData
     * @return array
     */
    protected function setObjectBrickDataType($brickAttrData)
    {
        try {
            $pimStrucData = $this->getStructuralDataset($brickAttrData, "objectbrick");
            foreach ($pimStrucData as $brickName => $target) {
                $brickClass = "\\Pimcore\\Model\\Object\\Objectbrick\\Data\\" . ucfirst($brickName);
                $brick = new $brickClass($this->clsObj);
                $setterBrick = "set" . ucfirst($brickName);
                foreach ($target as $kValue => $params) {
                    $method = "set" . ucfirst($params['attrName']);
                    $getMethod = "get" . ucfirst($params['attrName']);
                    $clsAttr = $params['selected'];
                    if ($params['type'] == 'objects' /*&& !empty($params['ref_class'])*/ ) {
                        $value = $this->handleObjectField($params['ref_class'], $params['value'], $params['name']);
                        if (!is_array($value) || count($value) == 0) {
                            $this->errMsg[] = $params['attrName'];
                        }
                        $brick->{$method}($value, $this->languageKey);
                    } else if ($params['type'] == 'objectsMetadata' /* && !empty($params['ref_class'])*/ ) {
                        $value = $this->handleObjectMetadataField($params['ref_class'], $params['value'], $params['name']);
                        $brick->{$method}($value, $this->languageKey);
                    } elseif ($params['type'] == 'quantityValue') {
                        $value = $this->handleQuantityValueField($params['value']);
                        $brick->{$method}($value, $this->languageKey);
                    } elseif ($params['type'] == 'date' || $params['type'] == 'datetime') {
                        $value = $this->handleDateField($params['value']);
                        $brick->{$method}($value, $this->languageKey);
                    } elseif ($params['type'] == 'image') {
                        $value = $this->handleImageField($params['value']);
                        $brick->{$method}($value, $this->languageKey);
                    } elseif ($params['type'] == 'externalImage') {
                        $value = $this->handleExtImageField($params['value']);
                        $brick->{$method}($value, $this->languageKey);
                    } elseif ($params['type'] == 'dynamicDropdown') {
                        $arr = array("type" => $params['type'], "brick" => $brickName, "col_type" => "objectbrick");
                        $value = $this->handleDyanmicDropDownField($params['value'], $params['attrName'], $arr, $params['selected']);
                        $brick->{$method}($value, $this->languageKey);
                    } elseif ($params['type'] == 'video') {
                        $value = $this->handleVideoField($params['value']);
                        $brick->{$method}($value, $this->languageKey);
                    } elseif ($params['type'] == 'multiselect') {
                        $value = $this->handleMultiselectField($brick, $getMethod, array(), $params['value']);                                               
                        $brick->{$method}($value);                       
                    } elseif ($params['type'] == 'checkbox') {
                        $value = $this->handleCheckboxField($params['value']);
                        $brick->{$method}($value, $this->languageKey);
                    } elseif ($params['type'] == 'link') {
                        $value = $this->handleLinkField($params['value']);
                        $brick->{$method}($value, $this->languageKey);
                    } else {
                        $brick->{$method}($params['value'], $this->languageKey);
                    }
                }
                $clsAttr = "get" . ucfirst($clsAttr);
                $this->clsObj->$clsAttr()->$setterBrick($brick);                
            }
            return array(
                    'success' => true,
                    'msg' => 'objectbrick'
                );
        } catch (\Exception $excp) {
            $info = [
                'msg' => "Object Brick Data failure : " . $excp->getMessage() . "\n" . $excp->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return array(
                'success' => false,
                'msg' => $excp->getMessage()
            );
        }

    }

    /**
     * set the field-collection
     *
     * @param $fcAttr
     * @return array|bool
     */
    protected function setFieldCollectionDataType($fcAttr)
    {
        try {
            $fcArr = array();
            $pimStrucData = $this->getStructuralDataset($fcAttr, 'fieldcollection');
            foreach ($pimStrucData as $attr => $colGroups) {
                $fcs = new Fieldcollection();
                $getAttr = "get" . ucfirst($attr);
                if (method_exists($this->clsObj, $getAttr) && !empty($this->clsObj->$getAttr())) {
                    $fcData = $this->clsObj->$getAttr()->getItems();
                    foreach ($fcData as $fkey => $fcolvalue) {
                        if (method_exists($fcolvalue, 'getLocalizedFields')) {
                            $fcArr[$fcolvalue->type][] = $fcolvalue->getLocalizedFields()->getItems();
                        }
                    }
                }
                foreach ($colGroups as $fcName => $groups) {
                    $fcClass = "\\Pimcore\\Model\\Object\\Fieldcollection\\Data\\" . ucfirst($fcName);
                    $c = 0;
                    foreach ($groups as $no => $target) {
                        $fc = new $fcClass();
                        foreach ($target as $kValue => $params) {
                            $sel = "get" . ucfirst($params['selected']);
                            $method = "set" . ucfirst($params['attrName']);
                            $arr = array("type" => $params['type'], "fcName" => $fcName, "col_type" => "fieldcollection", "data" => "");
                            if ($params["dataType"] != '' && $params["dataType"] == 'localizedfields') {
                                $arr['data'] = 'localized';
                                if (count($fcArr[$fcName]) && isset($fcArr[$fcName][$c])) {
                                    foreach ($fcArr[$fcName][$c] as $flagKey => $flagValue) {
                                        if ($flagKey == $this->languageKey) {

                                            if ($params['type'] == 'objects' && !empty($params['ref_class'])) {
                                                $value = $this->handleObjectField($params['ref_class'], $params['value'], $params['name']);
                                                if (!is_array($value) || count($value) == 0) {
                                                    $this->errorMessage[] = $params['attrName'];
                                                }
                                                $fc->{$method}($value, $this->languageKey);
                                            } else if ($params['type'] == 'objectsMetadata' && !empty($params['ref_class'])) {
                                                $value = $this->handleObjectMetadataField($params['ref_class'], $params['value'], $params['name']);
                                                $fc->{$method}($value, $this->languageKey);
                                            } elseif ($params['type'] == 'quantityValue') {
                                                $value = $this->handleQuantityValueField($params['value']);
                                                $fc->{$method}($value, $this->languageKey);
                                            } elseif ($params['type'] == 'date' || $params['type'] == 'datetime') {
                                                $value = $this->handleDateField($params['value']);
                                                $fc->{$method}($value, $this->languageKey);
                                            } elseif ($params['type'] == 'image') {
                                                $value = $this->handleImageField($params['value']);
                                                $fc->{$method}($value, $this->languageKey);
                                            } elseif ($params['type'] == 'externalImage') {
                                                $value = $this->handleExtImageField($params['value']);
                                                $fc->{$method}($value, $this->languageKey);
                                            } elseif ($params['type'] == 'video') {
                                                $value = $this->handleVideoField($params['value']);
                                                $fc->{$method}($value, $this->languageKey);
                                            } elseif ($params['type'] == 'link') {
                                                $value = $this->handleLinkField($params['value']);
                                                $fc->{$method}($value, $this->languageKey);
                                            } elseif ($params['type'] == 'dynamicDropdown') {
                                                $value = $this->handleDyanmicDropDownField($params['value'], $params['attrName'], $arr, $params['selected']);
                                                $fc->{$method}($value, $this->languageKey);
                                            } else {
                                                $fc->{$method}($params['value'], $this->languageKey);
                                            }
                                        } else {
                                            $fc->{$method}($fcArr[$fcName][$c][$flagKey][$params['attrName']], $flagKey);
                                        }
                                    }
                                } else {//this is case for localised field if the method not exist
                                    if ($params['type'] == 'objects' && !empty($params['ref_class'])) {
                                        $value2 = $this->handleObjectField($params['ref_class'], $params['value'], $params['name']);
                                        if (!is_array($value2) || count($value2) == 0) {
                                            $this->errorMessage[] = $params['attrName'];
                                        }
                                        $fc->{$method}($value2, $this->languageKey);
                                    } else if ($params['type'] == 'objectsMetadata' && !empty($params['ref_class'])) {
                                        $value2 = $this->handleObjectMetadataField($params['ref_class'], $params['value'], $params['name']);
                                        $fc->{$method}($value2, $this->languageKey);
                                    } elseif ($params['type'] == 'quantityValue') {
                                        $value2 = $this->handleQuantityValueField($params['value']);
                                        $fc->{$method}($value2, $this->languageKey);
                                    } elseif ($params['type'] == 'date' || $params['type'] == 'datetime') {
                                        $value2 = $this->handleDateField($params['value']);
                                        $fc->{$method}($value2, $this->languageKey);
                                    } elseif ($params['type'] == 'image') {
                                        $value2 = $this->handleImageField($params['value']);
                                        $fc->{$method}($value2, $this->languageKey);
                                    } elseif ($params['type'] == 'externalImage') {
                                        $value2 = $this->handleExtImageField($params['value']);
                                        $fc->{$method}($value2, $this->languageKey);
                                    } elseif ($params['type'] == 'video') {
                                        $value2 = $this->handleVideoField($params['value']);
                                        $fc->{$method}($value2, $this->languageKey);
                                    } elseif ($params['type'] == 'link') {
                                        $value2 = $this->handleLinkField($params['value']);
                                        $fc->{$method}($value2, $this->languageKey);
                                    } elseif ($params['type'] == 'dynamicDropdown') {
                                        $value2 = $this->handleDyanmicDropDownField($params['value'], $params['attrName'], $arr, $params['selected']);
                                        $fc->{$method}($value2, $this->languageKey);
                                    } else {
                                        $fc->{$method}($params['value'], $this->languageKey);
                                    }
                                }
                            } else {//this is case for non-localised field
                                if ($params['type'] == 'objects' && !empty($params['ref_class'])) {
                                    $value3 = $this->handleObjectField($params['ref_class'], $params['value'], $params['name']);
                                    if (!is_array($value3) || count($value3) == 0) {
                                        $this->errorMessage[] = $params['attrName'];
                                    }
                                    $fc->{$method}($value3);
                                } else if ($params['type'] == 'objectsMetadata' && !empty($params['ref_class'])) {
                                    $value3 = $this->handleObjectMetadataField($params['ref_class'], $params['value'], $params['name']);
                                    $fc->{$method}($value3);
                                } elseif ($params['type'] == 'quantityValue') {
                                    $value3 = $this->handleQuantityValueField($params['value']);
                                    $fc->{$method}($value3);
                                } elseif ($params['type'] == 'date' || $params['type'] == 'datetime') {
                                    $value3 = $this->handleDateField($params['value']);
                                    $fc->{$method}($value3);
                                } elseif ($params['type'] == 'image') {
                                    $value3 = $this->handleImageField($params['value']);
                                    $fc->{$method}($value3);
                                } elseif ($params['type'] == 'externalImage') {
                                    $value3 = $this->handleExtImageField($params['value']);
                                    $fc->{$method}($value3);
                                } elseif ($params['type'] == 'video') {
                                    $value3 = $this->handleVideoField($params['value']);
                                    $fc->{$method}($value3);
                                } elseif ($params['type'] == 'link') {
                                    $value3 = $this->handleLinkField($params['value']);
                                    $fc->{$method}($value3);
                                } elseif ($params['type'] == 'dynamicDropdown') {
                                    $value3 = $this->handleDyanmicDropDownField($params['value'], $params['attrName'], $arr, $params['selected']);
                                    $fc->{$method}($value3);
                                } else {
                                    $fc->{$method}($params['value']);
                                }
                            }
                        }
                        $c++;
                        $fcs->add($fc);
                    }
                    $clsAttr = "set" . ucfirst($attr);
                    $this->clsObj->$clsAttr($fcs);
                }
            }

            return array(
                'success' => true,
                'msg' => 'field collection'
            );
        } catch (\Exception $excp) {
            $info = [
                'msg' => "Field Collection Data failure : " . $excp->getMessage() . "\n" . $excp->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return array(
                'success' => false,
                'msg' => $excp->getMessage()
            );
        }
    }


    /**
     * @param $data
     * @param null $type
     * @return array
     * @throws \Exception
     */
    protected function getStructuralDataset($data, $type = null)
    {
        $pimStrucData = [];
        foreach ($data as $sourceValue => $target) {
            $targetHead = explode(".", $target['target']);
            $clsName = $targetHead[0];
            $clsAttr = $targetHead[1];
            $selectedClsAttr = explode(",", $target['selected_attr']);
            $refClasses = "";
            if ($target['group_no'] == "") {
                $target['group_no'] = 'blank';
            }
            $dataType = "";
            if ($type == "objectbrick") {                
                $cls = "\\Pimcore\\Model\\DataObject\\Objectbrick\\Data\\" . ucfirst($clsName);
                $obj = new $cls($this->clsObj);
                $clsDefinition = $obj->getDefinition()->getClassDefinitions();
                
                foreach ($clsDefinition as $index => $def) {
                    if (in_array($def['fieldname'], $selectedClsAttr)) {
                        $selected = $def['fieldname'];
                        break;
                    }
                }
                $bdef = $obj->getDefinition()->getFieldDefinitions()[$clsAttr];
                
                if ($bdef->fieldtype == "objects") {
                    $refClasses = $bdef->getClasses();
                } else if ($bdef->fieldtype == "objectsMetadata") {
                    $refClasses = $bdef->getAllowedClassId();
                }
                $pimStrucData[$clsName][] = [
                    'attrName' => $clsAttr,
                    'selected' => $selected,
                    'group_no' => $target['group_no'],
                    'ref_class' => $refClasses,
                    'name' => $target['name'],
                    'type' => $obj->getDefinition()->getFieldDefinitions()[$clsAttr]->getFieldtype(),
                    'value' => $target['value']
                ];
            } elseif ($type == "fieldcollection") {
                $fieldCollection = \Pimcore\Model\Object\Fieldcollection\Definition::getByKey($clsName);
                if ($targetHead[1] == 'localizedfields') {
                    // Localized case
                    $dataType = $targetHead[1];
                    $clsAttr = $targetHead[2];
                    $fdef = $fieldCollection->getFieldDefinitions()['localizedfields']->getFieldDefinitions();
                    foreach ($fdef as $key => $fDefination) {
                        if ($clsAttr == $fDefination->getName()) {
                            $elementType = $fDefination->getFieldtype();
                            if ($elementType == "objects") {
                                $refClasses = $fDefination->getClasses();
                            } else if ($elementType == "objectsMetadata") {
                                $refClasses = $fDefination->getAllowedClassId();
                            }
                            break;
                        }
                    }
                } else {
                    // Non-Localized case
                    $fdef = $fieldCollection->getFieldDefinitions()[$clsAttr];
                    $elementType = $fdef->fieldtype;
                    if ($elementType == "objects") {
                        $refClasses = $fdef->getClasses();
                    } else if ($elementType == "objectsMetadata") {
                        $refClasses = $fdef->getAllowedClassId();
                    }
                }
                $obj = ClassDefinition::getById($this->classId);
                foreach ($selectedClsAttr as $index => $attr) {
                    if (in_array($clsName, $obj->getFieldDefinitions()[$attr]->getAllowedTypes())) {
                        $selected = $attr;
                        break;
                    }
                }
                $pimStrucData[$selected][$clsName][$target['group_no']][] = [
                    'attrName' => $clsAttr,
                    'selected' => $selected,
                    'group_no' => $target['group_no'],
                    'ref_class' => $refClasses,
                    'name' => $target['name'],
                    'type' => $elementType,
                    'dataType' => $dataType,
                    'value' => $target['value']
                ];
            }
        }
        return $pimStrucData;
    }


    /**
     * set localized data type
     * @param $dataset
     * @param $classdef
     * @param $target
     * @param $source
     * @return array
     */
    protected function setLocalizedDataRelationship($dataset, $classdef, $target, $source)
    {
        try {
            $method = "set" . ucfirst($target);
            $getMethod = "get" . ucfirst($target);
            if (isset($classdef[$this->className][$target]['fieldtype']) && $classdef[$this->className][$target]['fieldtype'] == 'checkbox') {

                $value = $this->handleCheckBoxField($dataset[$source]);
                $this->clsObj->{$method}($value, $this->languageKey);
            } elseif (isset($classdef[$this->className][$target]['fieldtype']) && $classdef[$this->className][$target]['fieldtype'] == 'select') {

                $value = $this->handleSelectField($this->clsObj, $getMethod, $classdef[$this->className][$target]['options'], $dataset[$source]);
                $this->clsObj->{$method}($value, $this->languageKey);
            } elseif (isset($classdef[$this->className][$target]['fieldtype']) && $classdef[$this->className][$target]['fieldtype'] == 'multiselect') {

                $value = $this->handleMultiselectField($this->clsObj, $getMethod, $classdef[$this->className][$target]['options'], $dataset[$source]);
                $this->clsObj->{$method}($value, $this->languageKey);
            } elseif (isset($classdef[$this->className][$target]['fieldtype']) && $classdef[$this->className][$target]['fieldtype'] == 'objects') {
                $value = $this->handleObjectField($classdef[$this->className][$target]['classes'], $dataset[$source], $source);
                if (!is_array($value) || count($value) == 0) {
                    $this->errorMessage[] = $target;
                }
                $this->clsObj->{$method}($value, $this->languageKey);
            } elseif (isset($classdef[$this->className][$target]['fieldtype']) && $classdef[$this->className][$target]['fieldtype'] == 'quantityValue') {

                $value = $this->handleQuantityValueField($dataset[$source]);
                $this->clsObj->{$method}($value, $this->languageKey);
            } elseif (isset($classdef[$this->className][$target]['fieldtype']) && ($classdef[$this->className][$target]['fieldtype'] == 'date' || $classdef[$this->className][$target]['fieldtype'] == 'datetime')) {

                $value = $this->handleDateField($dataset[$source]);
                $this->clsObj->{$method}($value, $this->languageKey);
            } elseif (isset($classdef[$this->className][$target]['fieldtype']) && $classdef[$this->className][$target]['fieldtype'] == 'image') {

                $value = $this->handleImageField($dataset[$source]);
                $this->clsObj->{$method}($value, $this->languageKey);
            } elseif (isset($classdef[$this->className][$target]['fieldtype']) && $classdef[$this->className][$target]['fieldtype'] == 'externalImage') {

                $value = $this->handleExtImageField($dataset[$source]);
                $this->clsObj->{$method}($value, $this->languageKey);
            } elseif (isset($classdef[$this->className][$target]['fieldtype']) && $classdef[$this->className][$target]['fieldtype'] == 'video') {
                $value = $this->handleVideoField($dataset[$source]);
                $this->clsObj->{$method}($value, $this->languageKey);
            } elseif (isset($classdef[$this->className][$target]['fieldtype']) && $classdef[$this->className][$target]['fieldtype'] == 'link') {
                $value = $this->handleLinkField($dataset[$source]);
                $this->clsObj->{$method}($value, $this->languageKey);
            } elseif (isset($classdef[$this->className][$target]['fieldtype']) && $classdef[$this->className][$target]['fieldtype'] == 'dynamicDropdown') {

                $value = $this->handleDyanmicDropDownField($dataset[$source], $target, $classdef[$this->className][$target]);
                $this->clsObj->{$method}($value, $this->languageKey);
            } elseif (isset($classdef[$this->className][$target]['fieldtype']) && $classdef[$this->className][$target]['fieldtype'] == 'objectsMetadata') {
                $value = $this->handleObjectMetadataField($classdef[$this->className][$target]['classid'], $dataset[$source], $source);
                $this->clsObj->{$method}($value, $this->languageKey);
            } else {
                $this->clsObj->{$method}($dataset[$source], $this->languageKey);
            }
        } catch (\Exception $excp) {
            $info = [
                'msg' => "Error in getting fieldtype data : " . $excp->getMessage() . "\n" . $excp->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return array(
                'success' => false,
                'msg' => $excp->getMessage()
            );
        }
    }

    /**
     * Set data mapper
     *
     * @param $data
     * @return array
     */
    protected function setDataMapper($data)
    {
        try {
            $header = $data;
            foreach ($header as $col => $value) {
                foreach ($this->mapping as $index => $map) {
                    if ($map['name'] == $col) {
                        if ($map['target_col'] != "") {
                            $this->mapper[$col]['target'] = $map['target_col'];
                            $this->mapper[$col]['ref_class'] = $map['ref_class'];
                            $this->mapper[$col]['ref_field'] = $map['ref_field'];
                            $this->mapper[$col]['col_type'] = $map['col_type'];
                            $this->mapper[$col]['selected_attr'] = $map['selected_attr'];
                            $this->mapper[$col]['group_no'] = $map['group_no'];
                            $this->mapper[$col]['name'] = $map['name'];
                            break;
                        }
                    }
                }
            }
            return array(
                'success' => true,
            );
        } catch (\Exception $excp) {
            $info = [
                'msg' => "Object failure : " . $excp->getMessage() . "\n" . $excp->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return array(
                'success' => false,
                'msg' => $excp->getMessage()
            );
        }
    }

    /**
     * Get the class defination
     *
     * @param $className
     * @return array
     */
    public function getClassDefinition($className)
    {
        try {
            // get class data
            $class = ClassDefinition::getByName($className);
            $fields = $class->getFieldDefinitions();
            foreach ($fields as $key => $field) {
                $config = null;
                $title = $field->getName();
                $type = $field->getFieldType();

                if (method_exists($field, "getTitle")) {
                    if ($field->getTitle()) {
                        $title = $field->getTitle();
                    }
                }

                if (in_array($type, $this->supportedFieldTypes)) {
                    if ($type == "localizedfields") {
                        $localField = $field->getFieldDefinitions();
                        foreach ($localField as $fieldName => $elDetail) {
                            $title = $elDetail->getTitle();
                            if ($fieldName != 'localizedfields') {
                                $availableField[$className][$elDetail->getName()]['type'] = $type;
                                $availableField[$className][$elDetail->getName()]['fieldtype'] = $elDetail->getFieldType();
                                if ($elDetail->getFieldType() == 'select' || $elDetail->getFieldType() == 'multiselect') {
                                    $localizedoptions = $elDetail->getOptions();
                                    if (count($localizedoptions)) {
                                        foreach ($localizedoptions as $loption) {
                                            $availableField[$className][$elDetail->getName()]['options'][] = $loption['value'];
                                        }
                                    } else {
                                        $availableField[$className][$elDetail->getName()]['options'][] = array();
                                    }
                                } else if ($elDetail->getFieldType() == 'objects') {
                                    $availableField[$className][$elDetail->getName()]['classes'] = $elDetail->getClasses();
                                } else if ($elDetail->getFieldType() == 'objectsMetadata') {
                                    $availableField[$className][$elDetail->getName()]['classid'] = $elDetail->getAllowedClassId();
                                }
                            }
                        }
                    }

                    if ($field->getName() != 'localizedfields') {
                        $availableField[$className][$field->getName()]['type'] = $type;
                        if ($type == 'select' || $type == 'multiselect') {
                            $options = $field->getOptions();

                            if (count($field->getOptions())) {
                                foreach ($field->getOptions() as $option) {
                                    $availableField[$className][$field->getName()]['options'][] = $option['value'];
                                }
                            } else {
                                $availableField[$className][$field->getName()]['options'][] = array();
                            }
                        } else
                            if ($type == 'objects') {

                                $availableField[$className][$field->getName()]['classes'] = $field->getClasses();
                            } else if ($type == 'objectsMetadata') {
                                $availableField[$className][$field->getName()]['classid'] = $field->getAllowedClassId();
                                $availableField[$className][$field->getName()]['columns'] = $field->getColumns();
                            }
                    }
                }
            }
            return $availableField;
        } catch (\Exception $excp) {
            $info = [
                'msg' => "Object failure : " . $excp->getMessage() . "\n" . $excp->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return array(
                'success' => false,
                'msg' => $excp->getMessage()
            );
        }
    }

    /**
     * Handles checkbox field
     *
     * @param mixed $value
     * @return boolean
     */
    protected function handleCheckBoxField($value)
    {
        try {

            $value = strtolower($value);
            if ($value && ($value == 'true' || $value == '1' || $value == 'yes')) {
                return true;
            } else if ($value && ($value == 'false' || $value == '0' || $value == 'no')) {
                return false;
            } else {
                return false;
            }
        } catch (\Exception $ex) {
            return null;
        }
    }

    /**
     * Handles select field
     *
     * @param object $obj
     * @param string $method
     * @param array $options
     * @param string $value
     * @return string
     */
    protected function handleSelectField($obj, $method, $options, $value)
    {
        try {
            if (count($options)) {

                if (in_array($value, $options)) {
                    return $value;
                } else {
                    return $selectValue = $obj->{$method}();
                }
            } else {
                return $selectValue = $obj->{$method}();
            }
        } catch (\Exception $ex) {
            return null;
        }
    }

    /**
     * Handles Multiselect Field
     *
     * @param object $obj
     * @param string $method
     * @param array $options
     * @param string $value
     * @return array
     */
    protected function handleMultiselectField($obj, $method, $options, $value)
    {
        try {

            //if (count($options)) {

            if (!empty($value)) {
                return explode("$this->separator", $value);
            } else {
                return $multiselectValue = $obj->{$method}();
            }
            /* } else {
              return $multiselectValue = $obj->{$method}();
              } */
        } catch (\Exception $ex) {
            return array();
        }
    }

    /**
     * Handles the Quantity Value
     *
     * @param object $obj
     * @param string $method
     * @param string $type
     * @param int $value
     * @return object
     */
    protected function handleQuantityValueField($value)
    {
        try {
            $abbervationArray = [];
            if (is_object($value)) {
                return $value;
            } else {
                if (!empty($value)) {
                    $quantityListObj = new \Pimcore\Model\Object\QuantityValue\Unit\Listing();
                    $quantityListObj = $quantityListObj->getUnits();
                    if (is_array($quantityListObj)) {
                        foreach ($quantityListObj as $key => $abbValue) {
                            $abbervationArray[strtolower($abbValue->getAbbreviation())] = $abbValue->getAbbreviation();
                        }
                    }
                    $value = explode($this->separator, $value);
                    if (!empty($abbervationArray)) {
                        foreach ($abbervationArray as $aKey => $newValue) {
                            if (strtolower($value[1]) == $aKey) {
                                $unitObj = \Pimcore\Model\Object\QuantityValue\Unit::getByAbbreviation($newValue);
                                if (!empty($unitObj)) {
                                    $unit = new \Pimcore\Model\Object\Data\QuantityValue();
                                    $unit->setValue($value[0]);
                                    $unit->setUnitId($unitObj->getId());
                                    return $unit;
                                }
                                break;
                            }
                        }
                    }
                } else {
                    return [];
                }
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Handle Date Type Field
     *
     * @param $value
     * @return bool|\DateTime|string
     * @throws \Exception
     */
    public function handleDateField($value)
    {
        try {
            $date = '';
            $date = \DateTime::createFromFormat('m/d/y', $value);
            if ($date == false) {
                $date = \DateTime::createFromFormat('m/d/Y H:i', $value);
            }
            if ($date == false) {
                $date = \DateTime::createFromFormat('m/d/Y H:i:s', $value);
            }
            return $date;

        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Handle Image Type Field
     *
     * @param $value
     * @return bool|Asset|Asset\Archive|Asset\Audio|Asset\Document|Asset\Folder|Asset\Image|Asset\Text|Asset\Unknown|Asset\Video|string
     */
    public function handleImageField($value)
    {
        try {
            if (!empty($value)) {
                $ImgObj = \Pimcore\Model\Asset\Image::getByPath($value);
                return $ImgObj;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * this will handle the External Image Field
     * @param url $value
     * @return \Pimcore\Model\Object\Data\ExternalImage
     */
    public function handleExtImageField($value)
    {

        try {
            if (!empty($value)) {

                $extImg = new \Pimcore\Model\Object\Data\ExternalImage();

                $extImg->setUrl($value);
                return $extImg;
            } else {
                return [];
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * handles video type field
     * @param url $value
     * @return \Pimcore\Model\Object\Data\Video
     */
    public function handleVideoField($value)
    {

        try {
            $rx = '~
            ^(?:https?://)?                           # Optional protocol
             (?:www[.])?                              # Optional sub-domain
             (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
             ([^&]{11})                               # Video id of 11 characters as capture group 1
              ~x';
            if (!empty($value)) {
                $data = preg_match($rx, $value, $matches);

                $video = new \Pimcore\Model\Object\Data\Video();

                $video->setType("youtube");
                $video->setData($matches[1]);
                return $video;
            } else {
                return [];
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * handles video type field
     * @param url $value
     * @return \Pimcore\Model\Object\ClassDefinition\Data\DynamicDropdown
     */
    public function handleDyanmicDropDownField($value, $target, $def, $classAttr = null)
    {
        try {
            if (!empty($value)) {
                $classDef = '';
                $classData = '';
                $dValue = '';
                $class = ClassDefinition::getByName($this->className);
                $classDef = $class->getFieldDefinitions();
                if (!empty($classAttr)) {
                    $classData = $classDef[$classAttr]->getAllowedTypes();
                    if (in_array($def['brick'], $classData) && $def['col_type'] == "objectbrick") {
                        $brickDef = \Object\Objectbrick\Definition::getByKey($def['brick']);
                        $classDef = $brickDef->getFieldDefinitions();
                    } elseif (in_array($def['fcName'], $classData) && $def['col_type'] == "fieldcollection") {
                        $fcDef = \Object\Fieldcollection\Definition::getByKey($def['fcName']);
                        $classDef = $fcDef->getFieldDefinitions();
                        if (!empty($def['data'])) {
                            $classDef = $classDef['localizedfields']->getFieldDefinitions();
                        }
                    }
                }
                if (empty($def['data']) && $def['type'] == 'localizedfields') {
                    $classDef = $classDef['localizedfields']->getFieldDefinitions();
                }
                $parentId = $classDef[$target]->getsource_parentid();
                $className = $classDef[$target]->getsource_classname();
                $method = $classDef[$target]->getsource_methodname();
                $fdef1 = ClassDefinition::getByName($className);

                $selClass = "\\Pimcore\\Model\\Object\\" . $className;
                $methodName = preg_replace("/get/", "", $method, 1);
                if (isset($fdef1->getFieldDefinitions()[$methodName]) || isset($fdef1->getFieldDefinitions()[lcfirst($methodName)])) {
                    $methodName = preg_replace("/get/", "getBy", $method, 1);
                    $myClassObj = $selClass::$methodName($value);
                } else {
                    $abc = $fdef1->getFieldDefinitions()['localizedfields']->getFieldDefinitions();
                    foreach ($abc as $k => $fd) {
                        if (lcfirst($fd->getName()) == lcfirst($methodName)) {
                            $methodName = $k;
                        }
                    }
                    $myClassObj = $selClass::getByLocalizedfields($methodName, $value, $this->languageKey);
                }
                foreach ($myClassObj as $dKey => $dValue) {
                    if ($dValue->getPath() == $parentId . "/") {
                        return $dValue;
                    }
                }
            } else {
                return [];
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Handle Object Type Field
     *
     * @param $classes
     * @param $value
     * @param null $source
     * @return array|string
     */
    public function handleObjectField($classes, $value, $source = null)
    {
        $baseClasses = array();
        foreach ($classes as $cls) {
            $baseClasses[strtolower($cls['classes'])] = $cls['classes'];
        }
        try {

            $data = array();
            $result = [];
            // This is to handle class name for mapped configuration
            if (count($baseClasses) && $this->mapper[$source]['ref_class'] != '' && array_key_exists(strtolower($this->mapper[$source]['ref_class']), $baseClasses)) {
                $class = "\\Pimcore\\Model\\Object\\" . $baseClasses[strtolower($this->mapper[$source]['ref_class'])];
                $checkCond = "getBy" . ucfirst($this->mapper[$source]['ref_field']);

                if ($this->brickType == 'csv') {
                    $values = str_replace('"', '', $values);
                }
                $values = explode($this->separator, $value);
                foreach ($values as $value) {
                    $ob = $class::{$checkCond}($value, true);

                    if ($ob) {
                        $data[] = $ob;
                    }
                }
            }
            return $data;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function handleLinkField($value)
    {
        try {

            if (!empty($value)) {
                $newValue = explode(".", $value);

                $link = new \Pimcore\Model\Object\Data\Link();
                $link->setPath($value);
                $link->setText($newValue[1] . '.' . $newValue[2]);
                $link->setTarget('_blank');
                $link->setTitle('Visit ' . $newValue[1] . '.' . $newValue[2]);

                return $link;
            } else {
                return [];
            }
        } catch (Exception $ex) {

        }
    }

    public function handleObjectMetadataField($allowedClass, $value, $source)
    {
        try {
            $data = array();
            if (empty($allowedClass)) {
                return $data;
            }
            $cls = \Pimcore\Model\Object\ClassDefinition::getById($allowedClass);

            $allowedClass = $cls->getName();

            if (empty($this->mapper[$source]['ref_class']) /* && strtolower($this->mapper[$source]['ref_class']) == strtolower($allowedClass)*/) {
                $class = "\\Pimcore\\Model\\DataObject\\" . $allowedClass;
                $checkCond = "getBy" . $allowedClass . "id";
                
                $values = explode($this->separator, $value);

                foreach ($values as $value) {
                    $ob = \Pimcore\Model\DataObject\ProductLine::getById($value);
                    if ($ob) {
                        $objectMetadata = new \Pimcore\Model\DataObject\Data\ObjectMetadata('metadata', [], $ob);                        
                        $data[] = $objectMetadata;
                    }
                }
            }
            return $data;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param $info
     */
    public function log($info)
    {
        try {
            $dispatcher = \Pimcore::getContainer()->get("event_dispatcher");
            $logEvent = new Log();
            $logEvent->setInfo($info);
            $dispatcher->dispatch('log_event', $logEvent);
        } catch (\Exception $ex) {

        }
    }

    public function queuedJobs($type, $data = NULL, $con = NULL)
    {
        try {
            $queueObj = new Tbljobqueue();
            $srcDestMappingObj = new Tblsrcdestmapping();
            $jobMasterObj = new Tbljobmaster();
            if ($type == 'queue') {
                $checkQueue = $queueObj->checkQueueExists($data['job_id'], $data['source_id']);
                if ($checkQueue == false) {
                    $queueObj->addQueueorMapping($data);
                }
            } elseif ($type == 'mapping') {
                $checkMapping = $srcDestMappingObj->checkMappingExists($data['dest_id']);
                if ($checkMapping == false) {
                    $queueObj->addQueueorMapping($data);
                }
            } elseif ($type == 'job') {
                $update['last_update'] = date("Y-m-d H:i:s");
                $jobMasterObj->doJobUpdateData($con, $update);
            }
        } catch (Exception $ex) {

        }
    }

    public function createCsv($dataArr, $jobId)
    {

        $fileHandle = $jobId . "_" . time();

        $csv = $this->getCsvData($dataArr);

        file_put_contents($this->getCsvFile($fileHandle), $csv, FILE_APPEND);
    }

    /**
     * @param $fileHandle
     *
     * @return string
     */
    protected function getCsvFile($fileHandle)
    {
        $logPath = PIMCORE_LOG_DIRECTORY . '/DataImportBundle';
        if (!is_dir($logPath)) {
            mkdir($logPath, 0777);
        }
        return $logPath . "/" . $fileHandle . '.csv';
    }

    protected function getCsvData($param)
    {

        try {
            $objects = $param;
            $csv = '';
            if (!empty($objects)) {

                $columns = array(['Source ID', 'Pim ID', 'Status', 'Result']);

                foreach ($columns as $col) {
                    foreach ($col as $columnIdx => $columnKey) {
                        $col[$columnIdx] = '"' . $columnKey . '"';
                    }
                    $csv = implode(';', $col) . "\r\n";
                }

                foreach ($objects as $o) {
                    foreach ($o as $key => $value) {

                        //clean value of evil stuff such as " and linebreaks
                        if (is_string($value)) {
                            $value = strip_tags($value);
                            $value = str_replace('"', '', $value);
                            $value = str_replace("\r", '', $value);
                            $value = str_replace("\n", '', $value);

                            $o[$key] = '"' . $value . '"';
                        }
                    }
                    $csv .= implode(';', $o) . "\r\n";
                }
            }

            return $csv;
        } catch (Exception $ex) {

        }
    }

}
