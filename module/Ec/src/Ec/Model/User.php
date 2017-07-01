<?php

namespace Ec\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

// 1つのオブジェクトが１つのレコード内容と一致するユーザー情報を保持するモデルクラス
class User implements InputFilterAwareInterface
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $comment;
    public $url;
    
    protected $inputFilter;
    
    // メンバ変数を初期化する処理
    public function exchangeArray($data)
    {
        // 引数に配列を想定し、目的とする添え字に対して値があれば、値をメンバに格納。
        // なければデフォルト値をメンバへ格納しています。
        // デフォルト値はデータベースの絡むの型と揃える
        $this->id = (isset($data['id'])) ? $data['id'] : 0;
        $this->name = (isset($data['name'])) ? $data['name'] : '';
        $this->email = (isset($data['email'])) ? $data['email'] : '';
        $this->password = (isset($data['password'])) ? $data['password'] : '';
        $this->comment = (isset($data['comment'])) ? $data['comment'] : '';
        $this->url = (isset($data['url'])) ? $data['url'] : '';

    }
    
    // implementsすることでInputFilterAwareInterface内にあるsetInputFilterの実装が義務となる
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");    
    }

    // implementsすることでInputFilterAwareInterface内にあるgetInputFilterの実装が義務となる
    public function getInputFilter()
    {
        if (!$this->inputFilter)
        {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            
            // id
            $inputFilter->add($factory->createInput(array(
                'name' => 'id',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));
            
            // name
            $inputFilter->add($factory->createInput(array(
                'name' => 'name',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' =>array(
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'mac' => 32,
                        ),
                    ),
                ),
            )));
            
            // email
            $inputFilter->add($factory->createInput(array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' =>array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'mac' => 64,
                        ),
                    ),
                ),
            )));
            
            // password
            $inputFilter->add($factory->createInput(array(
                'name' => 'password',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' =>array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'mac' => 16,
                        ),
                    ),
                ),
            )));
            
            // comment
            $inputFilter->add($factory->createInput(array(
                'name' => 'comment',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' =>array(
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'mac' => 5000,
                        ),
                    ),
                ),
            )));

            // url
            $inputFilter->add($factory->createInput(array(
                'name' => 'url',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' =>array(
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'mac' => 64,
                        ),
                    ),
                ),
            )));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
        
    }
    
}
