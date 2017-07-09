<?php


namespace Ec\Model;

use Zend\Db\TableGateway\TableGateway;

class UserTable
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
    public function getUser($id)
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
     * @param array $user ユーザ情報モデルクラス
     */
    public function saveUser(User $user)
    {
        // 引数を配列に置き換え
        $data = array(
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'comment' => $user->comment,
            'url' => $user->url,
            
            );
        $id = (int)$user->id;
        
        // IDがなければINSERT
        if ($id == 0) {
            $this->tableGateway->insert($data);
        // IDがあればUPDATE  
        } else {
            // ID情報をもとにレコード取得
            if ( $this->getUser($id)){
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exit');
            }
        }
    }
    
    // レコード削除
    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}
