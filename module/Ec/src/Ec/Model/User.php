<?php

namespace Ec\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
//implements InputFilterAwareInterface
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
        $this->email = (isset($data['password'])) ? $data['password'] : '';
        $this->email = (isset($data['comment'])) ? $data['comment'] : '';
        $this->email = (isset($data['url'])) ? $data['url'] : '';

    }
    
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");    
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter)
        {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            
            // id
            $inputFilter->add($factory->createInput(array(
                'name' => 'id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));
            
            // name
            $inputFilter->add($factory->createInput(array(
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
