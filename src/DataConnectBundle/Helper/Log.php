<?php
/**
 * Log
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
 * @desc This file is used for log and plugin configuration
 *
 */
namespace DataConnectBundle\Helper;

/**
 * Log Class
 */
class Log
{

    /**
     *
     * @var array
     */
    public $errors = [];

    /**
     * Get plugin configuration
     *
     * @return mixed
     */
    public static function getConfig()
    {
        $configuration = array();
        if (file_exists(PIMCORE_PLUGINS_PATH . "/DataConnect/plugin.xml")) {
            $path = PIMCORE_PLUGINS_PATH . "/DataConnect/plugin.xml";
            $pluginConf = new \Zend_Config_Xml($path);
            $configuration = $pluginConf->plugin->toArray();
            return $configuration;
        } else {
            return false;
        }
    }
    
    public function log($info,$type) {
        
        echo 'Logged'; die;
        
    }
    
}

