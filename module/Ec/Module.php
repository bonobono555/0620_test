<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Ec;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

// DB��³�Τ����ɲ�----
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Ec\Model\UserTable;
use Ec\Model\User;
//-----------------------

use Zend\Db\Adapter\Adapter;    // ���ɲ�
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;    // ���ɲ�

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        // DB��³
        $this->createDbAdapter($e);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    /*
     * DB��³
     */

    protected function createDbAdapter(MvcEvent $e)
    {
        $config = $e->getApplication()->getConfig();
        $adapter = new Adapter($config['db']);
        GlobalAdapterFeature::setStaticAdapter($adapter);
    }

    
    
    // DB��³�Τ����ɲ�----
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Ec\Model\UserTable' => function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway(
                            'user', $dbAdapter, null, $resultSetPrototype
                    );
                },
            ),
        );
    }
    // DB��³�Τ����ɲ�----
}
