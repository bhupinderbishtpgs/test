<?php 

/** 
* Generated at: 2018-07-17T11:08:40+02:00
* Inheritance: yes
* Variants: yes
* Changed by: admin (2)


Fields Summary: 
- productCommonAttributes [objectbricks]
- productType [select]
- productSpecificAttributes [objectbricks]
- groupedProducts [objectsMetadata]
- additionalAttributes [objectbricks]
*/ 


return Pimcore\Model\DataObject\ClassDefinition::__set_state(array(
   'name' => 'Product',
   'description' => '',
   'creationDate' => 0,
   'modificationDate' => 1531818520,
   'userOwner' => 0,
   'userModification' => 2,
   'parentClass' => '',
   'useTraits' => '',
   'allowInherit' => true,
   'allowVariants' => true,
   'showVariants' => true,
   'layoutDefinitions' => 
  Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
     'fieldtype' => 'panel',
     'labelWidth' => 100,
     'layout' => NULL,
     'name' => 'pimcore_root',
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
             'name' => 'productCommonAttributes',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Product Common Attributes',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Objectbricks::__set_state(array(
                 'fieldtype' => 'objectbricks',
                 'phpdocType' => '\\Pimcore\\Model\\DataObject\\Objectbrick',
                 'allowedTypes' => 
                array (
                  0 => 'CommonAttributes',
                ),
                 'maxItems' => '',
                 'name' => 'productCommonAttributes',
                 'title' => 'Product Common Attributes',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'columnType' => NULL,
                 'queryColumnType' => NULL,
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
             'name' => 'productSpecificAttributes',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Product Specific Attributes',
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
                    'key' => 'Bundle',
                    'value' => 'bundle',
                  ),
                  1 => 
                  array (
                    'key' => 'Comp Subscription',
                    'value' => 'comp_subscription',
                  ),
                  2 => 
                  array (
                    'key' => 'Configurable',
                    'value' => 'configurable',
                  ),
                  3 => 
                  array (
                    'key' => 'First Gift Box',
                    'value' => 'firstgiftbox',
                  ),
                  4 => 
                  array (
                    'key' => 'Gift Card',
                    'value' => 'giftcard',
                  ),
                  5 => 
                  array (
                    'key' => 'Gift Subscription',
                    'value' => 'giftsubscription',
                  ),
                  6 => 
                  array (
                    'key' => 'Rebillable Addition',
                    'value' => 'rebillable_addition',
                  ),
                  7 => 
                  array (
                    'key' => 'Rebillable Subscription',
                    'value' => 'rebillable_subscription',
                  ),
                  8 => 
                  array (
                    'key' => 'Redemption',
                    'value' => 'redemption',
                  ),
                  9 => 
                  array (
                    'key' => 'Sample Box',
                    'value' => 'samplebox',
                  ),
                  10 => 
                  array (
                    'key' => 'Simple',
                    'value' => 'simple',
                  ),
                  11 => 
                  array (
                    'key' => 'Subscription',
                    'value' => 'subscription',
                  ),
                  12 => 
                  array (
                    'key' => 'Subscription Configurable',
                    'value' => 'subscription_configurable',
                  ),
                  13 => 
                  array (
                    'key' => 'Subscription Feature',
                    'value' => 'subscription_feature',
                  ),
                  14 => 
                  array (
                    'key' => 'Subscription Simple',
                    'value' => 'subscription_simple',
                  ),
                  15 => 
                  array (
                    'key' => 'Virtual',
                    'value' => 'virtual',
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
                 'name' => 'productType',
                 'title' => 'Product Type',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Objectbricks::__set_state(array(
                 'fieldtype' => 'objectbricks',
                 'phpdocType' => '\\Pimcore\\Model\\DataObject\\Objectbrick',
                 'allowedTypes' => 
                array (
                  0 => 'SimpleProduct',
                ),
                 'maxItems' => '',
                 'name' => 'productSpecificAttributes',
                 'title' => 'Product Specific Attributes',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'columnType' => NULL,
                 'queryColumnType' => NULL,
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
              )),
              2 => 
              Pimcore\Model\DataObject\ClassDefinition\Data\ObjectsMetadata::__set_state(array(
                 'allowedClassId' => 24,
                 'visibleFields' => 'key',
                 'columns' => 
                array (
                  0 => 
                  array (
                    'type' => 'number',
                    'position' => 1,
                    'key' => 'group_id',
                    'id' => 'extModel1469-1',
                    'label' => 'Group Id',
                  ),
                  1 => 
                  array (
                    'type' => 'number',
                    'position' => 2,
                    'key' => 'position_id',
                    'id' => 'extModel1469-2',
                    'label' => 'Position Id',
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'position' => 3,
                    'key' => 'price',
                    'id' => 'extModel2655-1',
                    'label' => 'Price',
                  ),
                  3 => 
                  array (
                    'type' => 'number',
                    'position' => 4,
                    'key' => 'quantity',
                    'id' => 'extModel2655-2',
                    'label' => 'Quantity',
                  ),
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
                 'name' => 'groupedProducts',
                 'title' => 'Grouped Products',
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
                  0 => 'group_id',
                  1 => 'position_id',
                  2 => 'price',
                  3 => 'quantity',
                ),
              )),
            ),
             'locked' => false,
          )),
          2 => 
          Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'fieldtype' => 'panel',
             'labelWidth' => 100,
             'layout' => NULL,
             'name' => 'additionalAttributes',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Additional Attributes',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Objectbricks::__set_state(array(
                 'fieldtype' => 'objectbricks',
                 'phpdocType' => '\\Pimcore\\Model\\DataObject\\Objectbrick',
                 'allowedTypes' => 
                array (
                  0 => 'AdditionalAttributes',
                ),
                 'maxItems' => '',
                 'name' => 'additionalAttributes',
                 'title' => 'Additional Attributes',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'columnType' => NULL,
                 'queryColumnType' => NULL,
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
   'icon' => '',
   'previewUrl' => '',
   'group' => '',
   'showAppLoggerTab' => false,
   'linkGeneratorReference' => '',
   'propertyVisibility' => 
  array (
    'grid' => 
    array (
      'id' => true,
      'path' => true,
      'published' => true,
      'modificationDate' => true,
      'creationDate' => true,
    ),
    'search' => 
    array (
      'id' => true,
      'path' => true,
      'published' => true,
      'modificationDate' => true,
      'creationDate' => true,
    ),
  ),
   'dao' => NULL,
));
