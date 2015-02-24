<?php

/**
 * namespace definition and usage
 */
namespace Rentals\Form;

use Zend\Form\FormInterface;

/**
 * Rentals Form interface
 * 
 * @package    Rentals
 */
interface RentalsFormInterface extends FormInterface
{
    /**
     * Add csrf element
     */
    public function addCsrfElement($name = 'tick');
        
    /**
     * Add id element
     */
    public function addIdElement($name = 'id');
        
    /**
     * Add name element
     */
    public function addNameElement($name = 'name');
    
    public function addFromElement($from = 'from');
    
    public function addToElement($to = 'to');
    
    /**
     * Add description element
     */
    public function addDescriptionElement($name = 'description');
    
    
    /**
     * Add submit element
     */
    public function addSubmitElement($name = 'save', $label = 'Speichern');
}
