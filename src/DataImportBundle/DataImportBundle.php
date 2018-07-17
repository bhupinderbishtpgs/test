<?php

namespace DataImportBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class DataImportBundle extends AbstractPimcoreBundle
{

    /**
     * @return array|\Pimcore\Routing\RouteReferenceInterface[]|string[]
     */
    public function getJsPaths()
    {
        return [
            '/bundles/dataimport/js/pimcore/startup.js'
        ];
    }

    /**
     * @return mixed
     */
    public static function getConfig()
    {
        $path = PIMCORE_PROJECT_ROOT . "/src/DataImportBundle/plugin.xml";
        $obj = new \Symfony\Component\Serializer\Encoder\XmlEncoder();
        $data = $obj->decode(file_get_contents($path), 'XML');
        return $data['plugin'];
    }

}
