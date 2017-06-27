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
            // /ec�Υ롼�ƥ�������
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
                // ���/ec�ʲ��Υ롼�ƥ�������
                'child_routes' => array(
                    // /ec�ʲ��Υǥե���ȤΥ롼�ƥ�������
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
                    // ������ǽ signup���Υ롼�ƥ������ꡡ�ʼ�ͳ�˥롼�ƥ���̾�򵭽ҡ�
                    'signup' => array(
                        // �롼�ƥ��󥰥����סʴ������פǻ��ꤹ�����literal�ȵ��ҡ�
                        'type' => 'literal',
                        'options' => array(
                            // ���פ���URL
                            'route' => 'signup',
                            'defaults' => array(
                                // Controller̾
                                'controller' => 'Ec\Controller\Auth',
                                // ���������̾
                                'action' => 'signup',
                            ),
                        ),
                    ),
                    // ������ǽ login���Υ롼�ƥ�������
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
    //  /ec/list/index[/:page]���Υ롼�ƥ��󥰥ѥ�᡼������
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
    // ������ǽend
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
    // Controller�����Ѳ�ǽ�ˤ��� Controlle���ɲä��뤿�Ӥˤ������ɲä��롣
    'controllers' => array(
        'invokables' => array(
            'Ec\Controller\Index' => 'Ec\Controller\IndexController',
            'Ec\Controller\List' => 'Ec\Controller\ListController', // Ec�Ǻ�����������ȥ��顼
            'Ec\Controller\User' => 'Ec\Controller\UserController', // user����ȥ��顼
            'Ec\Controller\Form' => 'Ec\Controller\FormController', // Form����ȥ��顼 �ե�������Ͽ
            'Ec\Controller\Auth' => 'Ec\Controller\AuthController', // Auth����ȥ��顼 ������ǽ
        ),
    ),
    // view�ե��������Ͽ����
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
