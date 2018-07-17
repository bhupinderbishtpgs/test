<?php 

/** 
* Generated at: 2018-07-17T11:08:32+02:00


Fields Summary: 
 - componentType [select]
 - componentCategory [select]
 - dimensions [input]
 - isStore [checkbox]
 - unitOfMeasure [input]
 - uomConversion [input]
*/ 


return Pimcore\Model\DataObject\Objectbrick\Definition::__set_state(array(
   'classDefinitions' => 
  array (
    0 => 
    array (
      'classname' => 'Component',
      'fieldname' => 'productSpecificAttributes',
    ),
  ),
   'key' => 'Component',
   'parentClass' => '',
   'layoutDefinitions' => 
  Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
     'fieldtype' => 'panel',
     'labelWidth' => 100,
     'layout' => NULL,
     'name' => NULL,
     'type' => NULL,
     'region' => NULL,
     'title' => NULL,
     'width' => NULL,
     'height' => NULL,
     'collapsible' => false,
     'collapsed' => NULL,
     'bodyStyle' => NULL,
     'datatype' => 'layout',
     'permissions' => NULL,
     'childs' => 
    array (
      0 => 
      Pimcore\Model\DataObject\ClassDefinition\Layout\Tabpanel::__set_state(array(
         'fieldtype' => 'tabpanel',
         'name' => 'Layout',
         'type' => NULL,
         'region' => NULL,
         'title' => '',
         'width' => NULL,
         'height' => NULL,
         'collapsible' => false,
         'collapsed' => false,
         'bodyStyle' => '',
         'datatype' => 'layout',
         'permissions' => NULL,
         'childs' => 
        array (
          0 => 
          Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'fieldtype' => 'panel',
             'labelWidth' => 100,
             'layout' => NULL,
             'name' => 'Component Data',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Component Data',
             'width' => NULL,
             'height' => NULL,
             'collapsible' => false,
             'collapsed' => false,
             'bodyStyle' => '',
             'datatype' => 'layout',
             'permissions' => NULL,
             'childs' => 
            array (
              0 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                 'fieldtype' => 'select',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'Bag',
                    'value' => 'bag',
                  ),
                  1 => 
                  array (
                    'key' => 'Crinkle',
                    'value' => 'crinkle',
                  ),
                  2 => 
                  array (
                    'key' => 'Gift Card',
                    'value' => 'gitfcard',
                  ),
                  3 => 
                  array (
                    'key' => 'Insert',
                    'value' => 'insert',
                  ),
                  4 => 
                  array (
                    'key' => 'Kits/Fullsize',
                    'value' => 'kits/fullsize',
                  ),
                  5 => 
                  array (
                    'key' => 'Label',
                    'value' => 'label',
                  ),
                  6 => 
                  array (
                    'key' => 'Liner',
                    'value' => 'liner',
                  ),
                  7 => 
                  array (
                    'key' => 'Other',
                    'value' => 'other',
                  ),
                  8 => 
                  array (
                    'key' => 'Pillow pack',
                    'value' => 'pillow pack',
                  ),
                  9 => 
                  array (
                    'key' => 'Product Card',
                    'value' => 'product_card',
                  ),
                  10 => 
                  array (
                    'key' => 'Shipper',
                    'value' => 'shipper',
                  ),
                  11 => 
                  array (
                    'key' => 'Sleeve',
                    'value' => 'sleeve',
                  ),
                  12 => 
                  array (
                    'key' => 'Slipcase/lid',
                    'value' => 'slipcase/lid',
                  ),
                  13 => 
                  array (
                    'key' => 'Sticker',
                    'value' => 'sticker',
                  ),
                  14 => 
                  array (
                    'key' => 'Tamale',
                    'value' => 'tamale',
                  ),
                  15 => 
                  array (
                    'key' => 'Tape',
                    'value' => 'tape',
                  ),
                  16 => 
                  array (
                    'key' => 'Tissue',
                    'value' => 'tissue',
                  ),
                  17 => 
                  array (
                    'key' => 'Tray/Base',
                    'value' => 'tray/base',
                  ),
                ),
                 'width' => '',
                 'defaultValue' => '',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'queryColumnType' => 'varchar',
                 'columnType' => 'varchar',
                 'columnLength' => 190,
                 'phpdocType' => 'string',
                 'name' => 'componentType',
                 'title' => 'Component Type',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
              )),
              1 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                 'fieldtype' => 'select',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'B2B',
                    'value' => 'B2B',
                  ),
                  1 => 
                  array (
                    'key' => 'Full Size',
                    'value' => 'Full Size',
                  ),
                  2 => 
                  array (
                    'key' => 'LTE',
                    'value' => 'LTE',
                  ),
                  3 => 
                  array (
                    'key' => 'PR',
                    'value' => 'PR',
                  ),
                  4 => 
                  array (
                    'key' => 'Retail',
                    'value' => 'Retail',
                  ),
                  5 => 
                  array (
                    'key' => 'Subscription',
                    'value' => 'Subscription',
                  ),
                ),
                 'width' => '',
                 'defaultValue' => '',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'queryColumnType' => 'varchar',
                 'columnType' => 'varchar',
                 'columnLength' => 190,
                 'phpdocType' => 'string',
                 'name' => 'componentCategory',
                 'title' => 'Component Category',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
              )),
              2 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'fieldtype' => 'input',
                 'width' => NULL,
                 'queryColumnType' => 'varchar',
                 'columnType' => 'varchar',
                 'columnLength' => 250,
                 'phpdocType' => 'string',
                 'regex' => '',
                 'unique' => false,
                 'name' => 'dimensions',
                 'title' => 'Dimensions',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
              )),
              3 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Checkbox::__set_state(array(
                 'fieldtype' => 'checkbox',
                 'defaultValue' => 0,
                 'queryColumnType' => 'tinyint(1)',
                 'columnType' => 'tinyint(1)',
                 'phpdocType' => 'boolean',
                 'name' => 'isStore',
                 'title' => 'Is Store',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
              )),
              4 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'fieldtype' => 'input',
                 'width' => NULL,
                 'queryColumnType' => 'varchar',
                 'columnType' => 'varchar',
                 'columnLength' => 250,
                 'phpdocType' => 'string',
                 'regex' => '',
                 'unique' => false,
                 'name' => 'unitOfMeasure',
                 'title' => 'Unit of Measure',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
              )),
              5 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'fieldtype' => 'input',
                 'width' => NULL,
                 'queryColumnType' => 'varchar',
                 'columnType' => 'varchar',
                 'columnLength' => 250,
                 'phpdocType' => 'string',
                 'regex' => '',
                 'unique' => false,
                 'name' => 'uomConversion',
                 'title' => 'UoM Conversion',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
              )),
            ),
             'locked' => false,
          )),
        ),
         'locked' => false,
      )),
    ),
     'locked' => false,
  )),
   'dao' => NULL,
));
