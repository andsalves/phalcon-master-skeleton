<?php

namespace Main\Form;

use Main\Model\BaseModel;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Hidden;

/**
 * A geniric implementation of Form of Phalcon, to example
 *
 * @author Ands
 */
class GenericForm extends Form {

    public function __construct($entity = null, $userOptions = null) {
        parent::__construct($entity, $userOptions);

        $this->add(new Text('sample', array(
            'class' => 'form-control',
            'placeholder' => 'Some input'
        )));
        $this->add(new TextArea('description', array(
            'class' => 'form-control',
            'placeholder' => 'A detailed description'
        )));
        $this->add(new Email('email', array(
            'class' => 'form-control',
            'placeholder' => 'Please enter a e-mail',
        )));
        $this->add(new Select('id_responsavel',
            BaseModel::_find(),
            array(
                "using" => [
                    "_id",
                    "name",
                ],
                "useEmpty" => true,
                'emptyText' => 'Select some item',
                'class' => 'form-control'
            )
        ));
        $this->add(new Numeric('some_number', []));
        $this->add(new Hidden('some_hidden', []));
    }

}