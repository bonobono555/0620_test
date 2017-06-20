<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Ec\Model;


/**
 * Description of Login
 *
 * @author onoe-azusa
 */
class Login implements InputFilterAwareInterface
{
    //put your code here
    public $email;
    public $password;

     public function exchangeArray(array $data)
    {
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->password = (isset($data['password'])) ? $data['password'] : null;
    }
    
}
