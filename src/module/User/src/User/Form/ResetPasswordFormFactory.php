<?php

/**
 * namespace definition and usage
 */
namespace User\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Register form factory
 * 
 * @package    User
 */
class ResetPasswordFormFactory implements FactoryInterface
{
    /**
     * Create Service Factory
     * 
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $inputFilterManager = $serviceLocator->get('InputFilterManager');
        
        $form = new UserForm('register');
        $form->addCsrfElement();
        $form->addEmailElement();
        $form->addSubmitElement('save', 'Neues Passwort anfordern');
        $form->addSubmitElement('cancel', 'Abbrechen');
        $form->setInputFilter($inputFilterManager->get('User\Filter\User'));
        $form->setValidationGroup(
            array('email', 'save', 'cancel')
        );
        return $form;
    }
}
