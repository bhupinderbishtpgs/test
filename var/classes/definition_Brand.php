<?php 

/** 
* Generated at: 2018-07-03T11:28:48+02:00
* Inheritance: no
* Variants: no
* Changed by: admin (2)
* IP: 127.0.0.1


Fields Summary: 
- name [input]
- displayName [input]
- vendor [objectsMetadata]
- magentoShopCategory [numeric]
- magentoMenCategory [numeric]
- vertical [select]
- division [select]
- subdivision [select]
- importerOnRecord [booleanSelect]
*/ 


return Pimcore\Model\DataObject\ClassDefinition::__set_state(array(
   'name' => 'Brand',
   'description' => '',
   'creationDate' => 0,
   'modificationDate' => 1530610128,
   'userOwner' => 0,
   'userModification' => 2,
   'parentClass' => '',
   'useTraits' => '',
   'allowInherit' => false,
   'allowVariants' => false,
   'showVariants' => false,
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
     'collapsible' => NULL,
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
          Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'fieldtype' => 'panel',
             'labelWidth' => 100,
             'layout' => NULL,
             'name' => 'baseInfo',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Base Info',
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
                 'name' => 'name',
                 'title' => 'Name',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'fieldtype' => 'input',
                 'width' => NULL,
                 'queryColumnType' => 'varchar',
                 'columnType' => 'varchar',
                 'columnLength' => 190,
                 'phpdocType' => 'string',
                 'regex' => '',
                 'unique' => false,
                 'name' => 'displayName',
                 'title' => 'Display Name',
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
                 'allowedClassId' => 45,
                 'visibleFields' => 'displayName',
                 'columns' => 
                array (
                ),
                 'fieldtype' => 'objectsMetadata',
                 'phpdocType' => '\\Pimcore\\Model\\DataObject\\Data\\ObjectMetadata[]',
                 'width' => '',
                 'height' => '',
                 'maxItems' => 1,
                 'queryColumnType' => 'text',
                 'relationType' => true,
                 'lazyLoading' => false,
                 'classes' => 
                array (
                  0 => 
                  array (
                    'classes' => 'Vendor',
                  ),
                ),
                 'pathFormatterClass' => '',
                 'name' => 'vendor',
                 'title' => 'Vendor',
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
            ),
             'locked' => false,
          )),
          1 => 
          Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'fieldtype' => 'panel',
             'labelWidth' => 100,
             'layout' => NULL,
             'name' => 'ecommerce',
             'type' => NULL,
             'region' => NULL,
             'title' => 'E-commerce',
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
                 'integer' => true,
                 'unsigned' => false,
                 'minValue' => NULL,
                 'maxValue' => NULL,
                 'unique' => false,
                 'decimalSize' => NULL,
                 'decimalPrecision' => NULL,
                 'name' => 'magentoShopCategory',
                 'title' => 'Magento Shop Category ID',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Numeric::__set_state(array(
                 'fieldtype' => 'numeric',
                 'width' => 150,
                 'defaultValue' => NULL,
                 'queryColumnType' => 'double',
                 'columnType' => 'double',
                 'phpdocType' => 'float',
                 'integer' => true,
                 'unsigned' => false,
                 'minValue' => NULL,
                 'maxValue' => NULL,
                 'unique' => false,
                 'decimalSize' => NULL,
                 'decimalPrecision' => NULL,
                 'name' => 'magentoMenCategory',
                 'title' => 'Magento Men Category ID',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                 'fieldtype' => 'select',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'Men',
                    'value' => 'men',
                  ),
                  1 => 
                  array (
                    'key' => 'Women',
                    'value' => 'women',
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
                 'name' => 'vertical',
                 'title' => 'Vertical',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                 'fieldtype' => 'select',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'Gift Cards',
                    'value' => 'Gift Cards',
                  ),
                  1 => 
                  array (
                    'key' => 'Keychains',
                    'value' => 'Keychains',
                  ),
                  2 => 
                  array (
                    'key' => 'MSP',
                    'value' => 'MSP',
                  ),
                  3 => 
                  array (
                    'key' => 'Men LTE',
                    'value' => 'men-LTE',
                  ),
                  4 => 
                  array (
                    'key' => 'Men Grooming',
                    'value' => 'men-grooming',
                  ),
                  5 => 
                  array (
                    'key' => 'Men Lifestyle',
                    'value' => 'men-lifestyle',
                  ),
                  6 => 
                  array (
                    'key' => 'Women LTE',
                    'value' => 'women-LTE',
                  ),
                  7 => 
                  array (
                    'key' => 'Women Hair and Fragrance',
                    'value' => 'women-hair and fragrance',
                  ),
                  8 => 
                  array (
                    'key' => 'Women Lifestyle',
                    'value' => 'women-lifestyle',
                  ),
                  9 => 
                  array (
                    'key' => 'Women Makeup',
                    'value' => 'women-makeup',
                  ),
                  10 => 
                  array (
                    'key' => 'Women Skincare',
                    'value' => 'women-skincare',
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
                 'name' => 'division',
                 'title' => 'Division',
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
                    'key' => 'Accessories & Tools',
                    'value' => ' Accessories & Tools',
                  ),
                  1 => 
                  array (
                    'key' => 'Active & Outdoor',
                    'value' => ' Active & Outdoor',
                  ),
                  2 => 
                  array (
                    'key' => 'Active & Outdoor',
                    'value' => ' Active & Outdoor',
                  ),
                  3 => 
                  array (
                    'key' => 'Apparel',
                    'value' => ' Apparel',
                  ),
                  4 => 
                  array (
                    'key' => 'Apparel & Underwear',
                    'value' => ' Apparel & Underwear',
                  ),
                  5 => 
                  array (
                    'key' => 'Audio',
                    'value' => ' Audio',
                  ),
                  6 => 
                  array (
                    'key' => 'Bags & Pouches',
                    'value' => ' Bags & Pouches',
                  ),
                  7 => 
                  array (
                    'key' => 'Bags & Travel',
                    'value' => ' Bags & Travel',
                  ),
                  8 => 
                  array (
                    'key' => 'Bar & Entertaining (M)',
                    'value' => ' Bar & Entertaining (M)',
                  ),
                  9 => 
                  array (
                    'key' => 'Bar & Entertaining (W)',
                    'value' => ' Bar & Entertaining (W)',
                  ),
                  10 => 
                  array (
                    'key' => 'Birchbox Goods',
                    'value' => ' Birchbox Goods',
                  ),
                  11 => 
                  array (
                    'key' => 'Body',
                    'value' => ' Body',
                  ),
                  12 => 
                  array (
                    'key' => 'Chocolate & Snacks',
                    'value' => ' Chocolate & Snacks',
                  ),
                  13 => 
                  array (
                    'key' => 'Coffee & Tea (M)',
                    'value' => ' Coffee & Tea (M)',
                  ),
                  14 => 
                  array (
                    'key' => 'Coffee & Tea (W)',
                    'value' => ' Coffee & Tea (W)',
                  ),
                  15 => 
                  array (
                    'key' => 'Cooking & Baking',
                    'value' => ' Cooking & Baking',
                  ),
                  16 => 
                  array (
                    'key' => 'D_cor',
                    'value' => ' D_cor',
                  ),
                  17 => 
                  array (
                    'key' => 'Fragrance',
                    'value' => ' Fragrance',
                  ),
                  18 => 
                  array (
                    'key' => 'Gadgets & Tech Accessories',
                    'value' => ' Gadgets & Tech Accessories',
                  ),
                  19 => 
                  array (
                    'key' => 'Games',
                    'value' => ' Games',
                  ),
                  20 => 
                  array (
                    'key' => 'Grooming',
                    'value' => ' Grooming',
                  ),
                  21 => 
                  array (
                    'key' => 'Grooming Tools',
                    'value' => ' Grooming Tools',
                  ),
                  22 => 
                  array (
                    'key' => 'Hair',
                    'value' => ' Hair',
                  ),
                  23 => 
                  array (
                    'key' => 'Hair Accessories',
                    'value' => ' Hair Accessories',
                  ),
                  24 => 
                  array (
                    'key' => 'Hats Gloves & Scarves',
                    'value' => ' Hats Gloves & Scarves',
                  ),
                  25 => 
                  array (
                    'key' => 'Health & Wellness (M)',
                    'value' => ' Health & Wellness (W)',
                  ),
                  26 => 
                  array (
                    'key' => 'Home D_cor',
                    'value' => ' Home D_cor',
                  ),
                  27 => 
                  array (
                    'key' => 'Home Fragrance & Candles',
                    'value' => 'Home Fragrance & Candles',
                  ),
                  28 => 
                  array (
                    'key' => 'Jewelry & Watches',
                    'value' => 'Jewelry & Watches',
                  ),
                  29 => 
                  array (
                    'key' => 'Laundry & Cleaning',
                    'value' => 'Laundry & Cleaning',
                  ),
                  30 => 
                  array (
                    'key' => 'LTE',
                    'value' => 'LTE',
                  ),
                  31 => 
                  array (
                    'key' => 'Makeup',
                    'value' => 'Makeup',
                  ),
                  32 => 
                  array (
                    'key' => 'Men\'s Fragrance',
                    'value' => 'Men\'s Fragrance',
                  ),
                  33 => 
                  array (
                    'key' => 'MSP',
                    'value' => 'MSP',
                  ),
                  34 => 
                  array (
                    'key' => 'Nail',
                    'value' => ' Nail',
                  ),
                  35 => 
                  array (
                    'key' => 'Neckwear & Pocket Squares',
                    'value' => ' Neckwear & Pocket Squares',
                  ),
                  36 => 
                  array (
                    'key' => 'Other',
                    'value' => ' Other',
                  ),
                  37 => 
                  array (
                    'key' => 'Phone Cases',
                    'value' => ' Phone Cases',
                  ),
                  38 => 
                  array (
                    'key' => 'Prep & Cook',
                    'value' => ' Prep & Cook',
                  ),
                  39 => 
                  array (
                    'key' => 'Seasonal Weather Accessories',
                    'value' => ' Seasonal Weather Accessories',
                  ),
                  40 => 
                  array (
                    'key' => 'Shoes & Socks',
                    'value' => ' Shoes & Socks',
                  ),
                  41 => 
                  array (
                    'key' => 'Skincare',
                    'value' => ' Skincare',
                  ),
                  42 => 
                  array (
                    'key' => 'Stationery & Desk Supplies',
                    'value' => ' Stationery & Desk Supplies',
                  ),
                  43 => 
                  array (
                    'key' => 'Stationery & Paper Goods',
                    'value' => ' Stationery & Paper Goods',
                  ),
                  44 => 
                  array (
                    'key' => 'Tech Accessories',
                    'value' => ' Tech Accessories',
                  ),
                  45 => 
                  array (
                    'key' => 'Wallets & Money Clips',
                    'value' => ' Wallets & Money Clips',
                  ),
                  46 => 
                  array (
                    'key' => 'Watches & Accessories',
                    'value' => ' Watches & Accessories',
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
                 'name' => 'subdivision',
                 'title' => 'Sub Division',
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
              Pimcore\Model\DataObject\ClassDefinition\Data\BooleanSelect::__set_state(array(
                 'fieldtype' => 'booleanSelect',
                 'yesLabel' => 'Yes',
                 'noLabel' => 'No',
                 'emptyLabel' => 'empty',
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'empty',
                    'value' => 0,
                  ),
                  1 => 
                  array (
                    'key' => 'Yes',
                    'value' => 1,
                  ),
                  2 => 
                  array (
                    'key' => 'No',
                    'value' => -1,
                  ),
                ),
                 'width' => '',
                 'queryColumnType' => 'tinyint(1) null',
                 'columnType' => 'tinyint(1) null',
                 'phpdocType' => 'boolean',
                 'name' => 'importerOnRecord',
                 'title' => 'Importer On Record',
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
     'locked' => NULL,
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
