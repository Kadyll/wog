<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonAccueil for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class PageTeamController extends AbstractActionController {

    public $_servTranslator = null;
    private $_oSessionUser = null;
    private $_contentPageTable;
    /**
     * Retourne le service de traduction en mode lazy.
     *
     * @return
     */
    public function _getServTranslator() {
        if (!$this->_servTranslator) {
            $this->_servTranslator = $this->getServiceLocator()->get('translator');
        }
        return $this->_servTranslator;
    }
    
    private function _getContentPageTable()
    {
        if(!$this->$_contentPageTable)
        {
            $sm = $this->getServiceLocator();
            $this->$_contentPageTable = $sm->get('Backend\Model\ContentPageTable');
        }
        return $this->_contentPageTable;
    }
    
    public function indexAction() {
        $oViewModel = new ViewModel();
        $oSession = $this->_getSessionUser();
        
        if (!$oSession->offsetExists('administrateur')) {
            $this->flashMessenger()->addErrorMessage($this->_getServTranslator()->translate("La page demandé n'est pas accessible."));
            return $this->redirect()->toRoute('home');
        }
        
        try
        {
            $aContentPage = $this->_getContentPageTable()->getContentByPageId('2')->toArray();
            $oViewModel->setVariable('content', $aContentPage['content']);
            $oViewModel->setVariable('idPage', $aContentPage['idPage']);
           
        } catch(Exception $ex)
        {
            $this->flashMessenger()->addErrorMessage($this->_getServTranslator()->translate("Problème(s) lors du chargement des informations."));
            return $this->redirect()->toRoute('backend');
        }
        $oViewModel->setTemplate('backend/team/team');
        
        return $oViewModel;
    }

    private function savepageAction()
    {
        $oRequest = $this->getRequest();
        if(!$oRequest->isPost())
        {
            $this->flashMessenger()->addErrorMessage($this->_getServTranslator()->translate("Problème(s) lors du chargement des informations."));
            return $this->redirect()->toRoute('backend-team');
        }
        
        $aPost = $oRequest->getPost();
        
        try
        {
            $saveContent = $this->_getContentPageTable()->saveContentPage($aPost['content'],2);
        } catch (Exception $ex) {
            $this->flashMessenger()->addErrorMessage($this->_getServTranslator()->translate("Problème(s) lors du chargement des informations."));
            return $this->redirect()->toRoute('backend-team');
        }
    }
//    Récupere la session
    private function _getSessionUser() {
        if (!$this->_oSessionUser) {
            $this->_oSessionUser = new Container('operateur');
        }
        return $this->_oSessionUser;
    }

}
