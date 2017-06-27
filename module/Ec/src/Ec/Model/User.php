<?php

namespace Ec\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User 
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $comment;
    public $url;
    
    protected $inputFilter;
        
    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : 0;
        $this->name = (isset($data['name'])) ? $data['name'] : '';
        $this->email = (isset($data['email'])) ? $data['email'] : '';
        $this->email = (isset($data['email'])) ? $data['password'] : '';
        $this->email = (isset($data['email'])) ? $data['comment'] : '';
        $this->email = (isset($data['email'])) ? $data['url'] : '';

    }
    
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");    
    }

    public function getInputFilter($param)
    {
        if (!$this->inputFilter)
        {
            $inputFiter = new InputFilter();
            $factory = new InputFactory();
            
            // id
            $inputFiter->add($factory->createInput(array(
                'name' => 'id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));
            
            // name
            $inputFiter->add($factory->createInput(array(
                'name' => 'name',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
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
            $inputFiter->add($factory->createInput(array(
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
            $inputFiter->add($factory->createInput(array(
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
            $inputFiter->add($factory->createInput(array(
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
            $inputFiter->add($factory->createInput(array(
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
            
            $this->inputFilter = $inputFiter;
        }
        
        return $this->inputFilter;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
        
    }
    
}
