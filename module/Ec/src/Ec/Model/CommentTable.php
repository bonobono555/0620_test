<?php


namespace Ec\Model;

use Zend\Db\TableGateway\TableGateway;
// SQL文を取り扱うクラス
use Zend\Db\Sql\Sql;

/*
 * CommentTable データベース接続モデル
 */
class CommentTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        
    }
    
    // 全てのレコードを取得
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    // select文
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
    
    /* レコード新規作成・更新
     * 
     * @param array $comment コメント情報モデルクラス
     */
    public function saveComment(Comment $comment)
    {
        // 引数を配列に置き換え
        $data = array(
            'id' => $comment->id,
            'title' => $comment->title,
            'comment' => $comment->comment,
            'parent_id' => $comment->parent_id,
            'user_id' => $comment->user_id,
            
            );
        $id = (int)$comment->id;
        
        // IDがなければINSERT
        if ($id == 0) {
            $this->tableGateway->insert($data);
        // IDがあればUPDATE    
        } else {
            // ID情報をもとにレコード取得
            if ( $this->getComment($id)){
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exit');
            }
        }
    }
    
    // レコード削除
    public function deleteComment($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    /*
     * ユーザ情報を共に取得する
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
