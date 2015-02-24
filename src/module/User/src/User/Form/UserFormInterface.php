<?php

/**
 * namespace definition and usage
 */
namespace User\Form;

use Zend\Form\FormInterface;

/**
 * User Form interface
 * 
 * @package    User
 */
interface UserFormInterface extends FormInterface
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
     * Add role element
     */
    public function addRoleElement($options = array(), $name = 'role');
    
    /**
     * Add email element
     */
    public function addEmailElement($name = 'email');
    
    /**
     * Add pass element
     */
    public function addPassElement($name = 'pass');
    
    /**
     * Add firstname element
     */
    public function addFirstnameElement($name = 'firstname');
    
    /**
     * Add lastname element
     */
    public function addLastnameElement($name = 'lastname');
    
    /**
     * Add submit element
     */
    public function addSubmitElement($name = 'save', $label = 'Speichern');
}
