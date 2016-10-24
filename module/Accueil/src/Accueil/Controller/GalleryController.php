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

class GalleryController extends AbstractActionController
{
    public $_servTranslator = null;
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
    
    
    public function indexAction()
    {
        $oViewModel = new ViewModel();
        
        try
        {
            $aElement = $this->_getGalleryTable()->fetchAll();
            $oViewModel->setVariable('listElements', $aElement);
        } catch (Exception $ex) {
            return $this->redirect()->toRoute('home');
        }
        
        $oViewModel->setTemplate('accueil/gallery/gallery');
        return $oViewModel;
    }

}
