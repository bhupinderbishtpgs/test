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

namespace DataConnectBundle\Event;

/**
 * Log Class
 */
use Symfony\Component\EventDispatcher\Event;

class Log extends Event {

    private $info;

    public function setInfo($info) {
        
        $this->info = $info;
        
    }
    
    public function log() {

        try {

            $logger = \Pimcore::getContainer()->get("pimcore.app_logger");
            $logger->{$this->info['type']}($this->info['msg'], [
                "component" => $this->info['component']
            ]);

            return true;
        } catch (Exception $ex) {
            
        }
    }

}
