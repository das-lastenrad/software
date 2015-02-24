<?php

/**
 * namespace definition and usage
 */
namespace User\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Login form factory
 * 
 * @package    User
 */
class LoginFormFactory implements FactoryInterface
{
    /**
     * Create Service Factory
     * 
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $inputFilterManager = $serviceLocator->get('InputFilterManager');
        
        $form = new UserForm('login');
        $form->addCsrfElement();
        $form->addEmailElement();
        $form->addPassElement();
        $form->addSubmitElement('login', 'Einloggen');
        $form->setInputFilter($inputFilterManager->get('User\Filter\User'));
        $form->setValidationGroup(array('email', 'pass', 'login'));
        return $form;
    }
}
