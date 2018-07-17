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
 * @ Functional Description: Start file of AppBundle
 * @Classname: AppBundle class
 * @author: PGS
 */

namespace AppBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class AppBundle extends AbstractPimcoreBundle {

    /**
     * @ Functional Description : To add Js files for override 
     * @return null
     */
    public function getJsPaths() {
        return [
            '/bundles/app/js/pimcore/objectBrickHideRemoveButton.js',
        ];
    }

}
