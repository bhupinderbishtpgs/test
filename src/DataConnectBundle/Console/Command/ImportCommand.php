<?php

/**
 * ImportCommand
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) 2009-2016 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 * @desc This file is used for import execution
 *
 */

namespace DataConnectBundle\Console\Command;

// use ImportDefinitions\Model\Definition;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
// use Symfony\Component\Console\Helper\ProgressBar;
// use Pimcore\Console\Dumper;
// use DataConnect\Model\Import;
//use DataConnectBundle\Service\DataConnectionService;
use Pimcore\Model\Object\Bridge;
// use DataConnect\Model\DataMappingModel;
// use DataConnect\Service\DataMappingService;
use DataConnectBundle\Service\DataImportService;
use Pimcore\Log\ApplicationLogger;
use DataConnectBundle\Model\Tbljobmaster;
use DataConnectBundle\Model\Tbldataconnection;

/**
 * ImportCommand Class
 */
class ImportCommand {
    

    /**
     * configure command - for import execution
     */
//   protected function configure()
//    {
//        $this->setName('dataconnect:run')
//            ->setDescription('Run DataConnect Service')
//            ->addOption('connection', 'c', InputOption::VALUE_REQUIRED, 'Connection ID')
//            ->addOption('bridge', 'b', InputOption::VALUE_REQUIRED, 'Bridge ID');
//    }

    /**
     * Execute command - from cli
     *
     * @param InputInterface $input            
     * @param OutputInterface $output            
     *
     * @throws \Exception
     *
     * @return int
     */
//    public function execute(InputInterface $input, OutputInterface $output)
//    {
//        try {
//            
//            $conObj = new \DataConnect\Helper\Log();
//            $config = $conObj->getConfig();
//            
//            $log = $config['generatelog'];
//            \Pimcore::getEventManager()->attach(
//                "dataimport.log", function (\Zend_EventManager_Event $e) use($log) {
//                    if ($log === 'true') {
//                        $appLoggerInstance = "data-connect-" . date("Y-m-d");
//                        $info = $e->getTarget();
//                        $logger = ApplicationLogger::getInstance($appLoggerInstance, true);
//                        if ($info['type'] == "log") {
//                            $logger->{$info['type']}("info", $info['msg']);
//                        } else {
//                            $logger->{$info['type']}($info['msg']);
//                        }
//                    }
//                }
//            );
//            
//            $connIds = $input->getOption('conn');
//            
//            $obj = new DataConnectionService();
//            $connections = $obj->getDataConnectionInfo($connIds);
//            if (count($connections)) {
//                foreach ($connections as $key => $con) {
//                    
//                    $bridgeName = $con['bridge_name'];
//                    $list = new Bridge\Listing();
//                    $list->setCondition("BridgeName = '$bridgeName'");
//                    foreach ($list as $o) {
//                        
//                        $bObj = new \DataConnect\Helper\Bridge();
//                        $resp = $bObj->setBridgeAdapter($o);
//                        if ($resp != false) {
//                            // Get mapping details
//                            $conName = $con['con_name'];
//                            
//                            // Start Import
//                            $di = new DataImportService($conName);
//                            
//                            $di->runImport($bObj->db, $con);
//                        } else {
//                            throw new \Exception('Error - bridge adapter configuration.');
//                        }
//                    }
//                }
//            } else {
//                throw new \Exception('Invalid Connection ID.');
//            }
//        } catch (\Exception $excp) {
//            throw new \Exception($excp->getMessage());
//        }
//    }

