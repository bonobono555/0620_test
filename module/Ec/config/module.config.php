<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Ec\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            // /ecのルーティング設定
            'application' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/ec',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Ec\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                // ~/ec以下のルーティング設定
                'child_routes' => array(
                    // /ec以下のデフォルトのルーティング設定
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    // ログイン機能 signup　のルーティング設定　（自由にルーティング名を記述）
                    'signup' => array(
                        // ルーティングタイプ（完全一致で指定する場合はliteralと記述）
                        'type' => 'literal',
                        'options' => array(
                            // 一致するURL
                            'route' => 'signup',
                            'defaults' => array(
                                // Controller名
                                'controller' => 'Ec\Controller\Auth',
                                // アクション名
                                'action' => 'signup',
                            ),
                        ),
                    ),
                    // ログイン機能 login　のルーティング設定
                    'login' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => 'login',
                            'defaults' => array(
                                'controller' => 'Ec\Controller\Auth',
                                'action' => 'login',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    //  /ec/list/index[/:page]　のルーティングパラメータ設定
    'hoge' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/list/index[/:page]',
            'defaults' => array(
                'controller' => 'Ec\Controller\List',
                'action' => 'index',
            ),
        ),
    ),
    // ログイン機能end
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    // Controllerを利用可能にする Controlleを追加するたびにここに追加する。
    'controllers' => array(
        'invokables' => array(
            'Ec\Controller\Index' => 'Ec\Controller\IndexController',
            'Ec\Controller\List' => 'Ec\Controller\ListController', // Ecで作成したコントローラー
            'Ec\Controller\User' => 'Ec\Controller\UserController', // userコントローラー
            'Ec\Controller\Form' => 'Ec\Controller\FormController', // Formコントローラー フォーム登録
            'Ec\Controller\Auth' => 'Ec\Controller\AuthController', // Authコントローラー ログイン機能
        ),
    ),
    // viewフォルダを登録する
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'ec/index/index' => __DIR__ . '/../view/Ec/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
