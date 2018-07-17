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
 * @ Functional Description: The default controller of AppBundle 
 * @Controller: AppBundle\Controller
 * @author PGS
 */

namespace AppBundle\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends FrontendController {

    /**
     * @ Functional Requirement: Default action
     * @param Request $request
     * @return null
     */
    public function defaultAction(Request $request) {
        
    }

}
