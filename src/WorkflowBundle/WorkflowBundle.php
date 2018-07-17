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
 * @ Functional Description: Main file of WorkflowBundle
 * @Classname: WorkflowBundle class
 * @author: PGS
 */

namespace WorkflowBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class WorkflowBundle extends AbstractPimcoreBundle {

    /**
     * @ Functional Description : To add Js files for override 
     * @return null
     */
    public function getJsPaths() {
        return [
            '/bundles/workflow/js/pimcore/actionPanel.js'
        ];
    }
    
        public function getCssPaths()
    {
       return [
            '/bundles/workflow/css/pimcore/actionPanel.css'
        ];
    }


}
