<?php

namespace Ec\Model;

// AUTHアダプタクラスを読み込む
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
// AUTHサービス
use Zend\Authentication\AuthenticationService;
// AUTHセッションストレージ
use Zend\Authentication\Storage\Session as SessionStorage;
// ユーザモデル
use Ec\Model\User;

/**
 * ログイン認証用モデル
 *
 * @author onoeadusa
 */
class Auth
{
    // 認証オブジェクト
    protected $auth;

    // コンストラクタ
    public function __construct()
    {
        // 認証情報を取得する
        $this->auth = new AuthenticationService( new SessionStorage( 'user_auth' ) );
    }
    
    // ログイン用メソッド
    public function login( User $user, $dbAdapter )
    {
        // 認証アダプターを作成
        $authAdapter = new AuthAdapter( $dbAdapter );

        // 認証項目内容を設定
        $authAdapter
            // 検索するテーブル名
            ->setTableName('user')

            // 認証対象カラム
            ->setIdentityColumn( 'email' )

            // 認証パスワードカラム
            ->setCredentialColumn( 'password' );

        // フォームからの入力値をセットする
        $authAdapter
            // 認証対象値
            ->setIdentity( $user->email )

            // 認証パスワード
            ->setCredential( $user->password );

        // 認証クエリを実行し、認証結果を取得
        $result = $this->auth->authenticate( $authAdapter );

        // 認証成功(isValid()で認証結果のチェック）
        if( $result->isValid() ){

            // 認証ストレージを取得する
            $storage = $this->auth->getStorage();

            // 結果オブジェクトをストレージに書き込む
            $storage->write( $authAdapter->getResultRowObject() );

            return true;
        }
        // 認証失敗
        else{
            return false;
        }
    }
    
    /*
     * ログイン状態確認
     */
    public function getLoginUser()
    {
        $user = new User();

        // ログイン確認
        if( $this->auth->hasIdentity() ){
            // ログイン情報を取得する
            $user = $this->auth->getIdentity();
        }
        return $user;
    }
    
    /*
     * ログアウト
     */
    public function logout()
    {
        // ストレージと認証情報を破棄する
        $this->auth->getStorage()->clear();
        $this->auth->clearIdentity();
    }
    
        
}
