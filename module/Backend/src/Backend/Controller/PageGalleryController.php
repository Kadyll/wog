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

class PageGalleryController extends AbstractActionController {

    public $_servTranslator = null;
    private $_oSessionUser = null;
    private $_galleryTable;

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

    private function _getGalleryTable() {
        if (!$this->_galleryTable) {
            $sm = $this->getServiceLocator();
            $this->_galleryTable = $sm->get('Backend\Model\GalleryTable');
        }
        return $this->_galleryTable;
    }

    public function indexAction() {
        $oViewModel = new ViewModel();
        $oSession = $this->_getSessionUser();

        if (!$oSession->offsetExists('administrateur')) {
            $this->flashMessenger()->addErrorMessage($this->_getServTranslator()->translate("La page demandé n'est pas accessible."));
            return $this->redirect()->toRoute('home');
        }

        $oViewModel->setTemplate('backend/gallery/gallery');

        return $oViewModel;
    }

    public function getFormUploadAction() {
        $oRequest = $this->getRequest();
        $valUpload = new \Zend\Validator\File\UploadFile();
        $basePath = $this->getServiceLocator()->get('Request')->getBasePath();
        $bValidUpload = true;

        if (!$oRequest->isPost()) {
            $this->flashMessenger()->addErrorMessage($this->_getServTranslator()->translate("Problème(s) lors du chargement des informations."));
            return $this->redirect()->toRoute('backend-gallery');
        }

        $aPost = array_merge_recursive($oRequest->getPost()->toArray(), $oRequest->getFiles()->toArray());



        if ($aPost['titleImage'] != '' && $aPost['titleVideo'] === '') {
            //on effectue les controles sur le fichier à upload
            if (!empty($aPost['image']['name'])) {
                $aExtensions = array('jpg', 'jpeg', 'png');

                if (!$valUpload->isValid($aPost['image']))
                    $bValidUpload = false;

                $sFileExtension = (explode('.', $aPost['image']['name']));
                if (isset($sFileExtension[1])) {
                    $sFileExtension = $sFileExtension[1];
                    if (!in_array($sFileExtension, $aExtensions))
                        $bValidUpload = false;
                } else
                    $bValidUpload = false;

                if ($aPost['image']['size'] > 2000000)
                    $bValidUpload = false;
            }

            if (!$bValidUpload) {
                $this->flashMessenger()->addErrorMessage($this->_getServTranslator()->translate("Problème(s) lors du chargement des informations."));
                return $this->redirect()->toRoute('backend-gallery');
            }

            $aElement['title'] = $aPost['titleImage'];
            $aElement['type'] = 'image';
            $aElement['location'] = $aPost['image']['name'];

        } elseif ($aPost['titleImage'] === '' && $aPost['titleVideo'] != '') {
            $aElement['title'] = $aPost['titleVideo'];
            $aElement['type'] = 'youtube';
            $aElement['location'] = $aPost['linkVideo'];

        } else {
            $this->flashMessenger()->addErrorMessage($this->_getServTranslator()->translate("Problème(s) lors du chargement des informations."));
            return $this->redirect()->toRoute('backend-gallery');
        }
       
        try {
            $saveElement = $this->_getGalleryTable()->saveElement($aElement);
            
            if ($aPost['titleImage'] != '' && $aPost['titleVideo'] === '')
                move_uploaded_file($aPost['image']['tmp_name'], "{$basePath}public/screen/{$aPost['image']['name']}");
                
        } catch (Exception $ex) {
            $this->flashMessenger()->addErrorMessage($this->_getServTranslator()->translate("Problème(s) lors du chargement des informations."));
            return $this->redirect()->toRoute('backend-gallery');
        }
        return $this->redirect()->toRoute('backend-gallery');
    }

//    Récupere la session
    private function _getSessionUser() {
        if (!$this->_oSessionUser) {
            $this->_oSessionUser = new Container('operateur');
        }
        return $this->_oSessionUser;
    }

}
