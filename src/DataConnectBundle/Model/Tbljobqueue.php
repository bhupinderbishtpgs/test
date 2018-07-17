<?php

namespace DataConnectBundle\Model;

use Pimcore\Model\AbstractModel;

/**
 * Tbljobmaster
 */
class Tbljobqueue extends AbstractModel {

    protected $entity;

    public function __construct() {
        
    }

    public function checkQueueExists($jobId, $source) {
        try {
            $obj = new self;
            $data = $obj->getDao()->checkQueueExists($jobId, $source);
            return $data;
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }

    public function addQueueorMapping($data) {
        try {
            $obj = new self;
            $data = $obj->getDao()->addQueueorMapping($data);
            return $data;
        } catch (Exception $exc) {
            return array(
                "msg" => $exc->getTraceAsString()
            );
        }
    }

}
