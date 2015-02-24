<?php

/**
 * namespace definition and usage
 */
namespace Rentals\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Delete form factory
 * 
 * @package    Rentals
 */
class DeleteFormFactory implements FactoryInterface
{
    /**
     * Create Service Factory
     * 
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $inputFilterManager = $serviceLocator->get('InputFilterManager');
        
        $form = new RentalsForm('delete');
        $form->addIdElement();
        $form->addCsrfElement();
        $form->addSubmitElement('delete', 'Löschen');
        $form->addSubmitElement('cancel', 'Abbrechen');
        $form->setInputFilter($inputFilterManager->get('Rentals\Filter\Rentals'));
        $form->setValidationGroup(array('id', 'delete', 'cancel'));
        return $form;
    }
}
