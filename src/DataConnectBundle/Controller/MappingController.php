<?php

/**
 * DataConnectBundle\MappingController
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) 2009-2016 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 * @desc This file is used for mapping configuration panel and related manipulation
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
use Symfony\Component\Serializer\Encoder\JsonDecode;
use DataConnectBundle\Event\Log;

/**
 * DataConnectBundle\MappingController Class
 */
class MappingController extends Controller
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
     * Get data connector mapping
     * @Route("/mapping/get-mapping-info", name="get_mapping_info")
     * @return json
     */
    public function getMappingInfoAction(Request $request)
    {
        try {
            $success = true;

            $name = $request->query->get('name');
            $classId = $request->query->get('classId');


            $obj = new Tblcolumnconnectiondetails();

            $sourceData = $obj->getSourceData($name, '');
            $cols = count($sourceData);

            if (!$cols) {
                return $this->json([
                    "success" => false
                ]);
            }

            $conObj = new Tbldataconnection();
            $getConnDetail = $conObj->getConnectionDetail($name);

            foreach ($sourceData as $key => $value) {
                $firstRowData[$key] = $value['name'];
            }

            $supportedFieldTypes = [
                "checkbox",
                "country",
                "date",
                "datetime",
                "href",
                "image",
                "input",
                "language",
                "table",
                "multiselect",
                "numeric",
                "password",
                "select",
                "slider",
                "textarea",
                "wysiwyg",
                "objects",
                "multihref",
                "geopoint",
                "geopolygon",
                "geobounds",
                "link",
                "user",
                "email",
                "gender",
                "firstname",
                "lastname",
                "newsletterActive",
                "newsletterConfirmed",
                "countrymultiselect",
                "objectsMetadata",
                "localizedfields",
                "quantityValue",
                "externalImage",
                "video",
                "dynamicDropdown"
            ];

            // get class data
            $class = ClassDefinition::getById($classId);
            $fields = $class->getFieldDefinitions();


            $classesList = new ClassDefinition\Listing();

            $classes = $classesList->load();

            foreach ($classes as $index => $cls) {
                $tmpCls[] = $cls->getName();
            }
            $classes = $tmpCls;

            list($availableFields, $clsFields) = $this->getSupportedFields($classId, $supportedFieldTypes);

            $mappingStore = [];
            for ($i = 0; $i < $cols; $i++) {
                $mappedField = null;
                if ($availableFields[$i]) {
                    $mappedField = $availableFields[$i][0];
                }

                $firstRow = $i;
                if (is_array($firstRowData)) {
                    $firstRow = $firstRowData[$i];
                    if (strlen($firstRow) > 40) {
                        $firstRow = substr($firstRow, 0, 40) . "...";
                    }
                }

                if (empty($sourceData[$i]['target_col'])) {
                    $clsKey = strtolower(str_replace(" ", "", $firstRow));
                    if (array_key_exists($clsKey, $clsFields)) {
                        $sourceData[$i]['target_col'] = $clsFields[$clsKey];
                    }
                }

                $mappingStore[] = [
                    "source" => $sourceData[$i]['label'],
                    "firstRow" => $firstRow,
                    "target" => $sourceData[$i]['target_col'],
                    "ref_class" => $sourceData[$i]['ref_class'],
                    "index" => $i,
                    "ref_field" => $sourceData[$i]['ref_field']
                ];
            }

            $rows = 0;
            return $this->json([
                "success" => $success,
                "targetFields" => $availableFields,
                "mappingStore" => $mappingStore,
                "rows" => $rows,
                "cols" => $cols,
                "keyname" => $getConnDetail['keyName'],
                "overWrite" => $getConnDetail['overwrite'],
                "objkeyname" => $getConnDetail['obj_key_prefix'],
                "classes" => $classes
            ]);
        } catch (\Exception $e) {
            $info = [
                'msg' => "ERROR : " . $e->getMessage() . "\n" . $e->getTraceAsString(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            print_r($info);
            return $this->json([
                "success" => false,
                "msg" => $e->getMessage()
            ]);
        }
    }

    /**
     * Method to get depandent fields of given class
     * @Route("/mapping/get-dependant-class-fields", name="get-dependant-class-fields")
     * @return json
     * @access public
     */
    public function getDependantClassFieldsAction(Request $request)
    {

        $classData = $request->query->get('classValue');
        $classDef = ClassDefinition::getByName($classData);
        $fieldDef = $classDef->getFieldDefinitions();
        $fieldArray = array();
        if (!empty($fieldDef)) {
            $i = 0;
            foreach ($fieldDef as $fkey => $fvalue) {
                if ($fvalue->getFieldType != 'objects') {
                    $fieldArray[$i][] = $fvalue->getName();
                    $fieldArray[$i][] = $fvalue->getTitle() . "(" . $fvalue->getFieldType() . ")";
                }
                $i++;
            }
        }
        return $this->json(["fields" => $fieldArray]);
    }

    /**
     * Method to get depandent fields of given class
     * @Route("/mapping/get-brick-dependant-class-fields", name="get-brick-dependant-class-fields")
     * @return json
     * @access public
     */
    public function getBrickDependantClassFieldsAction(Request $request)
    {

        $classData = $request->query->get('classValue');
        $classDef = ClassDefinition::getByName($classData);
        $fieldDef = $classDef->getFieldDefinitions();
        $fieldArray = array();
        if (!empty($fieldDef)) {
            $i = 0;
            foreach ($fieldDef as $fkey => $fvalue) {
                if ($fvalue->getFieldType != 'objects') {
                    $fieldArray[$i][] = $fvalue->getName();
                    $fieldArray[$i][] = $fvalue->getTitle() . "(" . $fvalue->getFieldType() . ")";
                }
                $i++;
            }
        }
        return $this->json(["fields" => $fieldArray]);
    }

    /**
     * Save data connector mapping
     * @Route("/mapping/save-mapping-data", name="save_mapping_data")
     * @return json
     */
    public function saveMappingDataAction(Request $request)
    {
        try {

            $mappingData = $request->request->get('mappingData');
            $conName = $request->request->get('conName');
            $keyName = $request->request->get('keyName');
            //$objKeyName = $request->request->get('objKeyName');
            $overWrite = $request->request->get('overWrite');

            $obj = new Tblcolumnconnectiondetails();
            $mapData = json_decode($mappingData, true);

            if (!empty($mapData)) {
                foreach ($mapData as $key => $value) {
                    $data = explode(',', str_replace('[', '', str_replace(']', '', $value)));

                    $check = $obj->checkMappingConnectionName($conName, $data);
                    if (count($check)) {
                        $obj->updateMappingDetails($data, $conName);
                    } else {
                        $obj->saveMappingConnectionName($data, $conName);
                    }
                }
            }
            if (!empty($keyName)) {
                $conObj = new Tbldataconnection();
                $conObj->updateDataConnectionKeyName($conName, $keyName, $overWrite, $keyName);
            }
            $info = [
                'msg' => "Connection updated " . $request->request->get('conName'),
                'type' => 'info',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json([
                'success' => true
            ]);
        } catch (Exception $e) {
            $info = [
                'msg' => "Connection updated error" . $request->request->get('conName'),
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
     * Get data connector mapping listing
     * @Route("/mapping/load-mapping-attributes", name="load_mapping_attributes")
     * @return json
     */
    public function loadMappingAttributesAction(Request $request)
    {
        try {
            $where = array();
            if (!empty($request->query->get('start'))) {
                $where['start'] = $this->getParam("start");
            }

            if (!empty($request->query->get("limit"))) {
                $where['limit'] = $request->query->get("limit");
            }

            if (!empty($request->query->get("sort"))) {
                $where['sort'] = $request->query->get("sort");
                $where['order'] = $request->query->get("dir");
            }

            if (!empty($request->query->get("type")) && $request->query->get("type") == 'edit') {
                $where['type'] = $request->query->get("type");
                $where['id'] = $request->query->get("id");
            }

            $obj = new Tbldataconnection();
            $return = $obj->loadMapping($where);
            $info = [
                'msg' => "Load mapping listing.",
                'type' => 'info',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json(array(
                "data" => $return['resultData'],
                'success' => true,
                'total' => $return['listObjectTotal']
            ));
        } catch (Exception $e) {
            $info = [
                'msg' => "Error mapping listing: " . $e->getMessage(),
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
     * Save data connector mapping
     * @Route("/mapping/save-advanced-mapping-data", name="save_advanced_mapping_data")
     * @return json
     */
    public function saveAdvancedMappingDataAction(Request $request)
    {
        try {

            $mappingData = $request->request->get('mappingData');
            $mapData = json_decode($mappingData, true);
            $selectedAttr = $request->request->get('selectedAttr');
            $conName = $request->request->get('conName');
            $classId = $request->request->get('classId');
            $colType = $request->request->get('colType');
//p_r($mapData);p_r($colType);p_r($selectedAttr);die;
            $obj = new Tblcolumnconnectiondetails();


            foreach ($mapData as $key => $value) {
                $data = explode(',', str_replace('[', '', str_replace(']', '', $value)));

                $check = $obj->checkMappingConnectionName($conName, $data);
                if (count($check)) {
                    $obj->updateMappingDetails($data, $conName, $colType, $selectedAttr);
                }
            }

            //$obj->updateDataConnectionKeyName($this->getParam("conName"), $this->getParam("keyName"), $this->getParam("overWrite"), $this->getParam("objKeyName"));
            $info = [
                'msg' => "Connection updated : " . $request->request->get('conName'),
                'type' => 'info',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json([
                'success' => true
            ]);
        } catch (Exception $e) {
            $info = [
                'msg' => "Connection updated error : " . $request->request->get('conName'),
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
     * Delete data connector mapping
     * @Route("/mapping/delete-mapping", name="delete_mapping")
     * @return json
     */
    public function deleteMappingAction(Request $request)
    {
        try {
            $obj = new Tbldataconnection();
            $obj1 = new Tblcolumnconnectiondetails();
            $check = $obj->checkDataConnectionKeyName($request->query->get("name"));
            If (count($check)) {
                $deleteData = $obj1->deleteMapping($request->query->get("name"));
                $updateData = $obj->updateMapping($request->query->get("name"), array());
            }
            $info = [
                'msg' => $this->getParam("name") . " mapping deleted successfully.",
                'type' => 'info',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json(array(
                'success' => true
            ));
        } catch (\Exception $e) {
            $info = [
                'msg' => "Error deleting: " . $e->getMessage(),
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
     * gives grid data in case of objectBrick and fieldcollection
     * @Route("/mapping/get-advanced-grid-mapping", name="get_advanced_grid_mapping")
     * @return json
     */
    public function getAdvancedGridMappingAction(Request $request)
    {
        try {

            $con = $request->query->get('con');
            $classId = $request->query->get('classId');
            $type = $request->query->get('type');
            $selectedAttr = $request->query->get('selectedAttr');

            $obj = new Tblcolumnconnectiondetails();
            $newSourceData = [];
            $sourceData = $obj->getSavedMapping($con, $type);

            foreach ($sourceData as $sKey => $sourceValue) {
                if ($type == 'objectbrick') {
                    $emptyTarget = str_replace('objectbrick', " ", $sourceValue['target_col']);
                } elseif ($type == 'fieldcollection') {
                    $emptyTarget = str_replace('fieldcollection', " ", $sourceValue['target_col']);
                }
                $sourceValue['target_col'] = $emptyTarget;
                $newSourceData[] = $sourceValue;
            }
            $connObj = new Tbldataconnection();
            $connDetails = $connObj->getConnectionDetail($con);

            $cols = count($newSourceData);

            foreach ($newSourceData as $key => $value) {
                $firstRowData[$key] = $value['name'];
            }

            // get class data
            $class = ClassDefinition::getById($classId);
            $fields = $class->getFieldDefinitions();
            $selectedAttr = explode(",", $selectedAttr);

            $brickName = [];
            $fieldCollectionName = [];
            if ($type == 'objectbrick') {
                list($availableFields, $brickAllowedClasses) = $this->getAdvancedBrickGridData($selectedAttr, $class->getName());
            } elseif ($type == 'fieldcollection') {
                $availableFields = $this->getAdvancedFieldCollectionGridData($selectedAttr, $classId);
            }

            $mappingStore = [];
            for ($i = 0; $i < $cols; $i++) {
                $mappedField = null;
                if ($availableFields[$i]) {
                    $mappedField = $availableFields[$i][0];
                }

                $firstRow = $i;
                if (is_array($firstRowData)) {
                    $firstRow = $firstRowData[$i];
                    if (strlen($firstRow) > 40) {
                        $firstRow = substr($firstRow, 0, 40) . "...";
                    }
                }

                $mappingStore[] = [
                    "source" => $newSourceData[$i]['label'],
                    "firstRow" => $firstRow,
                    "target" => $newSourceData[$i]['target_col'],
                    "ref_class" => $newSourceData[$i]['ref_class'],
                    "group" => $newSourceData[$i]['group_no'],
                    "index" => $i,
                    "brick_ref_field" => $newSourceData[$i]['ref_field']
                ];
            }
            //$unsavedMappingData = [];
            //$obj = new DataMappingModel();
            //$unsavedMappingData = $obj->getSavedMapping($this->getParam("con"), $this->getParam("type"));
            $rows = 0;
            return $this->json(array(
//                 'data' => $unsavedMappingData,
                'success' => true,
                "targetFields" => $availableFields,
                "mappingStore" => $mappingStore,
                "brickAllowedAttr" => $brickAllowedClasses,
                "rows" => $rows,
                "cols" => $cols
            ));
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
                ]
            );
        }
    }

    /**
     * this function will return brick grid
     *
     * @return array
     */
    public function getAdvancedBrickGridData($selectedAttr, $classId)
    {
        try {
            $availableFields = [];
            $brickAllowedClasses = [];
            if (!empty($selectedAttr)) {
                foreach ($selectedAttr as $brickValue) {
                    $brick = new \Pimcore\Model\Object\Objectbrick\Definition\Listing();
                    $brickListing = $brick->load();
                    foreach ($brickListing as $index => $brickObj) {
                        $cls = $brickObj->getClassDefinitions();
                        if (is_array($cls) && count($cls) > 0) {
                            foreach ($cls as $key => $def) {
                                if (($def['classname'] == $classId) && ($def['fieldname'] == $brickValue)) {
                                    $brickName[$brickObj->getKey()] = $brickObj->getFieldDefinitions();
                                }
                            }
                        }
                    }
                }
            }

            if (is_array($brickName)) {
                foreach ($brickName as $brkey => $bvalue) {
                    foreach ($bvalue as $fkey => $fvalue) {
                        $availableFields[] = [$brkey . "." . $fkey, $brkey . "." . $fkey . "(" . $fvalue->fieldtype . ")"];
                        if ($fvalue->fieldtype == 'objects') {
                            foreach ($fvalue->getClasses() as $cvalue) {
                                $brickAllowedClasses[] = $cvalue['classes'];
                            }
                        }
                    }
                }
            }
            return array($availableFields, $brickAllowedClasses);
        } catch (\Exception $ex) {
            return array($availableFields, $brickAllowedClasses);
        }
    }

    /**
     * this function will return fieldcollection grid
     *
     * @return type
     */
    public function getAdvancedFieldCollectionGridData($selectedAttr, $classId)
    {

        $class = ClassDefinition::getById($classId);
        $className = $class->getName();
        $class = "\\Pimcore\\Model\\Object\\" . ucfirst($className);
        $classObj = new $class;
        $availableFields = [];
        if (!empty($selectedAttr)) {
            foreach ($selectedAttr as $fieldCollectionValue) {
                $fieldCollections = $classObj->getClass()->getFieldDefinitions()[$fieldCollectionValue]->allowedTypes;
                foreach ($fieldCollections as $collectionValue) {
                    $collectionValueClass = ucfirst($collectionValue);
                    $fc = "\\Pimcore\\Model\\Object\\Fieldcollection\\Data\\" . $collectionValueClass;
                    $fcObj = new $fc;
                    $panel = $fcObj->getDefinition()->getFieldDefinitions();


                    $i = 1;
                    foreach ($panel as $key => $childs) {

                        if ($childs->fieldtype == 'localizedfields') {

                            $fieldCollectionName[] = [
                                "$collectionValue.localizedfields" => $childs];


                            $i++;
                        } else {
                            $fieldCollectionName[] = [
                                "$collectionValue" => $childs];
                        }
                    }
                }
            }

            $availableFields = $this->getFieldCollectionAttributes($fieldCollectionName);

            return $availableFields;
        } else {
            return $availableFields;
        }
    }

    /**
     *
     * @param array $data
     * @return array
     */
    public function getFieldCollectionAttributes($data)
    {

        if (is_array($data) && count($data)) {
            foreach ($data as $brkey => $bvalue) {
                if (is_array($bvalue)) {
                    foreach ($bvalue as $frKey => $frValue) {

                        if (is_object($frValue) && $frValue->datatype == 'data' && $frValue->fieldtype != 'localizedfields') {
                            $availableFields[] = [$frKey . "." . $frValue->name, $frKey . "." . $frValue->name . "(" . $frValue->fieldtype . ")"];
                        } else {

                            foreach ($frValue->getFieldDefinitions() as $frKey1 => $frValue1) {
                                $availableFields[] = [$frKey . "." . $frValue1->name, $frKey . "." . $frValue1->name . "(" . $frValue1->fieldtype . ")"];
                            }
                        }
                    }
                }
            }
        }

        return $availableFields;
    }

    /**
     * common function used to get different class attributes for mapping.
     *
     * @param int $classId
     * @param array $supportedFieldTypes
     * @return array
     */
    public function getSupportedFields($classId, $supportedFieldTypes)
    {

        $class = ClassDefinition::getById($classId);
        $fields = $class->getFieldDefinitions();
        $availableFields = [];
        $clsFields = [];
        foreach ($fields as $key => $field) {
            $config = null;
            $title = $field->getName();
            if (method_exists($field, "getTitle")) {
                if ($field->getTitle()) {
                    $title = $field->getTitle();
                }
            }

            if (in_array($field->getFieldType(), $supportedFieldTypes)) {
                if ($field->getFieldType() != 'localizedfields') {
                    $availableFields[] = [
                        $field->getName(),
                        $title . "(" . $field->getFieldType() . ")"
                    ];
                    $clsFields[strtolower($field->getName())] = $field->getName();
                } else if ($field->getFieldType() == 'localizedfields') {
                    $localField = $field->getFieldDefinitions();
                    foreach ($localField as $fieldName => $elDetail) {
                        $availableFields[] = [
                            $fieldName,
                            $elDetail->getTitle() . "(" . $field->getFieldType() . ")"
                        ];
                        $clsFields[strtolower($field->getName())] = trim($field->getName());
                    }
                }
            }
        }

        return array($availableFields, $clsFields);
    }

    /**
     * it return brick attributes
     * @Route("/mapping/get-brick-attributes", name="get_brick_attributes")
     * @return type
     */
    public function getBrickAttributesAction(Request $request)
    {
        try {

            $con = $request->query->get('con');
            $classId = $request->query->get('classId');

            $obj = new Tblcolumnconnectiondetails();
            $attrData = $obj->getSelectedAttr($con);

            $fieldTypes = [
                "objectbricks"
            ];

            list($result, $clsFields) = $this->getSupportedFields($classId, $fieldTypes);
            if (count($result) > 0) {
                foreach ($result as $key => $value) {
                    $data[] = [
                        "text" => $value[0],
                        //"selected" => $attrData,
                    ];
                }
            } else {
                $data[] = [
                    "text" => "",
                ];
            }

            return $this->json(array("brickStore" => $data, "success" => true));
        } catch (\Exception $e) {
            $info = [
                'msg' => "Error in getting brick attributes: " . $e->getMessage(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json([
                    'success' => false,
                    'msg' => $e->getMessage()
                ]
            );
        }
    }

    /**
     * it return selected brick attributes
     * @Route("/mapping/get-preselected-brick-attr", name="get-preselected-brick-attr")
     * @return type
     */
    public function getPreselectedBrickAttrAction(Request $request)
    {
        try {

            $con = $request->query->get('con');

            $obj = new Tblcolumnconnectiondetails();
            $attrData = $obj->getPreselectAttr($con, 'brick');

            if (count($attrData) > 0) {
                return $this->json(array("data" => $attrData, "success" => true));
            } else {
                return $this->json(array("success" => false));
            }
        } catch (\Exception $e) {
            $info = [
                'msg' => "Error in getting selected brick attributes: " . $e->getMessage(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json([
                    'success' => false,
                    'msg' => $e->getMessage()
                ]
            );
        }
    }

    /**
     * it return selected brick attributes
     * @Route("/mapping/get-preselected-field-collection-attr", name="get-preselected-field-collection-attr")
     * @return type
     */
    public function getPreselectedFieldCollectionAttrAction(Request $request)
    {
        try {

            $con = $request->query->get('con');
            $obj = new Tblcolumnconnectiondetails();
            $attrData = $obj->getPreselectAttr($con, 'fieldcollection');
            if (count($attrData) > 0) {
                return $this->json(array("data" => $attrData, "success" => true));
            } else {
                return $this->json(array("success" => false));
            }
        } catch (\Exception $e) {
            $info = [
                'msg' => "Error in getting selected brick attributes: " . $e->getMessage(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json([
                    'success' => false,
                    'msg' => $e->getMessage()
                ]
            );
        }
    }

    /**
     * it return the fieldcollection attributes
     * @Route("/mapping/get-field-collection-attributes", name="get_field_collection_attributes")
     * @return type
     */
    public function getFieldCollectionAttributesAction(Request $request)
    {
        try {
            $classId = $request->query->get('classId');
            $fieldTypes = [
                "fieldcollections",
            ];

            list($result, $clsFields) = $this->getSupportedFields($classId, $fieldTypes);
            if (count($result) > 0) {
                foreach ($result as $key => $value) {
                    $data[] = [
                        "text" => $value[0],
                    ];
                }
            } else {
                $data[] = [
                    "text" => "",
                ];
            }
            return $this->json(array("fieldcollectionStore" => $data, "success" => true));
        } catch (\Exception $e) {
            $info = [
                'msg' => "Error in getting field collection attributes: " . $e->getMessage(),
                'type' => 'error',
                'component' => date("Y-m-d")
            ];
            $this->log($info);
            return $this->json([
                    'success' => false,
                    'msg' => $e->getMessage()
                ]
            );
        }
    }

    /**
     * this will get the data for setting panel
     * @Route("/mapping/get-setting-panel-data", name="get_setting_panel_data")
     */
    public function getSettingPanelDataAction(Request $request)
    {
        try {
            $conName = $request->query->get('conName');

            $settingData = [];
            $objkeyname = '';
            $overwrite = '';
            $objKeyPrefix = '';
            $obj = new Tblcolumnconnectiondetails();
            $data = $obj->getMappedDataColumns($conName);
            if (is_array($data) && count($data) > 0) {
                foreach ($data as $sKey => $sValue) {
                    $settingData[] = [
                        "firstRow" => $sValue['name']
                    ];
                    $objkeyname = $sValue['data_key_name'];
                    $overwrite = $sValue['overwrite'];
                    $objKeyPrefix = $sValue['obj_key_prefix'];
                }
            }

            return $this->json(array("settingData" => $settingData,
                "objkeyname" => $objkeyname,
                "overWrite" => $overwrite,
                "keyname" => $objKeyPrefix,
                "cols" => count($settingData), "success" => true));
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

}
