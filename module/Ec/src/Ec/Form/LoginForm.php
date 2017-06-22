<?php
namespace Ec\Form;

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * ログイン機能用のフォーム
 *
 * @author onoe-azusa
 */
class LoginForm extends Form
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
                'value' => 'login',
            ),
        ));
        $this->add(new Element\Csrf('csrf'));
    }
}
