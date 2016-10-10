<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Application\Service\LogService;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
        );
    }
    
    public function getServiceConfig() {
        return array(
            'invokables' => array(
                'ModuleManagerService' => 'Application\Service\ModuleManagerService',
                'LogService' => 'Application\Service\LogService',
            ),
            'factories' => array(
                'Zend\Log' => function($sm){
                    $auth = $sm->get('zfcuser_auth_service');
                    
                    $logger = new \Zend\Log\Logger;
                    if($auth->hasIdentity())
                    {
                        $sName = $auth->getIdentity()->getId() . '-' . $auth->getIdentity()->getUsername();
                        $writer = new \Zend\Log\Writer\Stream('./data/log/users/'.$sName.'-users.log');
                    }
                    else $writer = new \Zend\Log\Writer\Stream('./data/log/users/users.log');

                    $logger->addWriter($writer);  

                    return $logger;
                },
            ),
        );
    }

}
