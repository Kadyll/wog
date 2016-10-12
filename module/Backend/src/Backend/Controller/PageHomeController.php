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

class IndexController extends AbstractActionController {

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
    
    private function _getUsersTable()
    {
        if(!$this->$_contentPageTable)
        {
            $sm = $this->getServiceLocator();
            $this->_usersTable = $sm->get('Backend\Model\ContentPageTable');
        }
        return $this->$_contentPageTable;
    }
    
    public function indexAction() {
        $oViewModel = new ViewModel();
        $oSession = $this->_getSessionUser();
        $bAdmin = false;
        
        if ($oSession->offsetExists('administrateur')) {
            $bAdmin = true;
        }
        
        $oViewModel->setVariable('isAdmin', $bAdmin);
        
        return $oViewModel;
    }

//    Récupere la session
    private function _getSessionUser() {
        if (!$this->_oSessionUser) {
            $this->_oSessionUser = new Container('operateur');
        }
        return $this->_oSessionUser;
    }

}
