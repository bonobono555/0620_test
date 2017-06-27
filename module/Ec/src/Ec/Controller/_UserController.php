<?php

namespace Ec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ec\Model\User;

class UserController extends AbstractActionController
{
    protected $userTable;
    
    public function indexAction()
    {
        $result = $this->getUserTable()->fetchAll();
        $result->buffer();
        return new ViewModel(array('users' => $result));
    }

    public function addAction()
    {
        // 新規ユーザの追加
        $user = new User();
        $user->name = "hoge-taro";
        $user->email = "hoge@hoge.com";
        $this->getUserTable()->saveUser($user);
    }

    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Ec\Model\UserTable');
        }
        return $this->userTable;
    }
}
