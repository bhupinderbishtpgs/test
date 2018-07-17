<?php

namespace DataConnectBundle\Model;

use Pimcore\Model\AbstractModel;

/**
 * Tbljobmaster
 */
class Tblsrcdestmapping extends AbstractModel {

    protected $entity;

    public function __construct() {
        
    }

    public function checkMappingExists($dest_id) {
        try {
            $obj = new self;
            $data = $obj->getDao()->checkMappingExists($dest_id);
            return $data;
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }

}
