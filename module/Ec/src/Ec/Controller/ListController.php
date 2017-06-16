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
        // -----ルーティング設定練習--------------
        $page = $this->params()->fromRoute('page');
        var_dump($page);
        $result = 'abcde';
        
        // ------viewへのデータ引き渡し------------------------
        return new ViewModel(array(
            'data' => 'hello',
            'morning' => 'Good Morning',
            'result' => $result,
        ));
        // -----------------------------------
        


    }
}


//        // セッションの利用
//        $session = new Container('list');
//        $page = 1;
//        if ($this->params()->fromRoute('page') != null) {
//            $page = $this->params()->fromRoute('page');
//            // セッションへ格納
//            $session->page = $page;
//        } elseif ($session->page != null) {
//            // セッションから取得
//            $page = $session->page;
//        }
//
//        return new ViewModel(array(
//            'page' => $page,
//        ));


