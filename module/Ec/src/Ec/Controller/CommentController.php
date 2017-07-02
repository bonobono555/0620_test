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
 * �Ǽ��Ĵ�������ȥ���
 *
 * @author onoeadusa
 */
class CommentController extends AbstractActionController
{
    protected $translator;
    protected $commentTable;
    protected $userTable;
    
    /*
     * �����Ȱ�������
     */
    public function indexAction()
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

        $values = array(    
            'comments' => $this->getCommentTable()->fetchAllWithUser( array( 'parent_id' => 0 ) ),
            'message' => $message,
        );
        $view = new ViewModel( $values );

        return $view;
    }
    
    /*
     * �����ȿ������
     */
    public function addAction()
    {
        // ������桼��������������
        $auth = new Auth();
        $login_user = $auth->getLoginUser();

        // �����󤷤Ƥ��ʤ���Х�������̤����ܤ�����
        if( ! $login_user->id ){

            // �ե�å���ޥ͡�����ؼ����Ϥ���å��������ɲä���
            $this->flashMessenger()->addMessage( "Login is required to post comments" );

            return $this->redirect()->toRoute('ec', array(
                'controller' => 'index',
                'action' => 'login',
            ));
        }
        
        // �ե����४�֥������Ⱥ���
        $form = new CommentForm();
        $form->get('submit')->setValue('comment post');
        $form->get('user_id')->setValue($login_user->id);
        
        $request = $this->getRequest();
        if ($request->isPost()) {

            //  �ȥ�󥹥졼�����������ե�������ɤ߹��ޤ���
//            $this->getTranslator()->addTranslationFile('phparray', 'C:\xampp\Library\ZendFramework-2.2.2\resources\languages\ja\Zend_Validate.php');

            $comment = new Comment();

            // �����ȥ�ǥ��ѤΥե��륿��ե�����Υե��륿�Ȥ�������
            $form->setInputFilter($comment->getInputFilter());
            // post��Ƥ��줿�ͤ�setData()�ˤƥե��������Ǥ�����            
            $form->setData($request->getPost());

            if ($form->isValid()) {
                // �����ȥ�ǥ���ͤ�����
                $comment->exchangeArray($form->getData());

                //  �����ȥ����Ƥ�̵��������ʸ����Ѥ���
                if( strlen( $comment->title ) <= 0 ){
                    $comment->title = mb_strimwidth( $comment->comment, 0, 250, '...', 'UTF-8' );
                }                
                // Comment�ơ��֥��DB��Ͽ
                $this->getCommentTable()->saveComment($comment);

                // �ե�å���ޥ͡�����ؼ����Ϥ���å��������ɲä���
                $this->flashMessenger()->addMessage( "Comment add" );

                return $this->redirect()->toRoute('ec', array(
                    'controller' => 'comment',
                    'action' => 'index'
                ));
            }
            // �Х�ǡ�������̤�ʤ��ä���硢���顼��å�������ɽ��
            else {
                $message = [];
                foreach ($form->getMessages() as $messageId => $message){
                    var_dump($messageId);
                    echo "<br>";
                    var_dump($message);                
                }
            }
        }
        
        // POST��ƤǤʤ��ꥯ�����Ȥξ�硢�⤷���ϥե��륿�����å����Ƥ��줿���
        // ��������Ʋ��̤�ɽ��
        $values = array(
            'form' => $form,
        );
        $view = new ViewModel( $values );

        return $view;
        
    }

    // �����Ⱦܺٲ���
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

        //  ������桼��������������
        $auth = new Auth();
        $login_user = $auth->getLoginUser();

        $form = new CommentForm();
        $form->get('submit')->setValue('Comment add');
        $form->get('parent_id')->setValue($id);
        $form->get('user_id')->setValue($login_user->id);
        
        $request = $this->getRequest();
        if ($request->isPost()) {

            //  �����󤷤Ƥ��ʤ���Х�������̤����ܤ�����
            if( ! $login_user->id ){

                // �ե�å���ޥ͡�����ؼ����Ϥ���å��������ɲä���
                $this->flashMessenger()->addMessage( "login hissu!" );

                return $this->redirect()->toRoute('ec', array(
                    'controller' => 'index',
                    'action' => 'login',
                ));
            }

            //  �ȥ�󥹥졼�����������ե�������ɤ߹��ޤ���
//            $this->getTranslator()->addTranslationFile('phparray', 'C:\xampp\Library\ZendFramework-2.2.2\resources\languages\ja\Zend_Validate.php');

            $comment_res = new Comment();

            $form->setInputFilter($comment_res->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $comment_res->exchangeArray($form->getData());
                $this->getCommentTable()->saveComment($comment_res);

                // �ե�å���ޥ͡�����ؼ����Ϥ���å��������ɲä���
                $this->flashMessenger()->addMessage( "Comment ADD" );

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
     * �ȥ�󥹥졼���������
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
     * �����ȥơ��֥�����
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
     * �桼���ơ��֥�����
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
