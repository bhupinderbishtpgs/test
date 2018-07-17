<?php

namespace DataConnectBundle\Model;

use Pimcore\Model\AbstractModel;

/**
 * Tblcsvbrick
 */
class Tblcsvbrick extends AbstractModel {

    public function _construct() {
        
    }

    public function save($delimiter = null, $filePath = null) {

        try {
            $obj = new self;
            $data = $obj->getDao()->save($delimiter, $filePath);
            return $data;
        } catch (Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }

    public function checkBrick($delimiter,$filePath) {
        try {
            $obj = new self;
            $data = $obj->getDao()->checkBrick($delimiter,$filePath);
            return $data;
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }
    
    public function getBrickData($brickData) {
        try {
            $obj = new self;
            $data = $obj->getDao()->getBrick($brickData);
            return $data;
        } catch (\Exception $ex) {
            return array(
                'msg' => $ex->getMessage()
            );
        }
    }

}
