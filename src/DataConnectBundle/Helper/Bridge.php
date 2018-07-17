<?php

/**
 * Bridge
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) 2009-2016 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 * @desc This file is used for bridge class manipulation
 *
 */
namespace DataConnect\Helper;

/**
 * Bridge Class
 */
class Bridge
{

    /**
     *
     * @var array
     */
    public $errors = [];

    /**
     *
     * @var object
     */
    public $db = '';

    /**
     * Setup bridge
     * 
     * @param object $config            
     * @return array
     */
    public function setupBridge($config)
    {
        try {
            $this->db = \Zend_Db::factory($config->database);
            
            $resp = $this->db->getConnection();
        } catch (\Exception $e) {
            $msg = "Couldn't establish connection to Server: ".$e->getMessage();
            $this->errors[] = $msg;
        }
        return $this->errors;
    }

    /**
     * Setup bridge adapter
     * 
     * @param object $obj            
     * @return boolean
     */
    public function setBridgeAdapter($obj)
    {
        try {
            if ($obj->getDataSource() == 'Pdo_Mysql') {
                $pdoType = 'mysql';
            } else {
                $pdoType = 'dblib'; // for ms-sql
            }
            // database configuration host/unix socket
            $config = new \Zend_Config(
                array(
                    'database' => array(
                        // Set the Database adapter
                        'adapter' => $obj->getDataSource(), 
                        'params' => array(
                            'host' => $obj->getHostName(),
                            'dbname' => $obj->getDatabaseName(),
                            'username' => $obj->getUsername(),
                            'password' => $obj->getPassword(),
                            'pdoType' => $pdoType,
                            'port' => $obj->getPort()
                        )
                    )
                )
            );
            $error = $this->setupBridge($config);
            if (count($error) > 0) {
                return false;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get bridge adapter
     * 
     * @param object $config            
     * @return object
     */
    public function getBridgeAdapter($config)
    {
        $this->db = \Zend_Db::factory($config->database);
        return $this->db;
    }
}
