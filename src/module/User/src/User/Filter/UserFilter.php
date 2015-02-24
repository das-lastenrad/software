<?php

/**
 * namespace definition and usage
 */
namespace User\Filter;

use Zend\InputFilter\InputFilter;

/**
 * User filter
 * 
 * @package    User
 */
class UserFilter extends InputFilter
{
    /**
     * Build filter
     */
    public function __construct()
    {
        $this->add(array(
            'name'       => 'role',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'InArray',
                    'options' => array(
                        'haystack' => array('guest', 'customer', 'staff', 'admin'),
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name'       => 'email',
            'required'   => true,
            'filters'    => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'EmailAddress',
                    'options' => array(
                        'useDomainCheck' => false,
                        'message'        => 'Keine gültige E-Mail-Adresse',
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name'       => 'pass',
            'required'   => true,
            'filters'    => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8', 
                        'min'      => 5, 
                        'max'      => 128,
                        'message'  => 'Passwort muss mindestens 5 Zeichen lang sein',
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name'       => 'firstname',
            'required'   => true,
            'filters'    => array(
                array('name' => 'StringTrim'),
                array('name' => 'StripTags'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8', 
                        'min'      => 1, 
                        'max'      => 64,
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name'       => 'lastname',
            'required'   => true,
            'filters'    => array(
                array('name' => 'StringTrim'),
                array('name' => 'StripTags'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8', 
                        'min'      => 1, 
                        'max'      => 64,
                    ),
                ),
            ),
        ));
    }
}
