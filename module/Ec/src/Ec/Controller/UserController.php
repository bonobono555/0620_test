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
use Ec\Model\Auth;
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
        $auth = new Auth();
        // ログイン情報保持
        $login_user = $auth->getLoginUser();
         
        // フラッシュマネージャープラグインのハンドルを取得
        $flashMessenger = $this->flashMessenger();
 
        // 現在の要求中に追加されたものがあるのかチェック
        $message = '';
        if( $flashMessenger->hasMessages() ){
 
            // メッセージの取得（配列）
            $message_array = $flashMessenger->getMessages();

            // 初めのメッセージを取得
           $message = $message_array[0];
        }
        
        $values = array(
            'users' => $this->getUserTable()->fetchAll(),
            'message' => $message,
            'login_user' => $login_user,         
        );
        
        $view = new ViewModel( $values );
        
        return $view;
        
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
        
        //  ログインユーザ情報を取得する
        $auth = new Auth();
        $login_user = $auth->getLoginUser();

        //  本人の情報か判定
        $isSelf = false;
        if( $login_user->id == $id ){
            $isSelf = true;
        }

        $values = array(
            'user'   => $user,
            'isSelf' => $isSelf,
        );
        $view = new ViewModel($values);
        
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
        // ログインユーザ情報を取得する
        $auth = new Auth();
        $login_user = $auth->getLoginUser();

        // ログインしていなければログイン画面へ遷移させる
        if( ! $login_user->id ){

            // フラッシュマネージャへ受け渡すメッセージを追加する
            $this->flashMessenger()->addMessage( "Not Edit" );

            return $this->redirect()->toRoute('ec', array(
                'controller' => 'index',
                'action'     => 'login',
            ));
        }
        $user = $this->getUserTable()->getUser($login_user->id);

        // idが設定されていなければユーザ一覧画面にリダイレクト
        $id = $login_user->id;
        if (!$id){
            return $this->redirect()->toRoute('ec', array(
                'controller' => 'user',
                'action' => 'index'
            ));
        }
//        $user = $this->getUserTable()->getUser($id);
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
                // 一覧画面で以下のメッセージを表示する
                $this->flashMessenger()->addMessage( $user->name . "さんの情報を編集しました。" );

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
        // ログインユーザのみ削除できる
        //  ログインユーザ情報を取得する
        $auth = new Auth();
        $login_user = $auth->getLoginUser();

        //  ログインしていなければログイン画面へ遷移させる
        if( ! $login_user->id ){

            // フラッシュマネージャへ受け渡すメッセージを追加する
            $this->flashMessenger()->addMessage( "Not Delete" );

            return $this->redirect()->toRoute('ec', array(
                'controller' => 'index',
                'action'     => 'login',
            ));           
        }         
        $user = $this->getUserTable()->getUser($login_user->id);
        
        // ログインidを取得
        $id = $login_user->id;
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
            // 一覧画面で以下のメッセージを表示する
            $this->flashMessenger()->addMessage( $user->name . " is delete" );
            
        }
        
        $values = array(
            'id' => $id,
            'user' => $this->getUserTable()->getUser($id)
        );
        $view = new ViewModel( $values );
        
        return $view;
        
        
    }
    
    /*
     * ログイン後、マイページ（詳細画面）に遷移
     */
    public function mypageAction()
    {
        //  ログインユーザ情報を取得する
        $auth = new Auth();
        $login_user = $auth->getLoginUser();

        //  ログインしていなければログイン画面へ遷移させる
        if( ! $login_user->id ){

            // フラッシュマネージャへ受け渡すメッセージを追加する
            $this->flashMessenger()->addMessage( "plese login" );

            return $this->redirect()->toRoute('ec', array(
                'controller' => 'index',
                'action'     => 'login',
            ));
        }

        //  マイページとして、ID付で詳細画面へリダイレクトさせる
        return $this->redirect()->toRoute('ec', array(
            'controller' => 'user',
            'action'     => 'detail',
            'id'         => $login_user->id,
        ));
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
