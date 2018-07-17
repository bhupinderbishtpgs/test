<?php

namespace DataImportBundle\Controller;

use Pimcore\Controller\FrontendController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends FrontendController
{
    /**
     * @Route("/data_import")
     */
    public function indexAction(Request $request)
    {
        return new Response('Hello world from data_import');
    }
}
