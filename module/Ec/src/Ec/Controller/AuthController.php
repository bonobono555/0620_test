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
use Ec\Form\LoginForm;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;

/**
 * ������ǽ
 * Description of AuthController
 *
 * @author onoe-azusa
 */
class AuthController extends AbstractActionController
{
    // �����ѿ�
    protected $peopleTable;
    protected $dbAdapter;

    public function signupAction()
    {
        // RefisterForm��ƤӽФ�
        $form = new RegisterForm();
        // �ꥯ�����ȥ᥽�å�
        $request = $this->getRequest();

        // �ݥ��Ȥ���Ƥ�����
        if ($request->isPost()) {
            $people = new People();
            // getInputFilter�ǥե��륿����¹ԡʥХ�ǡ������Ū�ʴ�������
            $form->setInputFilter($people->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $people->exchangeArray($form->getData());
                // DB��Ͽ
                $this->getPeopleTable()->registerPeople($people);
                exit('��Ͽ��λ���ʲ��ν�����α');
            }
        }
        // view��form���ͤ��Ϥ�
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
    
    /*
     * �����󥢥������
     */
    public function loginAction()
    {
        $form = new LoginForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $people = new People();
            $form->setInputFilter($people->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $dbAdapter = $this->getDbAdapter();
                $authAdapter = new AuthAdapter($dbAdapter);

                $peopleData = $form->getData();
                // �б�����ơ��֥�̾����ˡ����ʥ����̾���ѥ���ɥ����̾
                $authAdapter->setTableName('people')->setIdentityColumn('email')->setCredentialColumn('password');
                // ������ä��ǡ���
                $authAdapter->setIdentity($peopleData['email'])->setCredential($peopleData['password']);
                // ɬ�ܤǤϤʤ���AuthenticationService������
                $auth = new AuthenticationService();
                $result = $auth->authenticate($authAdapter);
                exit(var_dump($result->isValid()));
            }
        }
                // view��form���ͤ��Ϥ�
        return array('form' => $form);
    }

    /*
     * �ǡ����١�������³���륢���ץ��������
     */
    public function getDbAdapter()
    {
        if (!$this->dbAdapter) {
            $sm = $this->getServiceLocator();
            $this->dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->dbAdapter;
    }
}
