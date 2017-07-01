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
// DB接続のため追加----
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Ec\Model\UserTable;
use Ec\Model\User; // UserTable
use Ec\Model\PeopleTable; // PeopleTable
// -----------------------
use Zend\Db\Adapter\Adapter;    // ←追加
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature; // ←追加

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        // DB接続
        $this->createDbAdapter($e);
    }

    public function getConfig()
    {
        // configファイルの配置
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            // srcフォルダ（公式ドキュメントと同様にsrcフォルダ内にモジュール名のフォルダを作成し、その中をオートロードするように設定）
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    /*
     * DB接続
     */

    protected function createDbAdapter(MvcEvent $e)
    {
        $config = $e->getApplication()->getConfig();
        $adapter = new Adapter($config['db']);
        GlobalAdapterFeature::setStaticAdapter($adapter);
    }

    // DB接続のため追加----
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                // UserTable追加 インスタンス作成している
                'Ec\Model\UserTable' => function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                // PeopleTable追加 インスタンス作成している
                'Ec\Model\PeopleTable' => function($serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $table = new PeopleTable($dbAdapter);
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
}
