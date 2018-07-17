<?php

namespace NotificationBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class NotificationBundle extends AbstractPimcoreBundle
{
    public function getJsPaths()
    {
        return [
            '/bundles/notification/js/pimcore/startup.js'
        ];
    }
}
