<?php
namespace Ec\Form;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Description of ContractForm
 *
 * @author onoe-azusa
 */
class ContractForm extends Form
{
    //put your code here
    protected $captcha;
 
    public function setCaptcha(CaptchaAdapter $captcha)
    {
        $this->captcha = $captcha;
    }
 
    public function prepareElements()
    {
        $this->add(array(
            'name' => 'name',
            'options' => array(
                'label' => 'Your name',
            ),
            'attributes' => array(
                'type'  => 'text',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Email',
            'name' => 'email',
            'options' => array(
                'label' => 'Your email address',
            ),
        ));
        $this->add(array(
            'name' => 'subject',
            'options' => array(
                'label' => 'Subject',
            ),
            'attributes' => array(
                'type'  => 'text',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'message',
            'options' => array(
                'label' => 'Message',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Captcha',
            'name' => 'captcha',
            'options' => array(
                'label' => 'Please verify you are human.',
                'captcha' => $this->captcha,
            ),
        ));
        $this->add(new Element\Csrf('security'));
        $this->add(array(
            'name' => 'send',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit',
            ),
        ));
    }
}
