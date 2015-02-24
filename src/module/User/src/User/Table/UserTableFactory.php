<?php

/**
 * namespace definition and usage
 */
namespace User\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * User table factory
 * 
 * Generates the User table object
 * 
 * @package    User
 */
class UserTableFactory implements FactoryInterface
{
    /**
     * Create Service Factory
     * 
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $entity  = $serviceLocator->get('User\Entity\User');
        $table   = new UserTable($adapter, $entity);
        return $table;
    }
}
