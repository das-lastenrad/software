<?php

/**
 * namespace definition and usage
 */
namespace Rentals\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\View\Helper;


/**
 * Create rentals service factory
 * 
 * @package    Rentals
 */
class RentalsServiceFactory implements FactoryInterface
{
    /**
     * Create Service Factory
     * 
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {   	
        $rentalsTable   = $serviceLocator->get('Rentals\Table\Rentals');
        $openinghoursTable   = $serviceLocator->get('Rentals\Table\Openinghours');
        $identity = $serviceLocator->get('User\Auth\Service')->getIdentity();
        $transport = $serviceLocator->get('mail.transport');
        $service    = new RentalsService($rentalsTable, $openinghoursTable, $identity, $transport);
        return $service;
    }
}
