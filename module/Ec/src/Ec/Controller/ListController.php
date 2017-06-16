<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Ec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ListController extends AbstractActionController
{

    public function indexAction()
    {
        // -----�롼�ƥ�����������--------------
        $page = $this->params()->fromRoute('page');
        var_dump($page);
        $result = 'abcde';
        
        // ------view�ؤΥǡ��������Ϥ�------------------------
        return new ViewModel(array(
            'data' => 'hello',
            'morning' => 'Good Morning',
            'result' => $result,
        ));
        // -----------------------------------
        


    }
}


//        // ���å���������
//        $session = new Container('list');
//        $page = 1;
//        if ($this->params()->fromRoute('page') != null) {
//            $page = $this->params()->fromRoute('page');
//            // ���å����س�Ǽ
//            $session->page = $page;
//        } elseif ($session->page != null) {
//            // ���å���󤫤����
//            $page = $session->page;
//        }
//
//        return new ViewModel(array(
//            'page' => $page,
//        ));


