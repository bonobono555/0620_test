<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ec\Form;

use Zend\Form\Form;
use Zend\Form\Element;  //csrf用のタグの出力に利用

/**
 * Description of RegisterForm
 *
 * @author onoe-azusa
 */

class RegisterForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'email',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
            ),
            'options' => array(
                'label' => 'password',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'signup'
            ),
        ));
        $this->add(new Element\Csrf('csrf'));
    }
}
