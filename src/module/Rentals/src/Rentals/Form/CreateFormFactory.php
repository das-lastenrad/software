<?php

/**
 * namespace definition and usage
 */
namespace Rentals\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Create form factory
 * 
 * @package    Rentals
 */
class CreateFormFactory implements FactoryInterface
{
    /**
     * Create Service Factory
     * 
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $inputFilterManager = $serviceLocator->get('InputFilterManager');
        
        $rentalsEntity   = $serviceLocator->get('Rentals\Entity\Rentals');
        
        $form = new RentalsForm('create');
        $form->addCsrfElement();
        $form->addFromElement();
        $form->addToElement();
        $form->addDescriptionElement();
        $form->addSubmitElement('save', 'Speichern');
        $form->addSubmitElement('cancel', 'Abbrechen');
        $form->setInputFilter($inputFilterManager->get('Rentals\Filter\Rentals'));
        $form->setValidationGroup(array(
            'from', 'to', 'save', 'cancel', 'description'
        ));
        return $form;
    }
}
