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
use Ec\Model\User;
use Ec\Form\UserForm;
use Ec\Model\Auth as Auth;

class IndexController extends AbstractActionController
{
    protected $translator;
    protected $userTable;
    protected $dbAdapter;
    
    /*
     * 最初に表示されるページ
     */
    public function indexAction()
    {
        // リクエストパラメータ
        // GET
//        $getValue = $this->params()->fromQuery('test_param');
//        // POST
//        $postValue = $this->params()->fromPost('test_param');
//        var_dump($getValue);
        
        return new ViewModel();
    }

    /* 
     *  データベース接続アダプターを取得
     */
    public function getDBAdapter()
    {
        if (!$this->dbAdapter) {
            $sm = $this->getServiceLocator();
            $this->dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->dbAdapter;
    }

    /* 
     *  トランスレーターを取得
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
     *  ユーザテーブルを取得
     */
    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Ec\Model\UserTable');
        }
        return $this->userTable;
    }
    
    
    /*
     * ログインアクション
     */
    public function loginAction()
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

        $form = new UserForm();
        $form->get('submit')->setValue('LOGIN');

        $request = $this->getRequest();
        if ($request->isPost()) {

            // 翻訳ファイルは使わない
            // $this->getTranslator()->addTranslationFile('phparray', 'C:\xampp\Library\ZendFramework-2.2.2\resources\languages\ja\Zend_Validate.php');

            $user = new User();

            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $user->exchangeArray($form->getData());

                $auth = new Auth();
                if( $auth->login( $user, $this->getDBAdapter() ) ){
                    $this->flashMessenger()->addMessage( 'LOGIN complete!' );

                    return $this->redirect()->toRoute('ec', array(
                        'controller' => 'user',
                        'action' => 'index'
                    ));
                }
                else{
                    $message = 'LOGIN false';
                }
            }
        }
        $values = array(
            'form' => $form,
            'message' => $message,
        );
        $view = new ViewModel( $values );

        return $view;
    }

    /*
     * ログアウト
     */
    public function logoutAction()
    {
        $auth = new Auth();
        $auth->logout();

        $this->flashMessenger()->addMessage( 'logout complete' );

        return $this->redirect()->toRoute('ec', array(
            'controller' => 'index',
            'action' => 'login'
        ));
    }
    
    
    
    
}
