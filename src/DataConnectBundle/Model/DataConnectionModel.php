<?php

/**
 * DataConnectionModel
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
 * @desc This file is used for data connector model definition
 *
 */

namespace DataConnectBundle\Model;

use Pimcore\Db;

/**
 * DataConnectionModel Class
 */
class DataConnectionModel  {

    /**
     *
     * @var string
     */
    public $table;

    /**
     *
     * @var object 
     */
    public $db;
    
    /**
     * initialize
     */
    public function __construct() {
    
        $this->db = Db::get();
        $this->table = "tblDataConnection";
    }

    /**
     * Get data connection details
     * 
     * @param string $connIds            
     * @return mixed
     * @throws Exception
     */
    public function getDataConnectionDetails($connIds = null) {
        try {

            $sql = "select con_name, bridge_name, class_name, sqlText, exec_order, data_key_name,overwrite,language_key,datasql,target_path,obj_key_prefix
                    from $this->table";
            $sql .= " where data_key_name is not null";
            if ($connIds) {
                $sql .= " and find_in_set(BINARY con_name,'" . $connIds . "')";
            }
            $sql .= " order by exec_order";
            $resp = $this->db->fetchAll($sql);

            if (count($resp) > 0) {
                return $resp;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getParentObjects($classId) {
        try {
            $table = "object_" . $classId;
            $sql = "SELECT pobj.o_type as parent_type,obj.*";
            $sql.=" FROM `$table` as obj join `objects` as pobj ";
            $sql.=" on (`obj`.`o_parentId`=`pobj`.`o_id`)";
            $sql.=" WHERE `obj`.`o_classId` = '$classId' and pobj.o_type='object' and obj.o_published='1'";
            
            $resp = $this->db->fetchAll($sql);

            if (count($resp) > 0) {
                return $resp;
            } else {
                return [];
            }
        } catch (\Exception $ex) {
            return [];
        }
    }

}
