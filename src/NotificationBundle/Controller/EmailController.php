<?php

namespace NotificationBundle\Controller;

use Pimcore\Controller\FrontendController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailController extends FrontendController
{
   
    /**
     *  @Route("/email/test")
     */
    public function testAction(Request $request) {
        echo "Test Action";
        die;
    }
}
