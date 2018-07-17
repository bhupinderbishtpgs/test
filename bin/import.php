<?php

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Pimcore\Model\DataObject\Job\Listing;

$kernel = require_once __DIR__ . '/../pimcore/config/startup_cli.php';

$obj = new Listing();
$obj->setCondition("status = ?", 'tobeexecuted');
$obj->setUnpublished(true);
$obj->setLimit(1);
$obj->setOrder('asc');
$obj->setOrderKey('o_creationDate');

$timeout = \Pimcore\Model\WebsiteSetting::getByName('timeout');
if(!is_object($timeout)) {
    $time = 3600;
} else {
    $time = $timeout->getData();
}
try {
    $job = $obj->load();
    if (count($job)) {
        foreach ($job as $run) {
            $process = new Process('php '.__DIR__.'/console dataimport:run -j ' . $run->getId());
            $process->setTimeout($time);
            $process->run();
            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            echo $process->getOutput();
            $process->stop(3);
        }
    } else {
        echo "\n No Jobs Found \n";
    }
} catch (\Exception $ex) {
    echo $ex->getMessage();
}
