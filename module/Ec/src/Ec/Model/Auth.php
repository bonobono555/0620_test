<?php

namespace Ec\Model;

// AUTH�����ץ����饹���ɤ߹���
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
// AUTH�����ӥ�
use Zend\Authentication\AuthenticationService;
// AUTH���å���󥹥ȥ졼��
use Zend\Authentication\Storage\Session as SessionStorage;
// �桼����ǥ�
use Ec\Model\User;

/**
 * ������ǧ���ѥ�ǥ�
 *
 * @author onoeadusa
 */
class Auth
{
    // ǧ�ڥ��֥�������
    protected $auth;

    // ���󥹥ȥ饯��
    public function __construct()
    {
        // ǧ�ھ�����������
        $this->auth = new AuthenticationService( new SessionStorage( 'user_auth' ) );
    }
    
    // �������ѥ᥽�å�
    public function login( User $user, $dbAdapter )
    {
        // ǧ�ڥ����ץ��������
        $authAdapter = new AuthAdapter( $dbAdapter );

        // ǧ�ڹ������Ƥ�����
        $authAdapter
            // ��������ơ��֥�̾
            ->setTableName('user')

            // ǧ���оݥ����
            ->setIdentityColumn( 'email' )

            // ǧ�ڥѥ���ɥ����
            ->setCredentialColumn( 'password' );

        // �ե����फ��������ͤ򥻥åȤ���
        $authAdapter
            // ǧ���о���
            ->setIdentity( $user->email )

            // ǧ�ڥѥ����
            ->setCredential( $user->password );

        // ǧ�ڥ������¹Ԥ���ǧ�ڷ�̤����
        $result = $this->auth->authenticate( $authAdapter );

        // ǧ������(isValid()��ǧ�ڷ�̤Υ����å���
        if( $result->isValid() ){

            // ǧ�ڥ��ȥ졼�����������
            $storage = $this->auth->getStorage();

            // ��̥��֥������Ȥ򥹥ȥ졼���˽񤭹���
            $storage->write( $authAdapter->getResultRowObject() );

            return true;
        }
        // ǧ�ڼ���
        else{
            return false;
        }
    }
    
    /*
     * ��������ֳ�ǧ
     */
    public function getLoginUser()
    {
        $user = new User();

        // �������ǧ
        if( $this->auth->hasIdentity() ){
            // �����������������
            $user = $this->auth->getIdentity();
        }
        return $user;
    }
    
    /*
     * ��������
     */
    public function logout()
    {
        // ���ȥ졼����ǧ�ھ�����˴�����
        $this->auth->getStorage()->clear();
        $this->auth->clearIdentity();
    }
    
        
}
