<?php 

/** 
* Generated at: 2018-07-17T11:06:08+02:00


Fields Summary: 
 - colorName [input]
 - size [quantityValue]
 - size_display [input]
 - planningNotes [textarea]
 - upc [input]
 - msdsUrl [input]
 - msdsExists [checkbox]
 - casepackSize [numeric]
 - doNotReconcile [checkbox]
 - hazmat [checkbox]
 - reactivateDate [date]
 - scent [select]
 - legacyColor [select]
 - color [objectsMetadata]
 - pantone [input]
 - shadeGroup [select]
 - spfLevel [input]
 - concern [multiselect]
 - effect [multiselect]
 - formulation [multiselect]
 - gender [multiselect]
 - ingredientPreference [multiselect]
 - skinType [multiselect]
 - coerage [multiselect]
 - finish [multiselect]
 - skinShade [multiselect]
 - itemColor [multiselect]
 - hairType [multiselect]
 - entityRelationships [objects]
*/ 


return Pimcore\Model\DataObject\Objectbrick\Definition::__set_state(array(
   'classDefinitions' => 
  array (
    0 => 
    array (
      'classname' => 'Product',
      'fieldname' => 'additionalAttributes',
    ),
    1 => 
    array (
      'classname' => 'BoxandKit',
      'fieldname' => 'additionalAttributes',
    ),
  ),
   'key' => 'AdditionalAttributes',
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
             'name' => 'basicinfo',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'fieldtype' => 'input',
                 'width' => NULL,
                 'queryColumnType' => 'varchar',
                 'columnType' => 'varchar',
                 'columnLength' => 190,
                 'phpdocType' => 'string',
                 'regex' => '',
                 'unique' => false,
                 'name' => 'colorName',
                 'title' => 'Color Name',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\QuantityValue::__set_state(array(
                 'fieldtype' => 'quantityValue',
                 'width' => 100,
                 'defaultValue' => NULL,
                 'defaultUnit' => NULL,
                 'validUnits' => 
                array (
                ),
                 'decimalPrecision' => NULL,
                 'queryColumnType' => 
                array (
                  'value' => 'double',
                  'unit' => 'bigint(20)',
                ),
                 'columnType' => 
                array (
                  'value' => 'double',
                  'unit' => 'bigint(20)',
                ),
                 'phpdocType' => '\\Pimcore\\Model\\DataObject\\Data\\QuantityValue',
                 'name' => 'size',
                 'title' => 'Size',
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
                 'columnLength' => 190,
                 'phpdocType' => 'string',
                 'regex' => '',
                 'unique' => false,
                 'name' => 'size_display',
                 'title' => 'Display Size',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                 'fieldtype' => 'textarea',
                 'width' => '',
                 'height' => '',
                 'queryColumnType' => 'longtext',
                 'columnType' => 'longtext',
                 'phpdocType' => 'string',
                 'name' => 'planningNotes',
                 'title' => 'Planning Notes',
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
                 'columnLength' => 190,
                 'phpdocType' => 'string',
                 'regex' => '',
                 'unique' => false,
                 'name' => 'upc',
                 'title' => 'UPC',
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
             'layout' => '',
             'name' => 'operations',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Operations',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'fieldtype' => 'input',
                 'width' => NULL,
                 'queryColumnType' => 'varchar',
                 'columnType' => 'varchar',
                 'columnLength' => 250,
                 'phpdocType' => 'string',
                 'regex' => '',
                 'unique' => false,
                 'name' => 'msdsUrl',
                 'title' => 'MSDS Url',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Checkbox::__set_state(array(
                 'fieldtype' => 'checkbox',
                 'defaultValue' => 0,
                 'queryColumnType' => 'tinyint(1)',
                 'columnType' => 'tinyint(1)',
                 'phpdocType' => 'boolean',
                 'name' => 'msdsExists',
                 'title' => 'MSDS exists',
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
                 'name' => 'casepackSize',
                 'title' => 'Casepack Size',
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
                 'name' => 'doNotReconcile',
                 'title' => 'Do Not Reconcile',
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
                 'name' => 'hazmat',
                 'title' => 'Hazmat',
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
          2 => 
          Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'fieldtype' => 'panel',
             'labelWidth' => 100,
             'layout' => NULL,
             'name' => 'ecommerce',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Ecommerce',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Date::__set_state(array(
                 'fieldtype' => 'date',
                 'queryColumnType' => 'bigint(20)',
                 'columnType' => 'bigint(20)',
                 'phpdocType' => '\\Carbon\\Carbon',
                 'defaultValue' => NULL,
                 'useCurrentDate' => false,
                 'name' => 'reactivateDate',
                 'title' => 'Reactivate Date',
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
          3 => 
          Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'fieldtype' => 'panel',
             'labelWidth' => 100,
             'layout' => '',
             'name' => 'ontologyandframeworks',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Ontology and Frameworks',
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
                    'key' => 'Floral',
                    'value' => 'Floral',
                  ),
                  1 => 
                  array (
                    'key' => 'Fresh',
                    'value' => 'Fresh',
                  ),
                  2 => 
                  array (
                    'key' => 'Sweet & Spicy',
                    'value' => 'Sweet & Spicy',
                  ),
                  3 => 
                  array (
                    'key' => 'Warm & Spicy',
                    'value' => 'Warm & Spicy',
                  ),
                  4 => 
                  array (
                    'key' => 'Woody',
                    'value' => 'Woody',
                  ),
                  5 => 
                  array (
                    'key' => 'Woody & Earthy',
                    'value' => 'Woody & Earthy',
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
                 'name' => 'scent',
                 'title' => 'Scent',
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
                ),
                 'width' => '',
                 'defaultValue' => '',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'queryColumnType' => 'varchar',
                 'columnType' => 'varchar',
                 'columnLength' => 190,
                 'phpdocType' => 'string',
                 'name' => 'legacyColor',
                 'title' => 'Legacy Color',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\ObjectsMetadata::__set_state(array(
                 'allowedClassId' => 40,
                 'visibleFields' => 'name',
                 'columns' => 
                array (
                ),
                 'fieldtype' => 'objectsMetadata',
                 'phpdocType' => '\\Pimcore\\Model\\DataObject\\Data\\ObjectMetadata[]',
                 'width' => '',
                 'height' => '',
                 'maxItems' => '',
                 'queryColumnType' => 'text',
                 'relationType' => true,
                 'lazyLoading' => false,
                 'classes' => 
                array (
                ),
                 'pathFormatterClass' => '',
                 'name' => 'color',
                 'title' => 'Color',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'columnType' => NULL,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
                 'columnKeys' => 
                array (
                ),
              )),
              3 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'fieldtype' => 'input',
                 'width' => NULL,
                 'queryColumnType' => 'varchar',
                 'columnType' => 'varchar',
                 'columnLength' => 190,
                 'phpdocType' => 'string',
                 'regex' => '',
                 'unique' => false,
                 'name' => 'pantone',
                 'title' => 'Pantone',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                 'fieldtype' => 'select',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'Brown',
                    'value' => 'Brown',
                  ),
                  1 => 
                  array (
                    'key' => 'Deep Brown',
                    'value' => 'Deep Brown',
                  ),
                  2 => 
                  array (
                    'key' => 'Deep Tan',
                    'value' => 'Deep Tan',
                  ),
                  3 => 
                  array (
                    'key' => 'Fair',
                    'value' => 'Fair',
                  ),
                  4 => 
                  array (
                    'key' => 'Light',
                    'value' => 'Light',
                  ),
                  5 => 
                  array (
                    'key' => 'Medium',
                    'value' => 'Medium',
                  ),
                  6 => 
                  array (
                    'key' => 'Tan',
                    'value' => 'Tan',
                  ),
                  7 => 
                  array (
                    'key' => 'Translucent',
                    'value' => 'Translucent',
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
                 'name' => 'shadeGroup',
                 'title' => 'Shade Group',
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
                 'columnLength' => 190,
                 'phpdocType' => 'string',
                 'regex' => '',
                 'unique' => false,
                 'name' => 'spfLevel',
                 'title' => 'SPF Level',
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
              6 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                 'fieldtype' => 'multiselect',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'Acne/Blemish',
                    'value' => 'Acne/Blemish',
                  ),
                  1 => 
                  array (
                    'key' => 'Aging',
                    'value' => 'Aging',
                  ),
                  2 => 
                  array (
                    'key' => 'Anti - Breakage',
                    'value' => 'Anti - Breakage',
                  ),
                  3 => 
                  array (
                    'key' => 'Anti - Frizz',
                    'value' => 'Anti - Frizz',
                  ),
                  4 => 
                  array (
                    'key' => 'Cellulite',
                    'value' => 'Cellulite',
                  ),
                  5 => 
                  array (
                    'key' => 'Color Protection',
                    'value' => 'Color Protection',
                  ),
                  6 => 
                  array (
                    'key' => 'Curl Enhancing',
                    'value' => 'Curl Enhancing',
                  ),
                  7 => 
                  array (
                    'key' => 'Damaged & Split Ends',
                    'value' => 'Damaged & Split Ends',
                  ),
                  8 => 
                  array (
                    'key' => 'Dandruff',
                    'value' => 'Dandruff',
                  ),
                  9 => 
                  array (
                    'key' => 'Dark circles',
                    'value' => 'Dark circles',
                  ),
                  10 => 
                  array (
                    'key' => 'Dark Spots',
                    'value' => 'Dark Spots',
                  ),
                  11 => 
                  array (
                    'key' => 'Dead Skin',
                    'value' => 'Dead Skin',
                  ),
                  12 => 
                  array (
                    'key' => 'Dryness',
                    'value' => 'Dryness',
                  ),
                  13 => 
                  array (
                    'key' => 'Dullness',
                    'value' => 'Dullness',
                  ),
                  14 => 
                  array (
                    'key' => 'Hair Loss',
                    'value' => 'Hair Loss',
                  ),
                  15 => 
                  array (
                    'key' => 'Hair Removal',
                    'value' => 'Hair Removal',
                  ),
                  16 => 
                  array (
                    'key' => 'Heat Protection',
                    'value' => 'Heat Protection',
                  ),
                  17 => 
                  array (
                    'key' => 'Irritation',
                    'value' => 'Irritation',
                  ),
                  18 => 
                  array (
                    'key' => 'Limp Hair',
                    'value' => 'Limp Hair',
                  ),
                  19 => 
                  array (
                    'key' => 'Odor',
                    'value' => 'Odor',
                  ),
                  20 => 
                  array (
                    'key' => 'Oiliness',
                    'value' => 'Oiliness',
                  ),
                  21 => 
                  array (
                    'key' => 'Redness',
                    'value' => 'Redness',
                  ),
                  22 => 
                  array (
                    'key' => 'Scars',
                    'value' => 'Scars',
                  ),
                  23 => 
                  array (
                    'key' => 'Stretch Marks',
                    'value' => 'Stretch Marks',
                  ),
                  24 => 
                  array (
                    'key' => 'Uneven skin tone',
                    'value' => 'Uneven skin tone',
                  ),
                  25 => 
                  array (
                    'key' => 'Visible Pores',
                    'value' => 'Visible Pores',
                  ),
                  26 => 
                  array (
                    'key' => 'Weak',
                    'value' => 'Weak',
                  ),
                ),
                 'width' => '',
                 'height' => '',
                 'maxItems' => '',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'queryColumnType' => 'text',
                 'columnType' => 'text',
                 'phpdocType' => 'array',
                 'name' => 'concern',
                 'title' => 'Concern',
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
              7 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                 'fieldtype' => 'multiselect',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'Anti-Aging',
                    'value' => 'Anti-Aging',
                  ),
                  1 => 
                  array (
                    'key' => 'Anti-Wrinkles',
                    'value' => 'Anti-Wrinkles',
                  ),
                  2 => 
                  array (
                    'key' => 'Aromatherapy',
                    'value' => 'Aromatherapy',
                  ),
                  3 => 
                  array (
                    'key' => 'Beachy Waves',
                    'value' => 'Beachy Waves',
                  ),
                  4 => 
                  array (
                    'key' => 'Brightening',
                    'value' => 'Brightening',
                  ),
                  5 => 
                  array (
                    'key' => 'Curling',
                    'value' => 'Curling',
                  ),
                  6 => 
                  array (
                    'key' => 'Conditioning',
                    'value' => 'Conditioning',
                  ),
                  7 => 
                  array (
                    'key' => 'Chip Free',
                    'value' => 'Chip Free',
                  ),
                  8 => 
                  array (
                    'key' => 'Cleansing',
                    'value' => 'Cleansing',
                  ),
                  9 => 
                  array (
                    'key' => 'Detangling',
                    'value' => 'Detangling',
                  ),
                  10 => 
                  array (
                    'key' => 'Detoxifying',
                    'value' => 'Detoxifying',
                  ),
                  11 => 
                  array (
                    'key' => 'Exfoliating',
                    'value' => 'Exfoliating',
                  ),
                  12 => 
                  array (
                    'key' => 'Firming',
                    'value' => 'Firming',
                  ),
                  13 => 
                  array (
                    'key' => 'Hair Trimming',
                    'value' => 'Hair Trimming',
                  ),
                  14 => 
                  array (
                    'key' => 'Hardening',
                    'value' => 'Hardening',
                  ),
                  15 => 
                  array (
                    'key' => 'Hold',
                    'value' => 'Hold',
                  ),
                  16 => 
                  array (
                    'key' => 'Hydrating',
                    'value' => 'Hydrating',
                  ),
                  17 => 
                  array (
                    'key' => 'Hypoallergenic',
                    'value' => 'Hypoallergenic',
                  ),
                  18 => 
                  array (
                    'key' => 'Lathering',
                    'value' => 'Lathering',
                  ),
                  19 => 
                  array (
                    'key' => 'Long lasting',
                    'value' => 'Long lasting',
                  ),
                  20 => 
                  array (
                    'key' => 'Mattifying',
                    'value' => 'Mattifying',
                  ),
                  21 => 
                  array (
                    'key' => 'More Volume',
                    'value' => 'More Volume',
                  ),
                  22 => 
                  array (
                    'key' => 'Non-Comedogenic',
                    'value' => 'Non-Comedogenic',
                  ),
                  23 => 
                  array (
                    'key' => 'Nourishing',
                    'value' => 'Nourishing',
                  ),
                  24 => 
                  array (
                    'key' => 'Scar Reduction',
                    'value' => 'Scar Reduction',
                  ),
                  25 => 
                  array (
                    'key' => 'Shine',
                    'value' => 'Shine',
                  ),
                  26 => 
                  array (
                    'key' => 'Smoothing',
                    'value' => 'Smoothing',
                  ),
                  27 => 
                  array (
                    'key' => 'Sun Protection',
                    'value' => 'Sun Protection',
                  ),
                  28 => 
                  array (
                    'key' => 'Straightening',
                    'value' => 'Straightening',
                  ),
                  29 => 
                  array (
                    'key' => 'Strengthens',
                    'value' => 'Strengthens',
                  ),
                  30 => 
                  array (
                    'key' => 'Styling',
                    'value' => 'Styling',
                  ),
                  31 => 
                  array (
                    'key' => 'Tanning',
                    'value' => 'Tanning',
                  ),
                  32 => 
                  array (
                    'key' => 'Quick Dry',
                    'value' => 'Quick Dry',
                  ),
                ),
                 'width' => '',
                 'height' => '',
                 'maxItems' => '',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'queryColumnType' => 'text',
                 'columnType' => 'text',
                 'phpdocType' => 'array',
                 'name' => 'effect',
                 'title' => 'Effect',
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
              8 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                 'fieldtype' => 'multiselect',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'Balm',
                    'value' => 'Balm',
                  ),
                  1 => 
                  array (
                    'key' => 'Bar',
                    'value' => 'Bar',
                  ),
                  2 => 
                  array (
                    'key' => 'Cream',
                    'value' => 'Cream',
                  ),
                  3 => 
                  array (
                    'key' => 'Foam',
                    'value' => 'Foam',
                  ),
                  4 => 
                  array (
                    'key' => 'Gel',
                    'value' => 'Gel',
                  ),
                  5 => 
                  array (
                    'key' => 'Gloss',
                    'value' => 'Gloss',
                  ),
                  6 => 
                  array (
                    'key' => 'Incense',
                    'value' => 'Incense',
                  ),
                  7 => 
                  array (
                    'key' => 'Liquid',
                    'value' => 'Liquid',
                  ),
                  8 => 
                  array (
                    'key' => 'Loose Powder',
                    'value' => 'Loose Powder',
                  ),
                  9 => 
                  array (
                    'key' => 'Lotion',
                    'value' => 'Lotion',
                  ),
                  10 => 
                  array (
                    'key' => 'Mask',
                    'value' => 'Mask',
                  ),
                  11 => 
                  array (
                    'key' => 'Mineral',
                    'value' => 'Mineral',
                  ),
                  12 => 
                  array (
                    'key' => 'Mousse',
                    'value' => 'Mousse',
                  ),
                  13 => 
                  array (
                    'key' => 'Oil',
                    'value' => 'Oil',
                  ),
                  14 => 
                  array (
                    'key' => 'Pads',
                    'value' => 'Pads',
                  ),
                  15 => 
                  array (
                    'key' => 'Patch',
                    'value' => 'Patch',
                  ),
                  16 => 
                  array (
                    'key' => 'Pressed Powder',
                    'value' => 'Pressed Powder',
                  ),
                  17 => 
                  array (
                    'key' => 'Pencil',
                    'value' => 'Pencil',
                  ),
                  18 => 
                  array (
                    'key' => 'Pomade',
                    'value' => 'Pomade',
                  ),
                  19 => 
                  array (
                    'key' => 'Rollerball',
                    'value' => 'Rollerball',
                  ),
                  20 => 
                  array (
                    'key' => 'Scrub',
                    'value' => 'Scrub',
                  ),
                  21 => 
                  array (
                    'key' => 'Serum',
                    'value' => 'Serum',
                  ),
                  22 => 
                  array (
                    'key' => 'Spray',
                    'value' => 'Spray',
                  ),
                  23 => 
                  array (
                    'key' => 'Sticker',
                    'value' => 'Sticker',
                  ),
                  24 => 
                  array (
                    'key' => 'Stick',
                    'value' => 'Stick',
                  ),
                  25 => 
                  array (
                    'key' => 'Strips',
                    'value' => 'Strips',
                  ),
                  26 => 
                  array (
                    'key' => 'Wax',
                    'value' => 'Wax',
                  ),
                  27 => 
                  array (
                    'key' => 'Wipes',
                    'value' => 'Wipes',
                  ),
                ),
                 'width' => '',
                 'height' => '',
                 'maxItems' => '',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'queryColumnType' => 'text',
                 'columnType' => 'text',
                 'phpdocType' => 'array',
                 'name' => 'formulation',
                 'title' => 'Formulation',
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
              9 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                 'fieldtype' => 'multiselect',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'Mens',
                    'value' => 'Mens',
                  ),
                  1 => 
                  array (
                    'key' => 'Unisex',
                    'value' => 'Unisex',
                  ),
                  2 => 
                  array (
                    'key' => 'Womens',
                    'value' => 'Womens',
                  ),
                ),
                 'width' => '',
                 'height' => '',
                 'maxItems' => '',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'queryColumnType' => 'text',
                 'columnType' => 'text',
                 'phpdocType' => 'array',
                 'name' => 'gender',
                 'title' => 'Gender',
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
              10 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                 'fieldtype' => 'multiselect',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'Acetone Free',
                    'value' => 'Acetone Free',
                  ),
                  1 => 
                  array (
                    'key' => 'All Natural',
                    'value' => 'All Natural',
                  ),
                  2 => 
                  array (
                    'key' => 'BPA-Free Plastic',
                    'value' => 'BPA-Free Plastic',
                  ),
                  3 => 
                  array (
                    'key' => 'Fragrance Free',
                    'value' => 'Fragrance Free',
                  ),
                  4 => 
                  array (
                    'key' => 'Gluten Free',
                    'value' => 'Gluten Free',
                  ),
                  5 => 
                  array (
                    'key' => 'Kosher',
                    'value' => 'Kosher',
                  ),
                  6 => 
                  array (
                    'key' => 'Mineral',
                    'value' => 'Mineral',
                  ),
                  7 => 
                  array (
                    'key' => 'Mineral Oil Free',
                    'value' => 'Mineral Oil Free',
                  ),
                  8 => 
                  array (
                    'key' => 'No Nuts',
                    'value' => 'No Nuts',
                  ),
                  9 => 
                  array (
                    'key' => 'Non Dairy',
                    'value' => 'Non Dairy',
                  ),
                  10 => 
                  array (
                    'key' => 'Not Tested On Animals',
                    'value' => 'Not Tested On Animals',
                  ),
                  11 => 
                  array (
                    'key' => 'Oil Free',
                    'value' => 'Oil Free',
                  ),
                  12 => 
                  array (
                    'key' => 'Organic',
                    'value' => 'Organic',
                  ),
                  13 => 
                  array (
                    'key' => 'Paraben Free',
                    'value' => 'Paraben Free',
                  ),
                  14 => 
                  array (
                    'key' => 'Petrochemical Free',
                    'value' => 'Petrochemical Free',
                  ),
                  15 => 
                  array (
                    'key' => 'Phthalate Free',
                    'value' => 'Phthalate Free',
                  ),
                  16 => 
                  array (
                    'key' => 'Retinoids Free',
                    'value' => 'Retinoids Free',
                  ),
                  17 => 
                  array (
                    'key' => 'Salicylic acid',
                    'value' => 'Salicylic acid',
                  ),
                  18 => 
                  array (
                    'key' => 'Silicone Free',
                    'value' => 'Silicone Free',
                  ),
                  19 => 
                  array (
                    'key' => 'Sulfate Free',
                    'value' => 'Sulfate Free',
                  ),
                  20 => 
                  array (
                    'key' => 'Synthetic Fragrance Free',
                    'value' => 'Synthetic Fragrance Free',
                  ),
                  21 => 
                  array (
                    'key' => 'Vegan',
                    'value' => 'Vegan',
                  ),
                  22 => 
                  array (
                    'key' => 'Vegetarian',
                    'value' => 'Vegetarian',
                  ),
                  23 => 
                  array (
                    'key' => '3-Free',
                    'value' => '3-Free',
                  ),
                  24 => 
                  array (
                    'key' => '5-Free',
                    'value' => '5-Free',
                  ),
                ),
                 'width' => '',
                 'height' => '',
                 'maxItems' => '',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'queryColumnType' => 'text',
                 'columnType' => 'text',
                 'phpdocType' => 'array',
                 'name' => 'ingredientPreference',
                 'title' => 'Ingredient Preference',
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
              11 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                 'fieldtype' => 'multiselect',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'All',
                    'value' => 'All',
                  ),
                  1 => 
                  array (
                    'key' => 'Combination',
                    'value' => 'Combination',
                  ),
                  2 => 
                  array (
                    'key' => 'Dry',
                    'value' => 'Dry',
                  ),
                  3 => 
                  array (
                    'key' => 'Normal',
                    'value' => 'Normal',
                  ),
                  4 => 
                  array (
                    'key' => 'Oily',
                    'value' => 'Oily',
                  ),
                  5 => 
                  array (
                    'key' => 'Sensitive',
                    'value' => 'Sensitive',
                  ),
                ),
                 'width' => '',
                 'height' => '',
                 'maxItems' => '',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'queryColumnType' => 'text',
                 'columnType' => 'text',
                 'phpdocType' => 'array',
                 'name' => 'skinType',
                 'title' => 'Skin Type',
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
              12 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                 'fieldtype' => 'multiselect',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'Full',
                    'value' => 'Full',
                  ),
                  1 => 
                  array (
                    'key' => 'Light',
                    'value' => 'Light',
                  ),
                  2 => 
                  array (
                    'key' => 'Medium',
                    'value' => 'Medium',
                  ),
                  3 => 
                  array (
                    'key' => 'Sheer',
                    'value' => 'Sheer',
                  ),
                ),
                 'width' => '',
                 'height' => '',
                 'maxItems' => '',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'queryColumnType' => 'text',
                 'columnType' => 'text',
                 'phpdocType' => 'array',
                 'name' => 'coerage',
                 'title' => 'Coerage',
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
              13 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                 'fieldtype' => 'multiselect',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'Glitter',
                    'value' => 'Glitter',
                  ),
                  1 => 
                  array (
                    'key' => 'Glossy',
                    'value' => 'Glossy',
                  ),
                  2 => 
                  array (
                    'key' => 'Lengthens',
                    'value' => 'Lengthens',
                  ),
                  3 => 
                  array (
                    'key' => 'Luminous',
                    'value' => 'Luminous',
                  ),
                  4 => 
                  array (
                    'key' => 'Matte',
                    'value' => 'Matte',
                  ),
                  5 => 
                  array (
                    'key' => 'Natural',
                    'value' => 'Natural',
                  ),
                  6 => 
                  array (
                    'key' => 'Satin',
                    'value' => 'Satin',
                  ),
                  7 => 
                  array (
                    'key' => 'Shimmer',
                    'value' => 'Shimmer',
                  ),
                  8 => 
                  array (
                    'key' => 'Textured',
                    'value' => 'Textured',
                  ),
                  9 => 
                  array (
                    'key' => 'Thickens',
                    'value' => 'Thickens',
                  ),
                  10 => 
                  array (
                    'key' => 'Volumizing',
                    'value' => 'Volumizing',
                  ),
                  11 => 
                  array (
                    'key' => 'Waterproof/Water resistant',
                    'value' => 'Waterproof/Water resistant',
                  ),
                ),
                 'width' => '',
                 'height' => '',
                 'maxItems' => '',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'queryColumnType' => 'text',
                 'columnType' => 'text',
                 'phpdocType' => 'array',
                 'name' => 'finish',
                 'title' => 'Finish',
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
              14 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                 'fieldtype' => 'multiselect',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'Dark',
                    'value' => 'Dark',
                  ),
                  1 => 
                  array (
                    'key' => 'Fair',
                    'value' => 'Fair',
                  ),
                  2 => 
                  array (
                    'key' => 'Light',
                    'value' => 'Light',
                  ),
                  3 => 
                  array (
                    'key' => 'Light-Medium',
                    'value' => 'Light-Medium',
                  ),
                  4 => 
                  array (
                    'key' => 'Medium',
                    'value' => 'Medium',
                  ),
                  5 => 
                  array (
                    'key' => 'Medium-Dark',
                    'value' => 'Medium-Dark',
                  ),
                ),
                 'width' => '',
                 'height' => '',
                 'maxItems' => '',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'queryColumnType' => 'text',
                 'columnType' => 'text',
                 'phpdocType' => 'array',
                 'name' => 'skinShade',
                 'title' => 'Skin Shade',
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
              15 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                 'fieldtype' => 'multiselect',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'Black',
                    'value' => 'Black',
                  ),
                  1 => 
                  array (
                    'key' => 'Blue',
                    'value' => 'Blue',
                  ),
                  2 => 
                  array (
                    'key' => 'Brown',
                    'value' => 'Brown',
                  ),
                  3 => 
                  array (
                    'key' => 'Clear',
                    'value' => 'Clear',
                  ),
                  4 => 
                  array (
                    'key' => 'Gray',
                    'value' => 'Gray',
                  ),
                  5 => 
                  array (
                    'key' => 'Gold',
                    'value' => 'Gold',
                  ),
                  6 => 
                  array (
                    'key' => 'Green',
                    'value' => 'Green',
                  ),
                  7 => 
                  array (
                    'key' => 'Metallic',
                    'value' => 'Metallic',
                  ),
                  8 => 
                  array (
                    'key' => 'Multiple',
                    'value' => 'Multiple',
                  ),
                  9 => 
                  array (
                    'key' => 'Orange',
                    'value' => 'Orange',
                  ),
                  10 => 
                  array (
                    'key' => 'Pink',
                    'value' => 'Pink',
                  ),
                  11 => 
                  array (
                    'key' => 'Purple',
                    'value' => 'Purple',
                  ),
                  12 => 
                  array (
                    'key' => 'Red',
                    'value' => 'Red',
                  ),
                  13 => 
                  array (
                    'key' => 'Silver',
                    'value' => 'Silver',
                  ),
                  14 => 
                  array (
                    'key' => 'White',
                    'value' => 'White',
                  ),
                  15 => 
                  array (
                    'key' => 'Yellow',
                    'value' => 'Yellow',
                  ),
                ),
                 'width' => '',
                 'height' => '',
                 'maxItems' => '',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'queryColumnType' => 'text',
                 'columnType' => 'text',
                 'phpdocType' => 'array',
                 'name' => 'itemColor',
                 'title' => 'Item Color',
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
              16 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                 'fieldtype' => 'multiselect',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'All',
                    'value' => 'All',
                  ),
                  1 => 
                  array (
                    'key' => 'Bald or Shaved',
                    'value' => 'Bald or Shaved',
                  ),
                  2 => 
                  array (
                    'key' => 'Buzzcut',
                    'value' => 'Buzzcut',
                  ),
                  3 => 
                  array (
                    'key' => 'Coarse',
                    'value' => 'Coarse',
                  ),
                  4 => 
                  array (
                    'key' => 'Color Treated Hair',
                    'value' => 'Color Treated Hair',
                  ),
                  5 => 
                  array (
                    'key' => 'Curly Hair',
                    'value' => 'Curly Hair',
                  ),
                  6 => 
                  array (
                    'key' => 'Fine Hair',
                    'value' => 'Fine Hair',
                  ),
                  7 => 
                  array (
                    'key' => 'Kinky Hair',
                    'value' => 'Kinky Hair',
                  ),
                  8 => 
                  array (
                    'key' => 'Long Hair',
                    'value' => 'Long Hair',
                  ),
                  9 => 
                  array (
                    'key' => 'Medium Hair',
                    'value' => 'Medium Hair',
                  ),
                  10 => 
                  array (
                    'key' => 'Short Hair',
                    'value' => 'Short Hair',
                  ),
                  11 => 
                  array (
                    'key' => 'Straight Hair',
                    'value' => 'Straight Hair',
                  ),
                  12 => 
                  array (
                    'key' => 'Thick Hair',
                    'value' => 'Thick Hair',
                  ),
                  13 => 
                  array (
                    'key' => 'Thin Hair',
                    'value' => 'Thin Hair',
                  ),
                  14 => 
                  array (
                    'key' => 'Wavy Hair',
                    'value' => 'Wavy Hair',
                  ),
                ),
                 'width' => '',
                 'height' => '',
                 'maxItems' => '',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'queryColumnType' => 'text',
                 'columnType' => 'text',
                 'phpdocType' => 'array',
                 'name' => 'hairType',
                 'title' => 'Hair Type',
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
          4 => 
          Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'fieldtype' => 'panel',
             'labelWidth' => 100,
             'layout' => '',
             'name' => 'relationships',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Relationships',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Objects::__set_state(array(
                 'fieldtype' => 'objects',
                 'width' => '',
                 'height' => '',
                 'maxItems' => '',
                 'queryColumnType' => 'text',
                 'phpdocType' => 'array',
                 'relationType' => true,
                 'lazyLoading' => false,
                 'classes' => 
                array (
                  0 => 
                  array (
                    'classes' => 'BoxandKit',
                  ),
                  1 => 
                  array (
                    'classes' => 'Product',
                  ),
                ),
                 'pathFormatterClass' => '',
                 'name' => 'entityRelationships',
                 'title' => 'Entity Relationships',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'columnType' => NULL,
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
