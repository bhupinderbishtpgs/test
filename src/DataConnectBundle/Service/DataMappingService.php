<?php
/**
 * DataMappingService
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
 * @desc This file is used data mapping service
 *
 */
namespace DataConnectBundle\Service;

use DataConnectBundle\Model\Tblcolumnconnectiondetails;

/**
 * DataMappingService Class
 */
class DataMappingService
{

    /**
     *
     * @var array
     */
    public $mapping;

    /**
     *
     * @var string
     */
    public $conName;

    /**
     *
     * @var object
     */
    public $mappingObj;

    /**
     *
     * @var array
     */
    public $supportedFieldTypes;

    /**
     * initialize
     *
     * @param string $conName            
     */
    public function __construct($conName)
    {
        $this->supportedFieldTypes = array(
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
        );
        
        $this->setMappingName($conName);
        $this->mappingObj = new Tblcolumnconnectiondetails();
        $this->setMapping();
    }

    /**
     * Set mapping connection name
     *
     * @param string $conName            
     */
    protected function setMappingName($conName)
    {
        $this->conName = $conName;
    }

    /**
     * Set mapping details
     */
    protected function setMapping()
    {
        $this->mapping = $this->mappingObj->getDataMappingDetails($this->conName);
    }

    /**
     * Get mapping connection name
     *
     * @return string
     */
    protected function getMappingName()
    {
        return $this->conName;
    }

    /**
     * Get mapping details
     *
     * @return array
     */
    protected function getMapping()
    {
        return $this->mapping;
    }
}