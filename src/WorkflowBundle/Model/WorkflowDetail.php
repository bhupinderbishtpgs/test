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
 * @ Functional Description: This class is used for retrieve workflowId,role,state,status and user actions for corresponding object Id.
 * @Classname: WorkflowDetail class
 * @author: PGS
 */

namespace WorkflowBundle\Model;

use Pimcore\Model\Workflow;
use \Pimcore\Db;

class WorkflowDetail {

    /**
     * @ Functional Description: To get workflow object
     * @param type $objectId
     * @return type 
     */
    public function getObjectWorkflow($objectId) {
        $queryString = "select * from element_workflow_state where cid = ? ";
        $stmt = Db::get()->prepare($queryString);
        $stmt->bindParam(1, $objectId);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

}
