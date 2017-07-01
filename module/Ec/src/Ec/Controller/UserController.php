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
    // メンバ変数
    protected $userTable;
    
    // 翻訳データ
    protected $translator;


    // ユーザ情報一覧画面
    public function indexAction()
    {
        $values = array(
            'users' => $this->getUserTable()->fetchAll(),
        );
        
        $view = new ViewModel( $values );
        
        return $view;
        
    }
    
    // ServiceManagerで生成されたインスタンスを取得
    public function getUserTable()
    {
        if (!$this->userTable){
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Ec/Model/UserTable');
        }
        return $this->userTable;
    }

    // ユーザ情報詳細画面
    public function detailAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id){
            return $this->redirect()->toRoute('ec', array(
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
    
    // ユーザ情報追加画面
    public function addAction() 
    {
        // チャプター03
//        $user = new User();
//        $user->name = "testname";
//        $user->email = "testemail";
//        $this->getUserTable()->saveUser($user);
//
//        $values = array(
//            'key1' => 'value1',
//            'key2' => 'value2',
//        );        

        // フォームオブジェクトのインスタンスを作成
        $form = new UserForm();
                
        // 04のシステム作成
        $form->get('submit')->setValue('tourokuGO!');
        
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $user = new User();
            // ユーザモデル用のフィルタをフォームのフィルタとして設定
            $form->setInputFilter($user->getInputFilter());
            // post投稿された値をsetData()にてフォーム要素に設定
            $form->setData($request->getPost());              
            
            // フォームに登録したフィルタと、フォームが保持する値の検証をisValid()で行う
            if ($form->isValid())
            {
                // ユーザーモデルの値を初期化
                $user->exchangeArray($form->getData());
                // DB登録
                $this->getUserTable()->saveUser($user);
                return $this->redirect()->toRoute('ec', array(
                    'controller' => 'user',
                    'action' => 'index'
                ));
            }
            // バリデーションが通らなかった場合、エラーメッセージを表示
            else {
                $message = [];
                foreach ($form->getMessages() as $messageId => $message){
                    var_dump($messageId);
                    echo "<br>";
                    var_dump($message);                
                }
            }
        }
        
        // ビューへ渡す値を連想配列にて指定
        $values = array(
            'form' => $form,
        );
        
        // ビューを作成し、値をセット
        $view = new ViewModel( $values );
        
        // ビューを返却
        return $view;
        
    }
    
    //ユーザ情報編集画面
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id){
            return $this->redirect()->toRoute('ec', array(
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
            // 翻訳ファイルの適用
//            $this->getTranslator->addTranslationFile('phparrray','パス表記');
            
            $form->setInputFilter($user->getInputfilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()){
                $this->getUserTable()->saveUser($form->getData());
                
                return $this->redirect()->toRoute('ec', array(
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
    
    // ユーザ情報削除画面
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id){
            return $this->redirect()->toRoute('ec', array(
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
            
            return $this->redirect()->toRoute('ec', array(
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
    
    // 翻訳ファイルの使用
    public function getTranslator($param)
    {
        if (!$this->translator){
            $sm = $this->getServiceLocator();
            $this->translator = $sm->get('translator');
        }
        return $this->translator;
        
    }
}
