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

class IndexController extends AbstractActionController
{
    public $_servTranslator = null;
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

    public function indexAction()
    {
        $oViewModel = new ViewModel();
        
        try
        {
            $aListeArticles = $this->_getContentPageTable()->getListeArticlesDesc()->toArray();
            $oViewModel->setVariable('listeArticles', $aListeArticles);
            
        } catch (Exception $ex) {
            
        }
        
        $oViewModel->setTemplate('accueil/index/index');
        return $oViewModel;
    }

}
