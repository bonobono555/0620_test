<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ec\Form\UserForm;
use Ec\Model\User;
/**
 * Description of User
 *
 * @author onoeadusa
 */
class UserController extends AbstractActionController
{
    // �����ѿ�
    protected $userTable;
    
    // �����ǡ���
    protected $translator;


    // �桼�������������
    public function indexAction()
    {
        $values = array(
            'users' => $this->getUserTable()->fetchAll(),
        );
        
        $view = new ViewModel( $values );
        
        return $view;
        
    }
    
    public function getUserTable()
    {
        if (!$this->userTable){
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Ec/Model/UserTable');
        }
        return $this->userTable;
    }

    // �桼������ܺٲ���
    public function detailAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id){
            return $this->redirect()->toRoute('application', array(
                'controller' =>'user',
                'action' => 'index'
            ));
        }
        $user = $this->getUserTable()->getUser($id);
        
        $value = array(
            'user' => $user,
        );
        $view = new ViewModel($value);
        
        return $view;
        
    }    
    
    // �桼�������ɲò���
    public function addAction() 
    {
        // 03�Υ��塼�ȥꥢ��
//        $user = new User();
//        $user->name = "testname";
//        $user->email = "testemail";
//        $this->getUserTable()->saveUser($user);
//
//        $values = array(
//            'key1' => 'value1',
//            'key2' => 'value2',
//        );        

        // �ե����४�֥������ȤΥ��󥹥��󥹤����
        $form = new UserForm();
                
        // 04�Υ����ƥ����
        $form->get('submit')->setValue('tourokuGO!');
        
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $user = new User();
            // �桼����ǥ��ѤΥե��륿��ե�����Υե��륿�Ȥ�������
            $form->setInputFilter($user->getInputFilter());
            // post��Ƥ��줿�ͤ�setData()�ˤƥե��������Ǥ�����
            $form->setData($request->getPost());
            
            // �ե��������Ͽ�����ե��륿�ȡ��ե����ब�ݻ������ͤθ��ڤ�isValid()�ǹԤ�
            if ($form->isValid())
            {
                // �桼������ǥ���ͤ�����
                $user->exchangeArray($form->getData());
                // DB��Ͽ
                $this->getUserTable()->saveUser($user);
                echo('<pre>');
                var_dump($this->getUserTable()->saveUser($user));
                echo('</pre>');
                exit;
                return $this->redirect()->toRoute('application', array(
                    'controller' => 'user',
                    'action' => 'index'
                ));
            } else {
                foreach ($form->getMessages() as $messageId => $message){
                    echo "error         '$messageId': $message\n";                    
                }
            }
        }
        
        // �ӥ塼���Ϥ��ͤ�Ϣ������ˤƻ���
        $values = array(
            'form' => $form,
        );
        
        // �ӥ塼����������ͤ򥻥å�
        $view = new ViewModel( $values );
        
        // �ӥ塼���ֵ�
        return $view;
        
    }
    
    //�桼�������Խ�����
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id){
            return $this->redirect()->toRoute('application', array(
                'controller' => 'user',
                'action' => 'index'
            ));
        }
        $user = $this->getUserTable()->getUser($id);
        var_dump($user);
        $form = new UserForm;
        $form->bind($user);
        $form->get('submit')->setAttribute('value', 'edit');
        
        $request = $this->getRequest();
        if ($request->isPost()){
            // �����ե������Ŭ��
//            $this->getTranslator->addTranslationFile('phparrray','�ѥ�ɽ��');
            
            // getInputfilter�ηѾ�������������
            $form->setInputFilter($user->getInputfilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()){
                $this->getUserTable()->saveUser($form->getData());
                
                return $this->redirect()->toRoute('application', array(
                    'controller' => 'user',
                    'action' => 'index'
                ));
            }
        }
        
        $values = array(
            'id' => $id,
            'form' => $form,
        );
        $view = new ViewModel($values);
        
        return $view;
    }
    
    // �桼������������
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id){
            return $this->redirect()->toRoute('application', array(
                'controller' => 'user',
                'action' => 'index'
            ));
        }
        
        $request = $this->getRequest();
        if ($request->isPost()){
            $del = $request->isPost('del', 'No');
            
            if($del == 'Yes'){
                $id = (int) $request->getPost('id');
                $this->getUserTable()->deleteUser($id);
            }
            
            return $this->redirect()->toRoute('application', array(
                'controller' => 'user',
                'action' => 'index'
            ));
        }
        
        $values = array(
            'id' => $id,
            'user' => $this->getUserTable()->getUser($id)
        );
        $view = new ViewModel( $values );
        
        return $view;
        
        
    }
    
    // �����ե�����λ���
    public function getTranslator($param)
    {
        if (!$this->translator){
            $sm = $this->getServiceLocator();
            $this->translator = $sm->get('translator');
        }
        return $this->translator;
        
    }
}
