<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonAccueil for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Backend;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Backend\Model\UsersTable;
use Backend\Model\RolesTable;
use Backend\Model\ContentPageTable;
use Backend\Model\PagesTable;
use Backend\Model\GalleryTable;
use Zend\Db\TableGateway\TableGateway;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $oSessionConfig = new \Zend\Session\Config\SessionConfig();
        $oSessionManager = new \Zend\Session\SessionManager($oSessionConfig);
        $oSessionManager->start();

        \Zend\Session\Container::setDefaultManager($oSessionManager);
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
            'factories' => array(
                'Backend\Model\UsersTable' => function($sm) {
                    $tableGateway = $sm->get('UsersTableGateway');
                    $table = new UsersTable($tableGateway);
                    return $table;
                },
                'UsersTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new TableGateway('users', $dbAdapter);
                },
                'Backend\Model\RolesTable' => function($sm) {
                    $tableGateway = $sm->get('RolesTableGateway');
                    $table = new RolesTable($tableGateway);
                    return $table;
                },
                'RolesTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new TableGateway('roles', $dbAdapter);
                },
                'Backend\Model\PagesTable' => function($sm) {
                    $tableGateway = $sm->get('PagesTableGateway');
                    $table = new PagesTable($tableGateway);
                    return $table;
                },
                'PagesTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new TableGateway('pages', $dbAdapter);
                },
                'Backend\Model\ContentPageTable' => function($sm) {
                    $tableGateway = $sm->get('ContentPageTableGateway');
                    $table = new ContentPageTable($tableGateway);
                    return $table;
                },
                'ContentPageTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new TableGateway('contentPage', $dbAdapter);
                },
                'Backend\Model\GalleryTable' => function($sm) {
                    $tableGateway = $sm->get('GalleryTableGateway');
                    $table = new GalleryTable($tableGateway);
                    return $table;
                },
                'GalleryTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new TableGateway('gallery', $dbAdapter);
                },
            ),
        );
    }

}
