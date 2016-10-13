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

class PageHomeController extends AbstractActionController {

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

    private function _getContentPageTable() {
        if (!$this->_contentPageTable) {
            $sm = $this->getServiceLocator();
            $this->_contentPageTable = $sm->get('Backend\Model\ContentPageTable');
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
        $oViewModel->setVariable('idPage', '1');
        $oViewModel->setTemplate('backend/home/home');


        return $oViewModel;
    }

    /*
     * renvoie la liste des articles
     */

    public function articlesAction() {
        $oRequest = $this->getRequest();
        $oViewModel = new ViewModel();
        $oSession = $this->_getSessionUser();

        if (!$oSession->offsetExists('administrateur')) {
            $this->flashMessenger()->addErrorMessage($this->_getServTranslator()->translate("La page demandé n'est pas accessible."));
            return $this->redirect()->toRoute('home');
        }
        
        try {
            $aListeArticles = $this->_getContentPageTable()->getListeArticles()->toArray();
            $oViewModel->setVariable('listeArticles', $aListeArticles);
            
        } catch (Exception $ex) {
            $this->flashMessenger()->addErrorMessage($this->_getServTranslator()->translate("Problème(s) lors du chargement des informations."));
            return $this->redirect()->toRoute('backend-home');
        }
        if ($oRequest->isPost()) {
            $aPost = $oRequest->getPost();
            try {   
                $aArticle = $this->_getContentPageTable()->getArticle($aPost['articles']);
                $oViewModel->setVariable('titleArticle', $aArticle['titleArticle']);
                $oViewModel->setVariable('idContentPage', $aArticle['idContentPage']);
                $oViewModel->setVariable('content', $aArticle['content']);
            } catch (Exception $ex) {
                $this->flashMessenger()->addErrorMessage($this->_getServTranslator()->translate("Problème(s) lors du chargement de l'article."));
                return $this->redirect()->toRoute('backend-home');
            }
        }

        $oViewModel->setTemplate('backend/home/home');

        return $oViewModel;
    }

    public function savearticleAction() {
        $oRequest = $this->getRequest();
        if ($oRequest->isPost()) {
            $aPost = $oRequest->getPost();
            
            if (isset($aPost['save'])) {
                
                try {
                    
                    $aArticle['titleArticle'] = $aPost['titleArticle'];
                    $aArticle['content'] = $aPost['content'];
                    $aArticle['idPage'] = 1;
                    $aArticle['type'] = 'article';
                    $aArticle['idContentPage'] = $aPost['idContentPage'];
                    $this->_getContentPageTable()->saveArticle($aArticle);
                    
                    $this->flashMessenger()->addSuccessMessage($this->_getServTranslator()->translate("Article enregistré avec succès."));
                    return $this->redirect()->toRoute('backend-home');
                } catch (Exception $ex) {
                    $this->flashMessenger()->addErrorMessage($this->_getServTranslator()->translate("Problème(s) lors de la sauvegarde de l'article."));
                    return $this->redirect()->toRoute('backend-home');
                }
            } elseif (isset($aPost['delete'])) {
                
                try {
                    $this->_getContentPageTable()->deleteArticle($aPost['idContentPage']);
                    
                    $this->flashMessenger()->addSuccessMessage($this->_getServTranslator()->translate("Article supprimé avec succès."));
                    return $this->redirect()->toRoute('backend-home');
                } catch (Exception $ex) {
                    $this->flashMessenger()->addErrorMessage($this->_getServTranslator()->translate("Problème(s) lors de la suppression de l'article."));
                    return $this->redirect()->toRoute('backend-home');
                }
            }
        }
        return $this->redirect()->toRoute('backend-home');
    }

//    Récupere la session
    private function _getSessionUser() {
        if (!$this->_oSessionUser) {
            $this->_oSessionUser = new Container('operateur');
        }
        return $this->_oSessionUser;
    }

}
