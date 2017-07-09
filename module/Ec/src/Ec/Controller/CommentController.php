<?php

namespace Ec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ec\Model\Comment;
use Ec\Form\CommentForm;
use Ec\Model\Auth;
use Ec\Model\User;
use Ec\Form\UserForm;

/**
 * 掲示板管理コントローラ
 *
 * @author onoeadusa
 */
class CommentController extends AbstractActionController
{
    protected $translator;
    protected $commentTable;
    protected $userTable;
    
    /*
     * コメント一覧画面
     */
    public function indexAction()
    {
        // フラッシュマネージャーを取得
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
            'comments' => $this->getCommentTable()->fetchAllWithUser( array( 'parent_id' => 0 ) ),
            'message' => $message,
        );
        $view = new ViewModel( $values );

        return $view;
    }
    
    /*
     * コメント新規投稿
     */
    public function addAction()
    {
        // ログインユーザ情報を取得する
        $auth = new Auth();
        $login_user = $auth->getLoginUser();

        // ログインしていなければログイン画面へ遷移させる
        if( ! $login_user->id ){

            // フラッシュマネージャへ受け渡すメッセージを追加する
            $this->flashMessenger()->addMessage( "コメントを投稿するにはログインが必要です。" );

            return $this->redirect()->toRoute('ec', array(
                'controller' => 'index',
                'action' => 'login',
            ));
        }
        
        // フォームオブジェクト作成
        $form = new CommentForm();
        $form->get('submit')->setValue('ほっこりを投稿');
        $form->get('user_id')->setValue($login_user->id);
        
        $request = $this->getRequest();
        if ($request->isPost()) {

            //  トランスレーターに翻訳ファイルを読み込ませる
//            $this->getTranslator()->addTranslationFile('phparray', 'C:\xampp\Library\ZendFramework-2.2.2\resources\languages\ja\Zend_Validate.php');

            $comment = new Comment();

            // コメントモデル用のフィルタをフォームのフィルタとして設定
            $form->setInputFilter($comment->getInputFilter());
            // post投稿された値をsetData()にてフォーム要素に設定       
            $form->setData($request->getPost());

            if ($form->isValid()) {
                // コメントモデルの値を初期化
                $comment->exchangeArray($form->getData());

                //  タイトルの投稿が無い場合は本文を引用する
                if( strlen( $comment->title ) <= 0 ){
                    $comment->title = mb_strimwidth( $comment->comment, 0, 250, '...', 'UTF-8' );
                }                
                // CommentテーブルへDB登録
                $this->getCommentTable()->saveComment($comment);

                // フラッシュマネージャへ受け渡すメッセージを追加する
                $this->flashMessenger()->addMessage( "ほっこりを投稿しました" );

                return $this->redirect()->toRoute('ec', array(
                    'controller' => 'comment',
                    'action' => 'index'
                ));
            }
            // バリデーションが通らなかった場合、エラーメッセージを表示
            else {
                $message = [];
                foreach ($form->getMessages() as $messageId => $message){
//                    var_dump($messageId);
                    echo "<br>";
//                    var_dump($message);                
                }
            }
        }
        
        // POST投稿でないリクエストの場合、もしくはフィルタチェックで弾かれた場合
        // コメント投稿画面を表示
        $values = array(
            'form' => $form,
        );
        $view = new ViewModel( $values );

        return $view;
        
    }

    // コメント詳細画面
    public function detailAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('ec', array(
                'controller' => 'comment',
                'action'     => 'index'
            ));
        }

        $comment = $this->getCommentTable()->getComment($id);
        $comment_user = $this->getUserTable()->getUser($comment->user_id);
        
        // フラッシュマネージャーを取得
        $flashMessenger = $this->flashMessenger();
 
        // 現在の要求中に追加されたものがあるのかチェック
        $message = '';
        if( $flashMessenger->hasMessages() ){
 
            // メッセージの取得（配列）
            $message_array = $flashMessenger->getMessages();

            // 初めのメッセージを取得
           $message = $message_array[0];
        }

        // ログインユーザ情報を取得する
        $auth = new Auth();
        $login_user = $auth->getLoginUser();

        $form = new CommentForm();
        $form->get('submit')->setValue('ほっこりした');
        $form->get('parent_id')->setValue($id);
        $form->get('user_id')->setValue($login_user->id);
        
        $request = $this->getRequest();
        if ($request->isPost()) {

            //  ログインしていなければログイン画面へ遷移させる
            if( ! $login_user->id ){

                // フラッシュマネージャへ受け渡すメッセージを追加する
                $this->flashMessenger()->addMessage( "ログインが必要です" );

                return $this->redirect()->toRoute('ec', array(
                    'controller' => 'index',
                    'action' => 'login',
                ));
            }

            //  トランスレーターに翻訳ファイルを読み込ませる
//            $this->getTranslator()->addTranslationFile('phparray', 'C:\xampp\Library\ZendFramework-2.2.2\resources\languages\ja\Zend_Validate.php');

            $comment_res = new Comment();

            $form->setInputFilter($comment_res->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $comment_res->exchangeArray($form->getData());
                $this->getCommentTable()->saveComment($comment_res);

                // フラッシュマネージャへ受け渡すメッセージを追加する
                $this->flashMessenger()->addMessage( "ほっこりに共感しました" );

                return $this->redirect()->toRoute('ec', array(
                    'controller' => 'comment',
                    'action'     => 'detail',
                    'id'         => $id,
                ));
            }
        }
        
        $values = array(
            'comment'        => $comment,
            'comment_user'   => $comment_user,
            'comment_res'    => $this->getCommentTable()->fetchAllWithUser( array( 'parent_id' => $id ) ),
            'form'    => $form,
            'message' => $message,
        );
        $view = new ViewModel( $values );

        return $view;
        
    }
    
    /*  
     * トランスレーターを取得
     */
    public function getTranslator()
    {
        if (!$this->translator) {
            $sm = $this->getServiceLocator();
            $this->translator = $sm->get('translator');
        }
        return $this->translator;
    }

    /*  
     * コメントテーブルを取得
     */
    public function getCommentTable()
    {
        if (!$this->commentTable) {
            $sm = $this->getServiceLocator();
            $this->commentTable = $sm->get('Ec\Model\CommentTable');
        }
        return $this->commentTable;
    }

    /* 
     * ユーザテーブルを取得
     */
    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Ec\Model\UserTable');
        }
        return $this->userTable;
    }
    
    
    
}
