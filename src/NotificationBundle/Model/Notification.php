<?php

namespace NotificationBundle\Model;

class Notification {
    
    protected $templatePath = null;
    
    public function setTemplatePath (String $templatePath) {
        $this->templatePath = $templatePath;
    }
    
    //sending the email
    public function sendNotification ($params, $emailAddressArr, $adminEmailAddress = array()) {
        try {
            $mail = new \Pimcore\Mail();
            $mail->addTo($emailAddressArr);
            $mail->setCc($adminEmailAddress);
            $mail->setDocument($this->templatePath);
            $mail->setParams($params);
            $mail->send();
        } catch (Exception $ex) {
            $this->writeError("\n" . $ex->getMessage() . "\n");
        }             
    }
}