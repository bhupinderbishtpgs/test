<?php

namespace DataConnectBundle\Model;

use Pimcore\Model\AbstractModel;

/**
 * Tbljobmaster
 */
class Tbljobmaster extends AbstractModel {

    protected $entity;

    public function __construct() {
        
    }

    public function getConnJobDetailsData($conn, $status, $flag = null) {
        try {
            $obj = new self;
            $data = $obj->getDao()->getConnJobDetails($conn, $status, $flag);
            return $data;
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }

    public function addJobData($data, $conn) {
        try {
            $obj = new self;
            $data = $obj->getDao()->addJob($data, $conn);
            return $data;
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }
    
     public function getJobIdData($conn, $status) {
        try {
            $obj = new self;
            $data = $obj->getDao()->getJobId($conn, $status);
            return $data;
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }
    
    public function doJobUpdateData($conn, $update) {
        try {
            $obj = new self;
            $data = $obj->getDao()->doJobUpdate($conn, $update);
            return $data;
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }

}
