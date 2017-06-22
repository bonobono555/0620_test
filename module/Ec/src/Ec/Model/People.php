<?php

namespace Ec\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
/**
 * ����Ū�ʥǡ������б����륯�饹
 */
class People 
{
    public $email;
    public $password;
    private $inputFilter;

    /*
     * ���󷿤Υǡ����������ꡢ�����ѿ��˳�Ǽ
     */
    public function exchangeArray(array $data)
    {
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->password = (isset($data['password'])) ? $data['password'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Excepion("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

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
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => '7',
                                    'max' => '255',
                                ),
                            ),
                        ),
            )));

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
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => '6',
                                    'max' => '20',
                                ),
                            ),
                        ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
