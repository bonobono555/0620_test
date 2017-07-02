<?php

namespace Ec\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * コメント投稿用フィルター
 *
 * @author onoeadusa
 */
class Comment implements InputFilterAwareInterface
{
    public $id;
    public $title;
    public $comment;
    public $parent_id;
    public $user_id;
    
    protected $inputFilter;
    
    // メンバ変数を初期化する処理
    public function exchangeArray($data)
    {
        // 引数に配列を想定し、目的とする添え字に対して値があれば、値をメンバに格納。
        // なければデフォルト値をメンバへ格納しています。
        // デフォルト値はデータベースの絡むの型と揃える
        $this->id = (isset($data['id'])) ? $data['id'] : 0;
        $this->title = (isset($data['title'])) ? $data['title'] : '';
        $this->comment = (isset($data['comment'])) ? $data['comment'] : '';
        $this->parent_id = (isset($data['parent_id'])) ? $data['parent_id'] : '';
        $this->user_id = (isset($data['user_id'])) ? $data['user_id'] : '';

    }
    
    /*
     * 
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /*
     * 
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'title',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 0,
                            'max'      => 255,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'comment',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 0,
                            'max'      => 5000,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'parent_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'user_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
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
