<?php
namespace Ec\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;

/*
 * DB�ȥ��֥������Ȥ�Ĥʤ����饹
 */
class PeopleTable extends AbstractTableGateway
{
    protected $table = 'people';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;

        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new People());

        $this->initialize();
    }

    /**
     * People�ơ��֥��INSERT
     * 
     */
    public function registerPeople(People $people)
    {
        $data = array(
            'email'     => $people->email,
            'password'  => $people->password,
        );

        $this->insert($data);
    }
}