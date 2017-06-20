<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Ec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
/**
 * Description of AnthController
 *
 * @author onoe-azusa
 */
class AnthController extends AbstractActionController
{
    protected $LoginTable; 
    //put your code here
    public function signupAction()
    {
        $form = new RegisterForm();
        $request = $this->getRequest();

        if($reqiest->isPost()){
            $login = new Login();
            $login->exchangeArray($form->getData());
            $this->getLoginTable()->registerLogin($login);
        }

        return array('form' => $form);
    }

    private function getLoginTable()
    {
        if(!$this->loginTable) {
            $sm = $this->getServiceLocator();
            $this->loginTable = $sm->get('Auth\Model\LoginTable');
        }
        return $this->loginTable;
    }    

    public function loginAction()
    {

    }    
}
