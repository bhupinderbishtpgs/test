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
 * @ Functional Description: This class is responsible for maintaining work-flow permissions for different users on different  work-flow state/status 
 * @Classname: WorkflowPermissionListener class
 * @author: PGS
 */

namespace WorkflowBundle\EventListener;

use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\Workflow;
use WorkflowBundle\Model\WorkflowDetail;
use WorkflowBundle\Util\UserPermission;

class WorkflowPermissionListener {

    /**
     * @Subject: Pimcore\Event\Model\DataObjectEvent
     * @var object
     * @param DataObjectEvent $e
     * @return json
     */
    public function userRoleHandling(DataObjectEvent $e) {
        if (!\Pimcore\Tool\Admin::getCurrentUser()->isAdmin()) {
            $objectpreUpdate = $e->getObject();
            $objectId = $objectpreUpdate->getId();
            $objectsWorkflow = new WorkflowDetail();
            $workflowData = $objectsWorkflow->getObjectWorkflow($objectId);
            //Skip workflow when first time creating object
            if(empty($workflowData)) {
                return true;
            }
            $userhandler = new UserPermission();
      
            if ($workflowData) {
                $canPerformAction = $userhandler->canPerfomAction($workflowData[0]);
            }
            if ($canPerformAction != "TRUE") {
                $failureMsg = array("success" => false, "message" => "Permission Error: Object can not be saved.");
                echo json_encode($failureMsg);
                exit();
            }
        }
    }

}
