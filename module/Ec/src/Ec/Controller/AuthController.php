<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Ec\Form\RegisterForm;
use Ec\Model\People;

/**
 * ログイン機能
 * Description of AuthController
 *
 * @author onoe-azusa
 */
class AuthController extends AbstractActionController
{
    protected $peopleTable;

    public function signupAction()
    {
        $form = new RegisterForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $people = new People();
            $form->setInputFilter($people->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $egist->exchangeArray($form->getData());
                $this->getPeopleTable()->registerPeople($people);
                exit('登録完了！以下の処理保留');
            }
        }
        return array('form' => $form);
    }

    private function getPeopleTable()
    {
        if (!$this->peopleTable) {
            $sm = $this->getServiceLocator();
            $this->peopleTable = $sm->get('Ec\Model\PeopleTable');
        }
        return $this->peopleTable;
    }
}
