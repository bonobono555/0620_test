<?php

namespace Ec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Ec\Form\RegisterForm;
use Ec\Model\People;
use Ec\Form\LoginForm;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel; //viewモデル

/**
 * ログイン機能
 *
 * @author onoe-azusa
 */
class AuthController extends AbstractActionController
{
    // メンバ変数
    protected $peopleTable;
    protected $dbAdapter;

    public function signupAction()
    {
        // RefisterFormを呼び出す
        $form = new RegisterForm();
        // リクエストメソッド
        $request = $this->getRequest();

        // ポストされている場合
        if ($request->isPost()) {
            $people = new People();
            // getInputFilterでフィルターを実行（バリデーション的な感じ？）
            $form->setInputFilter($people->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $people->exchangeArray($form->getData());
                // DB登録
                $this->getPeopleTable()->registerPeople($people);
                exit('登録完了！以下の処理保留');
            }
        }
        // viewにformの値を渡す
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
     * ログインアクション
     */
    public function loginAction()
    {
        // viewモデル
        $view = new ViewModel;

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
                // 対応するテーブル名、ユニークなカラム名、パスワードカラム名
                $authAdapter->setTableName('people')->setIdentityColumn('email')->setCredentialColumn('password');
                // 受け取ったデータ
                $authAdapter->setIdentity($peopleData['email'])->setCredential($peopleData['password']);
                // 必須ではないがAuthenticationServiceを利用
                $auth = new AuthenticationService();
                // 認証処理を実行し、結果を取得
                $result = $auth->authenticate($authAdapter);
                // ログイン失敗でもtrueにいってしまう
                if ($result->isValid()) {
                    return $this->loginTrueAction();
                } else {
                    return $this->loginFalseAction();
                }
                exit(var_dump($result->isValid()));
            }
        }
        // viewにformの値を渡す
        return array('form' => $form);
    }
    
    /*
     * データベースと接続するアダプターを取得
     */
    public function getDbAdapter()
    {
        if (!$this->dbAdapter) {
            $sm = $this->getServiceLocator();
            $this->dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->dbAdapter;
    }
    
    /*
     * 
     */
    public function loginTrueAction()
    {
        return new ViewModel();        
    }
    
    /*
     * 
     */
    public function loginFalseAction()
    {
        return new ViewModel();        
    }
}
