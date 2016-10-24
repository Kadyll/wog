<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonAccueil for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Accueil;

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
            'contact' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/contact[/:action]',
                    'defaults' => array(
                        'controller' => 'Accueil\Controller\Contact',
                        'action' => 'index',
                    ),
                ),
            ),
            'team' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/team',
                    'defaults' => array(
                        'controller' => 'Accueil\Controller\Team',
                        'action' => 'index',
                    ),
                ),
            ),
            'gallery' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/gallery',
                    'defaults' => array(
                        'controller' => 'Accueil\Controller\Gallery',
                        'action' => 'index',
                    ),
                ),
            ),
            
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Accueil\Controller\Index' => 'Accueil\Controller\IndexController',
            'Accueil\Controller\Contact' => 'Accueil\Controller\ContactController',
            'Accueil\Controller\Team' => 'Accueil\Controller\TeamController',
            'Accueil\Controller\Gallery' => 'Accueil\Controller\GalleryController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
