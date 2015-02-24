<?php

/**
 * namespace definition and usage
 */
namespace Rentals\Filter;

use Zend\InputFilter\InputFilter;

/**
 * Rentals filter
 * 
 * @package    Rentals
 */
class RentalsFilter extends InputFilter
{
    /**
     * Build filter
     */
    public function init()
    {
        $this->add(array(
            'name'       => 'name',
            'required'   => true,
            'filters'    => array(
                array('name' => 'StringTrim'),
                array('name' => 'StripTags'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8', 'min' => 5, 'max' => 128,
                        'message'  => 'Überschrift nur 5 - 128 Zeichen erlaubt',
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name'       => 'description',
            'required'   => false,
            'filters'    => array(
                array('name' => 'StringTrim'),
                array('name' => 'StringHtmlPurifier'),
            ),
        ));
        
        $this->add(array(
            'name'       => 'from',
            'required'   => true,
            'filters'    => array(
                //array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'Date',
                    'options' => array(
                        'message'  => 'Die Eingabe ist keine gültiges Datum',
                    	'format' => 'd.m.Y H:i'
                    ),
                ),
            ),
        ));
        
        $this->add(array(
        		'name'       => 'to',
        		'required'   => true,
        		'filters'    => array(
        				//array('name' => 'StringTrim'),
        		),
        		'validators' => array(
        				array(
        						'name'    => 'Date',
        						'options' => array(
        								'message'  => 'Die Eingabe ist keine gültiges Datum',
        								'format' => 'd.m.Y H:i'
        						),
        				),
        		),
        ));

    }
}
