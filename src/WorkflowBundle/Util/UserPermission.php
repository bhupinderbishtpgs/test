<?php

/* 
 * This class is used for check current login user permission of workflow based on transitionDefinitions. 
 * 
 */

namespace WorkflowBundle\Util;

use Pimcore\Model\Workflow;

class UserPermission {

    /** Arguments: workflowData Model object
     *  return true or false    
     */
    public function canPerfomAction($workflowData) {
        $user = \Pimcore\Tool\Admin::getCurrentUser();
        $userRole;
        $canPerformAction = "FALSE";
        if (empty($user->getRoles()) || $workflowData['workflowId'] == '') {
            return $canPerformAction;
        }
        foreach ($user->getRoles() as $role) {
            $userRole[] = $role;
        }
        $loginuserId = $user->getId();
        $workflowalldata = Workflow::getById($workflowData['workflowId']);
        
        $transition = $workflowalldata->getObjectVars()['transitionDefinitions'][$workflowData['status']]['validActions'];
        foreach ($transition as $key => $val) {
            $actionItems[] = $key;
        }
        foreach ($workflowalldata->getObjectVars()['actions'] as $key => $value) {
            foreach ($actionItems as $singleItem) {
                if ($value['name'] == $singleItem) {
                    $allusers[] = $value['users'];
                }
            }
        }
        foreach ($allusers as $users) {
            foreach ($users as $uid) {
                if (in_array($uid, $userRole)) {
                    $canPerformAction = "TRUE";
                }
            }
        }
        return $canPerformAction;
    }

}
