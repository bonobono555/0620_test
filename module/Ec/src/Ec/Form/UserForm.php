<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ec\Form;

use Zend\Form\Form;

/**
 * ユーザーテーブルのフォーム
 *
 * @author onoeadusa
 */
class UserForm  extends Form
{
    public function __construct($name = null) 
    {
        parent::__construct('user');
        
        // アトリビュート
        $this->setAttribute('method', 'post');
        
        // フォーム要素の追加
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                // view表示
                'label' => 'お名前'
            ),
        ));
        
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'email',
            ),
            'options' => array(
                'label' => 'メールアドレス(必須)'
            ),
        ));
        
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
            ),
            'options' => array(
                'label' => 'パスワード(必須)'
            ),
        ));
        
        $this->add(array(
            'name' => 'comment',
            'attributes' => array(
                'type' => 'textarea',
                'rows' => 5,
                'cols' => 5,
            ),
            'options' => array(
                'label' => '自己紹介'
            ),
        ));
        
        $this->add(array(
            'name' => 'url',
            'attributes' => array(
                'type' => 'url',
            ),
            'options' => array(
                'label' => 'URL'
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Go',
                'label' => '登録'
            ),
        ));
    }
}
