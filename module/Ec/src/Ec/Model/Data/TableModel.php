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
    // �ơ��֥�̾
    protected $name;

    public function __construct($adapter = null)
    {
        if ($adapter == null) {
            $adapter = GlobalAdapterFeature::getStaticAdapter();
        }
        parent::__construct($this->name, $adapter);
    }

    
    /*
     * DB������
     */
    
    public function getRecord($id)
    {
        // Select�Υ��󥹥��󥹤����
        $select = $this->getSql()->select();
        // WHERE������
        $select->where(array(
            'id' => $id,
        ));
        // �¹�
        $rowset = $this->selectWith($select);
        return $rowset;
    }  
}