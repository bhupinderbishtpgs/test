<?php

/**
 * DataMappingModel
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
 * @desc This file is used for data mapping model definition
 *
 */
namespace DataConnect\Model;

/**
 * DataMappingModel Class
 */
class DataMappingModel extends DataStorage
{

    /**
     *
     * @var string
     */
    public $table;

    /**
     * initialize
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = "tblColumnConnectionDetails";
    }

    /**
     * Get mapping details
     * 
     * @param string $conName            
     * @return mixed
     */
    public function getDataMappingDetails($conName)
    {
        try {
            $sql = "SELECT con_name, name, label, target_col 
                FROM
                    $this->table
                WHERE
                    BINARY con_name = '$conName'";
            
            $resp = $this->db->fetchAll($sql);
            
            if (count($resp) > 0) {
                return $resp;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
