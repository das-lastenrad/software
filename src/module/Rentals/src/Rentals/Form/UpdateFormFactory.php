<?php

/**
 * namespace definition and usage
 */
namespace Rentals\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Update form factory
 * 
 * @package    Rentals
 */
class UpdateFormFactory implements FactoryInterface
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
        
        $form = new RentalsForm('update');
        $form->addIdElement();
        $form->addCsrfElement();
        $form->addFromElement();
        $form->addToElement();
        $form->addDescriptionElement();
        $form->addSubmitElement('save', 'Speichern');
        $form->addSubmitElement('cancel', 'Abbrechen');
        $form->setInputFilter($inputFilterManager->get('Rentals\Filter\Rentals'));
        $form->setValidationGroup(array(
            'id', 'from', 'to', 'description', 'save', 'cancel'
        ));
        return $form;
    }
}
