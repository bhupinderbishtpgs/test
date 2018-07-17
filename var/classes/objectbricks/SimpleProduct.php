<?php 

/** 
* Generated at: 2018-07-17T11:08:40+02:00


Fields Summary: 
 - functionality [select]
 - packagingType [select]
 - retailValue [numeric]
 - sizeImpressionScore [numeric]
 - universal [checkbox]
 - specialCostPerUnit [numeric]
*/ 


return Pimcore\Model\DataObject\Objectbrick\Definition::__set_state(array(
   'classDefinitions' => 
  array (
    0 => 
    array (
      'classname' => 'Product',
      'fieldname' => 'productSpecificAttributes',
    ),
  ),
   'key' => 'SimpleProduct',
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
         'title' => 'Layout',
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
             'name' => 'basicInfo',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Basic Info',
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
                    'key' => 'Accessory',
                    'value' => 'Accessory',
                  ),
                  1 => 
                  array (
                    'key' => 'Bathing',
                    'value' => 'Bathing',
                  ),
                  2 => 
                  array (
                    'key' => ' Beards',
                    'value' => ' Beards',
                  ),
                  3 => 
                  array (
                    'key' => 'Cheeks',
                    'value' => 'Cheeks',
                  ),
                  4 => 
                  array (
                    'key' => 'Cleansing',
                    'value' => 'Cleansing',
                  ),
                  5 => 
                  array (
                    'key' => 'Concealer',
                    'value' => 'Concealer',
                  ),
                  6 => 
                  array (
                    'key' => 'Dental',
                    'value' => 'Dental',
                  ),
                  7 => 
                  array (
                    'key' => 'DIY Treatment',
                    'value' => 'DIY Treatment',
                  ),
                  8 => 
                  array (
                    'key' => 'Dry Hair Styling',
                    'value' => 'Dry Hair Styling',
                  ),
                  9 => 
                  array (
                    'key' => 'Dry Hair Treatments',
                    'value' => 'Dry Hair Treatments',
                  ),
                  10 => 
                  array (
                    'key' => 'Dry Shampoo',
                    'value' => 'Dry Shampoo',
                  ),
                  11 => 
                  array (
                    'key' => 'Edible',
                    'value' => 'Edible',
                  ),
                  12 => 
                  array (
                    'key' => 'Exfoliating',
                    'value' => 'Exfoliating',
                  ),
                  13 => 
                  array (
                    'key' => 'Eyes',
                    'value' => 'Eyes',
                  ),
                  14 => 
                  array (
                    'key' => 'Eyebrows',
                    'value' => 'Eyebrows',
                  ),
                  15 => 
                  array (
                    'key' => 'Face Shade',
                    'value' => 'Face Shade',
                  ),
                  16 => 
                  array (
                    'key' => 'Hair Cleaning',
                    'value' => 'Hair Cleaning',
                  ),
                  17 => 
                  array (
                    'key' => 'Hair Spray Bottle',
                    'value' => 'Hair Spray Bottle',
                  ),
                  18 => 
                  array (
                    'key' => 'Hair Treatments',
                    'value' => 'Hair Treatments',
                  ),
                  19 => 
                  array (
                    'key' => 'Home Good',
                    'value' => 'Home Good',
                  ),
                  20 => 
                  array (
                    'key' => 'Lips',
                    'value' => 'Lips',
                  ),
                  21 => 
                  array (
                    'key' => 'Lotion or Cream',
                    'value' => 'Lotion or Cream',
                  ),
                  22 => 
                  array (
                    'key' => 'Mascara',
                    'value' => 'Mascara',
                  ),
                  23 => 
                  array (
                    'key' => 'Mask or Peel',
                    'value' => 'Mask or Peel',
                  ),
                  24 => 
                  array (
                    'key' => 'Makeup Remover',
                    'value' => 'Makeup Remover',
                  ),
                  25 => 
                  array (
                    'key' => 'Mens Box Lifestyle',
                    'value' => 'Mens Box Lifestyle',
                  ),
                  26 => 
                  array (
                    'key' => 'Nail Polish',
                    'value' => 'Nail Polish',
                  ),
                  27 => 
                  array (
                    'key' => 'Oil',
                    'value' => 'Oil',
                  ),
                  28 => 
                  array (
                    'key' => 'Primer',
                    'value' => 'Primer',
                  ),
                  29 => 
                  array (
                    'key' => 'Wet Hair Treatments',
                    'value' => 'Wet Hair Treatments',
                  ),
                  30 => 
                  array (
                    'key' => 'Wet Hair Styling',
                    'value' => 'Wet Hair Styling',
                  ),
                  31 => 
                  array (
                    'key' => 'Tool',
                    'value' => 'Tool',
                  ),
                  32 => 
                  array (
                    'key' => 'Treatment Serum or Gel or Cream',
                    'value' => 'Treatment Serum or Gel or Cream',
                  ),
                  33 => 
                  array (
                    'key' => 'Scent',
                    'value' => 'Scent',
                  ),
                  34 => 
                  array (
                    'key' => 'Shaving Routine',
                    'value' => 'Shaving Routine',
                  ),
                  35 => 
                  array (
                    'key' => 'Shave Cream',
                    'value' => 'Shave Cream',
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
                 'name' => 'functionality',
                 'title' => 'Functionality',
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
                    'key' => 'Deluxe',
                    'value' => 'Deluxe',
                  ),
                  1 => 
                  array (
                    'key' => 'Flat',
                    'value' => 'Flat',
                  ),
                  2 => 
                  array (
                    'key' => 'Full Size',
                    'value' => 'Full Size',
                  ),
                  3 => 
                  array (
                    'key' => 'Other',
                    'value' => 'Other',
                  ),
                  4 => 
                  array (
                    'key' => 'Packette',
                    'value' => 'Packette',
                  ),
                  5 => 
                  array (
                    'key' => 'Secondary Unit',
                    'value' => 'Secondary Unit',
                  ),
                  6 => 
                  array (
                    'key' => 'VOC',
                    'value' => 'VOC',
                  ),
                  7 => 
                  array (
                    'key' => 'Xela Pack',
                    'value' => 'Xela Pack',
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
                 'name' => 'packagingType',
                 'title' => 'Packaging Type',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Numeric::__set_state(array(
                 'fieldtype' => 'numeric',
                 'width' => 150,
                 'defaultValue' => NULL,
                 'queryColumnType' => 'double',
                 'columnType' => 'double',
                 'phpdocType' => 'float',
                 'integer' => false,
                 'unsigned' => false,
                 'minValue' => NULL,
                 'maxValue' => NULL,
                 'unique' => false,
                 'decimalSize' => NULL,
                 'decimalPrecision' => NULL,
                 'name' => 'retailValue',
                 'title' => 'Retail Value',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Numeric::__set_state(array(
                 'fieldtype' => 'numeric',
                 'width' => 150,
                 'defaultValue' => NULL,
                 'queryColumnType' => 'double',
                 'columnType' => 'double',
                 'phpdocType' => 'float',
                 'integer' => true,
                 'unsigned' => false,
                 'minValue' => 0,
                 'maxValue' => 10,
                 'unique' => false,
                 'decimalSize' => NULL,
                 'decimalPrecision' => NULL,
                 'name' => 'sizeImpressionScore',
                 'title' => 'Size Impression Score',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Checkbox::__set_state(array(
                 'fieldtype' => 'checkbox',
                 'defaultValue' => 0,
                 'queryColumnType' => 'tinyint(1)',
                 'columnType' => 'tinyint(1)',
                 'phpdocType' => 'boolean',
                 'name' => 'universal',
                 'title' => 'Universal',
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
          1 => 
          Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'fieldtype' => 'panel',
             'labelWidth' => 100,
             'layout' => NULL,
             'name' => 'finance',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Finance',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Numeric::__set_state(array(
                 'fieldtype' => 'numeric',
                 'width' => 150,
                 'defaultValue' => NULL,
                 'queryColumnType' => 'double',
                 'columnType' => 'double',
                 'phpdocType' => 'float',
                 'integer' => false,
                 'unsigned' => false,
                 'minValue' => NULL,
                 'maxValue' => NULL,
                 'unique' => false,
                 'decimalSize' => NULL,
                 'decimalPrecision' => NULL,
                 'name' => 'specialCostPerUnit',
                 'title' => 'Special Cost Per Unit',
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
