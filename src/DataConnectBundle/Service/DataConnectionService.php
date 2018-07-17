<?php

/**
 * DataConnectionService
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
 * @desc This file is used for dataconnection service manipulation
 *
 */
namespace DataConnectBundle\Service;

use DataConnectBundle\Model\Tbldataconnection;

/**
 * DataConnectionService Class
 */
class DataConnectionService
{

    /**
     * Get dataconnection details
     *
     * @param string $connIds            
     * @return array
     * @throws Exception
     */
    public function getDataConnectionInfo($connIds = null)
    {
        try {
            $obj = new Tbldataconnection();
            $conResp = $obj->getDataConnectionDetails($connIds);
            if ($conResp) {
                return $conResp;
            } else {
                return array();
            }
        } catch (\Exception $ex) {
            return array(
                $ex->getMessage()
            );
        }
    }
}
