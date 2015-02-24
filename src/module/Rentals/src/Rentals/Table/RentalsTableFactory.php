<?php


/**
 * namespace definition and usage
 */
namespace Rentals\Table;

use Zend\Validator\IsInstanceOf;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Rentals table factory
 * 
 * Generates the Rentals table object
 * 
 * @package    Rentals
 */
class RentalsTableFactory implements FactoryInterface
{
    /**
     * Create Service Factory
     * 
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $entity  = $serviceLocator->get('Rentals\Entity\Rentals');
        $table   = new RentalsTable($adapter, $entity);
        return $table;
    }
}
