<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Ec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
 
use Zend\Captcha;
use Zend\Form\Element;
use Zend\Form\Form;
use Ec; // 参考URLにはなかったが追加する

/**
 * フォーム機能
 * Description of FormController
 *
 * @author onoe-azusa
 */
class FormController extends AbstractActionController
{
    //put your code here
 public function indexAction()
    {
        $form = new Ec\Form\ContractForm();
        $form->setCaptcha(new Captcha\Dumb());
        $form->prepareElements();
 
        return array('form' => $form);
    }
 
    public function fooAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            return array('datas' => $request->getPost());
        }
        return $this->redirect()->toRoute('form');
    }    
    
    
    
}
