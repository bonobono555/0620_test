<?php

namespace Ec\Form;

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;

/**
 * フォーム練習用のフォーム
 *
 * @author onoe-azusa
 */
class ContractForm extends Form
{
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
