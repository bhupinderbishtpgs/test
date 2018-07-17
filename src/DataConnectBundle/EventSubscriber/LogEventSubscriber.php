<?php

namespace DataConnectBundle\EventSubscriber;

class LogEventSubscriber {

    public function logEvent($event,$info) {
        $event->log();
    }

}
