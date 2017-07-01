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
     * �ǽ��ɽ�������ڡ���
     */
    public function indexAction()
    {
        // �ꥯ�����ȥѥ�᡼��
        // GET
//        $getValue = $this->params()->fromQuery('test_param');
//        // POST
//        $postValue = $this->params()->fromPost('test_param');
//        var_dump($getValue);
        
        return new ViewModel();
    }

    /* 
     *  �ǡ����١�����³�����ץ��������
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
     *  �ȥ�󥹥졼���������
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
     *  �桼���ơ��֥�����
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
     * �����󥢥������
     */
    public function loginAction()
    {
       // �ե�å���ޥ͡����㡼�����
        $flashMessenger = $this->flashMessenger();
 
        // ���ߤ��׵�����ɲä��줿��Τ�����Τ������å�
        $message = '';
        if( $flashMessenger->hasMessages() ){
 
            // ��å������μ����������
            $message_array = $flashMessenger->getMessages();

            // ���Υ�å����������
           $message = $message_array[0];
        }

        $form = new UserForm();
        $form->get('submit')->setValue('LOGIN');

        $request = $this->getRequest();
        if ($request->isPost()) {

            // �����ե�����ϻȤ�ʤ�
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
     * ��������
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
