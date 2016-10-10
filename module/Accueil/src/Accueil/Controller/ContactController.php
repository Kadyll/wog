<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonAccueil for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Accueil\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Service\LogService;

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Validator;

class ContactController extends AbstractActionController
{
    private $_logService;
    private $_config;
    private $_servTranslator;
    /**
     * Lazy getter pour le service de logs
     * @return service Le service de logs
     */
    private function _getLogService() {
        return  $this->_logService ?
                $this->_logService :
                $this->_logService = $this->getServiceLocator()->get('LogService');
    }
    private function _getServConfig()
    {
        if(!$this->_config)
        {
            $this->_config = $this->getServiceLocator()->get('config');
        }
        return $this->_config;
    }
    public function _getServTranslator()
    {
        if (!$this->_servTranslator) {
            $this->_servTranslator = $this->getServiceLocator()->get('translator');
        }
        return $this->_servTranslator;
    }
    public function indexAction()
    {
          // Log de l'update
//        $this->_getLogService()->log(LogService::NOTICE, "test de log user", LogService::USER);
//        $this->_getLogService()->log(LogService::NOTICE, "test de log RTK", LogService::LOGICIEL);
//        $this->_getLogService()->log(LogService::NOTICE, "test de log RTK", LogService::DEBUG);
        
        $oViewModel = new ViewModel();
        $publicKey = $this->_getServConfig()['google']['publicKey'];
        $privateKey = $this->_getServConfig()['google']['privateKey'];
        $oViewModel->setVariable('publicKey', $publicKey);
        $oViewModel->setVariable('privateKey', $privateKey);
        $oViewModel->setTemplate('accueil/contact/index');
        return $oViewModel;
    }
    
    public function getFormAction()
    {
        $aRequest = $this->getRequest();
        $aPost = $aRequest->getPost();
        $sMail = $aPost['email'];
        $sSubject = $aPost['subject'];
        
        $validator = new Validator\EmailAddress();
        $validMessage = new Validator\NotEmpty();
        
        if(!$this->_validCaptcha($aPost['g-recaptcha-response']))
           return $this->redirect()->toRoute('contact');           
        
        if(!$validator->isValid($sMail))
        {
            $this->flashMessenger()->addMessage($this->_getServTranslator()->translate("L'adresse e-mail renseignée n'est pas valide."), 'error');
            return $this->redirect()->toRoute('contact');
        }
        
        if(!$validMessage->isValid($aPost['message']))
        {
            $this->flashMessenger()->addMessage($this->_getServTranslator()->translate("Votre message est vide."), 'error');
            return $this->redirect()->toRoute('contact');
        }
        
        $oViewModel = new ViewModel(array('post'=>$aPost));
        $oViewModel->setTemplate('accueil/contact/mail_contact');
        $oViewModel->setTerminal(true);
        $sm = $this->getServiceLocator();
             

        $html = new MimePart(nl2br($sm->get('ViewRenderer')->render($oViewModel)));
        $html->type = "text/html";
        
        $body = new MimeMessage();
        $body->setParts(array($html));

        $oMail = new Message();
        $oMail->setBody($body);
        $oMail->setEncoding('UTF-8');
        $oMail->setFrom('contact@raid-tracker.com');
        $oMail->addTo('contact@raid-tracker.com');
       // $oMail->addCc('contact@raid-tracker.com');
        $oMail->setSubject($sSubject);

        $oSmtpOptions = new \Zend\Mail\Transport\SmtpOptions();  
        $oSmtpOptions->setHost($this->_getServConfig()['mail']['auth'])
                    ->setConnectionClass('login')
                    ->setName($this->_getServConfig()['mail']['namelocal'])
                    ->setConnectionConfig(array(
                        'username' => $this->_getServConfig()['mail']['username'],
                        'password' => $this->_getServConfig()['mail']['password'],
                        'ssl' => $this->_getServConfig()['mail']['ssl'],
                    ));

        $oSend = new \Zend\Mail\Transport\Smtp($oSmtpOptions);
        
        $bSent = true;
        
        try {
            $oSend->send($oMail);
        } catch (\Zend\Mail\Transport\Exception\ExceptionInterface $e) {
            $bSent = false;
            $this->flashMessenger()->addMessage($e->getMessage(),'error');
        }
        
        if($bSent)
        {
            $this->flashMessenger()->addMessage($this->_getServTranslator()->translate("Votre message a été envoyé."),'success');
            $this->_getLogService()->log(LogService::NOTICE, "Email de $sMail", LogService::USER);
        }
        else
        {
            $this->flashMessenger()->addMessage($this->_getServTranslator()->translate("Votre message n'a pu être envoyé."), 'error');
            $this->_getLogService()->log(LogService::ERR, "Erreur d'envoie de mail à $sMail", LogService::USER);
            
        }           
        return $this->redirect()->toRoute('contact');
        

    }
    
    private function _validCaptcha($captchaReponse)
    {
         $recaptcha = new \ZendService\ReCaptcha\ReCaptcha( $this->_getServConfig()['google']['publicKey'],
                                                            $this->_getServConfig()['google']['privateKey']);
        
        if(empty($captchaReponse))
        {
            $this->flashMessenger()->addMessage($this->_getServTranslator()->translate("Veuillez valider votre captcha."), 'error');
            return false;                
        }
        
        $result = $recaptcha->verify($captchaReponse);
        
        if (!$result->isValid()) {
            $this->flashMessenger()->addMessage($this->_getServTranslator()->translate("Veuillez verifier votre captcha."), 'error');
            return false;
        }
        else return true;
    }
}
