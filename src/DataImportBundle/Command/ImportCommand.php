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

namespace DataImportBundle\Command;

use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pimcore\Model\DataObject\Job;
use DataConnectBundle\Model\Tbldataconnection;
use Carbon\Carbon;

/**
 * ImportCommand Class
 */
class ImportCommand extends AbstractCommand
{

    /**
     * configure command - for import execution
     */
    protected function configure()
    {
        $this
            ->setName('dataimport:run')
            ->setDescription('Run DataImport Service')
            ->addOption('queue', 'j', InputOption::VALUE_REQUIRED, 'Job Queue ID')
            ->addOption('maintenance', 'm', InputOption::VALUE_OPTIONAL, 'Ignore maintenance');
    }

    /**
     * Execute command - from cli
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $queueId = $input->getOption('queue');
            if ($queueId) {
                $queue = Job::getById($queueId, true);
                if ($queue) {
                    $mapping = $queue->getMapping();
                    $conObj = new Tbldataconnection();
                    $con = $conObj->getConnectionDetail($mapping);
                    if (!empty($con['class']) && !empty($con['keyName'])) {

                        $service = new \DataImportBundle\Service\DataImportService($mapping);

                        $queue->setStatus('inprogress');
                        //$queue->setStartTime(new Carbon());
                      //  $queue->setAttempts(((int)$queue->getAttempts()) + 1);
                        //$queue->save();
                        //print_r($service); die();
                        $return = $service->runImport($queue, $con, $queue->getId());
                        $queue = Job::getById($queueId, true);
                        if ($return['success'] == true) {
                            $queue->setStatus('completed');
                            $queue->setEndTime(new Carbon());
                            $queue->setPublished(true);
                          //  $queue->save();
                        } else {
                            $queue->setStatus('failed');
                            $queue->setEndTime(new Carbon());
                            //$queue->save();
                        }
                        echo $return['msg'];
                    } else {
                        throw new \Exception('Invalid mapping details.');
                    }
                } else {
                    throw new \Exception('Job Queue ID does not exists or invalid.');
                }
            } else {
                throw new \Exception('Invalid Job Queue ID.');
            }
        } catch (\Exception $excp) {
            print_r($excp);
            $this->writeError("\n" . $excp->getMessage() . "\n");
        }
    }
}