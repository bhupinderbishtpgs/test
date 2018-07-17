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
 * @ Functional Description: This class is responsible for performing required tasks while work-flow actions are triggered 
 * @Classname: WorkflowActionListener class
 * @author: PGS
 */

namespace WorkflowBundle\EventListener;

use Pimcore\Event\Model\WorkflowEvent;

class WorkflowActionListener {

    /**
     * @Functional Description : To publish product
     *  @param WorkflowEvent $event
     */
    public function publishData(WorkflowEvent $event) {
        self::setPublishStatus($event, true);
    }

    /**
     * @Functional Description : To un-publish product
     * @param WorkflowEvent $event
     */
    public function unpublishData(WorkflowEvent $event) {
        self::setPublishStatus($event, false);
    }

    /**
     * @Functional Description : To Publish/Un-Publish object on the bases of passed parameter $setPublish
     * @param WorkflowEvent $event
     * @param boolen $setPublish
     */
    public static function setPublishStatus(WorkflowEvent $event, $setPublish = false) {
        \Pimcore\Model\Version::disable(); // to disable versioning for the current process
        $dataObj = $event->getWorkflowManager()->getElement();
        $dataObj->setPublished($setPublish);
        $dataObj->save();
        \Pimcore\Model\Version::enable(); // to enable versioning for the current process
    }

}
