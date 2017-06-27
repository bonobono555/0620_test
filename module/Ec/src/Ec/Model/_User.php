<?php

namespace Ec\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author onoe-azusa
 */
class User
{
    //put your code here
    public $id;
    public $name;
    public $email;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : 0;
        $this->name = (isset($data['name'])) ? $data['name'] : '';
        $this->email = (isset($data['email'])) ? $data['email'] : '';
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
}