    /**
     * Execute command - panel
     * 
     * @param string $connection            
     * @return mixed
     * @throws \Exception
     */
    public function webExecute($connection, $status = null) {
        try {
            //ini_set('memory_limit', '-1');
            $userId = \Pimcore\Tool\Authentication::authenticateSession()->getId();
            $conObj = new \DataConnectBundle\Helper\Log();
            $config = $conObj->getConfig();

            //$log = $config['generatelog'];
//            \Pimcore::getEventManager()->attach(
//                "dataimport.log", function (\Zend_EventManager_Event $e) use($log) {
//                    if ($log === 'true') {
//                        $appLoggerInstance = "data-connect-" . date("Y-m-d");
//                        $info = $e->getTarget();
//                        $logger = ApplicationLogger::getInstance($appLoggerInstance, true);
//                        if ($info['type'] == "log") {
//                            $logger->{$info['type']}("info", $info['msg']);
//                        } else {
//                            $logger->{$info['type']}($info['msg']);
//                        }
//                    }
//                }
//            );
//            \Pimcore::getEventManager()->attach(
//                "dataimport.job", function (\Zend_EventManager_Event $e) {
//                        $param = $e->getTarget();
//                        $resource = new \DataConnect\Model\ResourceManager();
//                        
//                        if(isset($param['type'])) {
//                            if($param['type']=='queue') {
//                                $checkQueue = $resource->checkQueueExists($param['data']['job_id'], $$param['data']['source_id']);
//                                if($checkQueue==false) {
//                                    $resource->addQueueorMapping($param['table'],$param['data']);
//                                }
//                            } else if($param['type']=='mapping') {
//                            
//                                unset($param['data']['job_id']);
//                                $checkMapping = $resource->checkMappingExists($param['data']['dest_id']);
//                                if($checkMapping == false) {
//                                    $resource->addQueueorMapping($param['table'],$param['data']);
//                                } else {
//                                    $resource->updateMapping($param['data']['dest_id']);
//                                }
//                                
//                            }else 
//                            if ($param['type'] == 'job') {
//                                $update['last_update'] = date("Y-m-d H:i:s");
//                                $resource->doJobUpdate($param['conName'], $update);
//                            }
//                        }
//                        
//                }
//            );


            $connIds = $connection;
            $obj = new Tbldataconnection();
            $resource = new Tbljobmaster();

            $connections = $obj->getDataConnectionDetails($connIds);
            $success = false;
            if (count($connections)) {

                foreach ($connections as $key => $con) {
                    $update = array();
                    if (!empty($con['bridge_id'])) {
                        $bridgeObj = \Pimcore\Model\Object::getById($con['bridge_id']);
                        $bObj = new \DataConnectBundle\Helper\Bridge();
                        $resp = $bObj->setBridgeAdapter($bridgeObj);
                    }

                    $conName = $con['con_name'];

                    // Start Import
                    $di = new DataImportService($conName);
                    if ($status == 1) {
                        $update['user_id'] = $userId;
                        $jobDetail = $resource->getJobIdData($conName, "in-progress");
                        
                        if (count($jobDetail)) {
                            $job = $jobDetail[0]['job_id'];
                            if ($jobDetail[0]['start_time'] == '0000-00-00 00:00:00') {
                                $update['start'] = date("Y-m-d H:i:s");
                                $resource->doJobUpdateData($conName, $update);
                            }
                        } else {
                            continue 1;
                        }
                    } else {
                        $jobDetail = $resource->getJobIdData($conName, "in-progress");
                        if (count($jobDetail)) {
                            $job = $jobDetail[0]['job_id'];
                            if ($jobDetail[0]['start_time'] == '0000-00-00 00:00:00') {
                                $update['start'] = date("Y-m-d H:i:s");
                                $resource->doJobUpdateData($conName, $update);
                            } else if ($jobDetail[0]['start_time'] != '0000-00-00 00:00:00') {
                                $update['status'] = "in-complete";
                                $update['end'] = date("Y-m-d H:i:s");

                                $resource->doJobUpdateData($conName, $update);
                                $job = substr(microtime(), 2, 6);
                                $insert['job_id'] = $job;
                                $insert['records'] = 0;
                                $insert['user_id'] = $userId;
                                $insert['start'] = date("Y-m-d H:i:s");
                                $resource->addJobData($insert, $conName);
                            }
                        } else {
                            $job = substr(microtime(), 2, 6);
                            $insert['job_id'] = $job;
                            $insert['records'] = 0;
                            $insert['user_id'] = $userId;
                            $insert['start'] = date("Y-m-d H:i:s");
                            $resource->addJobData($insert, $conName);
                        }
                    }
                    if(isset($bObj->db) && !empty($con['bridge_id'])){
                        $ret = $di->runImport($bObj->db, $con, $status, $job);
                    }else{
                        $ret = $di->runImport(null,$con, $status, $job);
                    }
                    
                    if ($ret) {
                        
                        $update['status'] = 'done';
                        $update['end'] = date("Y-m-d H:i:s");
                        $update['last_update'] = date("Y-m-d H:i:s");
                        $resource->doJobUpdateData($conName, $update);
                        $success = true;
                        $msg = "Executed successfully.";
                    } else {
                        $msg = "Error during execution.";
                    }
                    //} else {
                    // throw new \Exception('Error - bridge adapter configuration.');
                    // }
                    // }
                }
                return array('success' => $success, 'msg' => $msg);
            } else {
                return array('success' => false, 'msg' => 'No conections found.');
            }
        } catch (\Exception $excp) {

            return array('success' => $success, 'msg' => $excp->getMessage());
        }
    }

}
