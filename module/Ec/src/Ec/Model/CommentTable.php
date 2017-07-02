<?php


namespace Ec\Model;

use Zend\Db\TableGateway\TableGateway;
// SQLʸ���갷�����饹
use Zend\Db\Sql\Sql;

/*
 * CommentTable �ǡ����١�����³��ǥ�
 */
class CommentTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        
    }
    
    // ���ƤΥ쥳���ɤ����
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    // selectʸ
    public function getComment($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    /* �쥳���ɿ�������������
     * 
     * @param array $comment �����Ⱦ����ǥ륯�饹
     */
    public function saveComment(Comment $comment)
    {
        // ������������֤�����
        $data = array(
            'id' => $comment->id,
            'title' => $comment->title,
            'comment' => $comment->comment,
            'parent_id' => $comment->parent_id,
            'user_id' => $comment->user_id,
            
            );
        $id = (int)$comment->id;
        
        // ID���ʤ����INSERT
        if ($id == 0) {
            $this->tableGateway->insert($data);
        // ID�������UPDATE    
        } else {
            // ID������Ȥ˥쥳���ɼ���
            if ( $this->getComment($id)){
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exit');
            }
        }
    }
    
    // �쥳���ɺ��
    public function deleteComment($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    /*
     * �桼������򶦤˼�������
     */
    public function fetchAllWithUser( $where = null )
    {
        $sql = new Sql( $this->tableGateway->adapter );
        $select = $sql->select();
        $select->from('comment');
        $select->where( $where );
        $select->order( 'id desc' );
        $select->join('user', 'comment.user_id=user.id',
            array(
                'user_name'     => 'name',
                'user_email'    => 'email',
                'user_password' => 'password',
                'user_comment'  => 'comment',
                'user_url'      => 'url',
            )
        );

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        return $result;
    }
    
    
}
