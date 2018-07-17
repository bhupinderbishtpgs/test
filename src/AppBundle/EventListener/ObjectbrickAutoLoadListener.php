<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 */
/**
 * @ Functional Description: This is EventListner class to listen all kinds of events 
 * @Classname: ObjectbrickAutoLoadListener class
 * @author: PGS
 */

namespace AppBundle\EventListener;

use Pimcore\Event\Model\DataObjectEvent;
use \Pimcore\Model\DataObject\Objectbrick;

class ObjectbrickAutoLoadListener {

    CONST ALLOWED_CLASSES = array('Product', 'BoxandKit', 'Component');

    static $className;
    static $object;

    /**
     * @ Functional Description: Execute to check required conditions
     * @return boolean
     */
    private static function preRequiredCheck() {
        $object = self::$object;
        if ($object->getType() != 'object') {
            return true;
        }
        self::$className = $object->getClassName();
        if (in_array(self::$className, self::ALLOWED_CLASSES)) {
            return false;
        }
        return true;
    }

    /**
     * @Events: Pimcore\Event\Model\DataObjectEvent
     * @param DataObjectEvent $e
     * @return boolean
     */
    public function attachBrick(DataObjectEvent $e) {
        try {
            self::$object = $e->getObject();
            $object = self::$object;      
            if (self::preRequiredCheck()) {
                return true;
            }
            $className = self::$className;
            if ($className == 'Product') {
                $this->setAdditionalAttributes($object);
            } elseif ($className == 'BoxandKit') {
                $this->setAdditionalAttributes($object);
                $this->setProductSpecificAttributes($object, "BoxandKit");
            } elseif ($className == 'Component') {
                $this->setProductSpecificAttributes($object, "Component");
            }
            $this->setProductCommonAttributes($object);
            $object->save();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * @Subject: Pimcore\Event\Model\DataObject
     * @param type $object
     * @return null
     */
    public function setAdditionalAttributes($object) {
        $brick = new Objectbrick\Data\AdditionalAttributes($object);
        $object->getAdditionalAttributes()->setAdditionalAttributes($brick);
    }

    /**
     * @Subject: Pimcore\Event\Model\DataObject
     * @param type $object
     * @return null
     */
    public function setProductSpecificAttributes($object, $method) {
        $setter = "set".$method;
        $class =  "\\Pimcore\\Model\\DataObject\\Objectbrick\\Data\\".$method;
        $brickProductSpecific = new $class($object);
        $object->getProductSpecificAttributes()->$setter($brickProductSpecific);
    }

    /**
     * @Subject: Pimcore\Event\Model\DataObject
     * @param type $object
     * @return null
     */
    public function setProductCommonAttributes($object) {
        $brick = new Objectbrick\Data\CommonAttributes($object);
        $object->getProductCommonAttributes()->setCommonAttributes($brick);
    }

    /**
     * Subject: Pimcore\Event\Model\DataObject
     * @param DataObjectEvent $e
     * @return boolean|json
     */
    public function checkSubclassMap(DataObjectEvent $e) {
        self::$object = $e->getObject();
        if (self::preRequiredCheck()) {
            return true;
        }
        $commonAttribute = self::$object->getProductCommonAttributes()->getCommonAttributes();
        if (is_null($commonAttribute)) {
            return true;
        }
        $subClasses = self::$object->getProductCommonAttributes()->getCommonAttributes()->getSubclass();
        if (!empty($subClasses)) {
            foreach ($subClasses as $singleClass) {
                if ($singleClass->getObject()->getClassificationType() != "subclass") {
                    $success = array("success" => false, "message" => "Please map Subclass type object with Subclass attribute. Currently " . $singleClass->getObject()->getClassificationType() . " type object is mapped.");
                    echo json_encode($success);
                    exit();
                }
            }
        }
    }

}
