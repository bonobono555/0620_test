<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Ec\Model\Data;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;

class TableModel extends TableGateway
{
    // テーブル名
    protected $name;

    public function __construct($adapter = null)
    {
        if ($adapter == null) {
            $adapter = GlobalAdapterFeature::getStaticAdapter();
        }
        parent::__construct($this->name, $adapter);
    }

    
    /*
     * DB練習用
     */
    
    public function getRecord($id)
    {
        // Selectのインスタンスを取得
        $select = $this->getSql()->select();
        // WHERE条件指定
        $select->where(array(
            'id' => $id,
        ));
        // 実行
        $rowset = $this->selectWith($select);
        return $rowset;
    }  
}