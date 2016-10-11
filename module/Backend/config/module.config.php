<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonAccueil for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Backend;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Accueil\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'backend' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/backend[/:action]',
                    'defaults' => array(
                        'controller' => 'Backend\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Backend\Controller\Index' => 'Backend\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);