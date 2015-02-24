<?php

/**
 * namespace definition and usage
 */
namespace User\Authentication;

use Zend\Crypt\Password\Bcrypt;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Database authentication with bcrypt factory
 * 
 * Generates the user authentication with a database and bcrypt object
 * 
 * @package    User
 */
class DbBcryptAdapterFactory implements FactoryInterface
{
    /**
     * Create Service Factory
     * 
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $table   = $serviceLocator->get('User\Table\User');
        $bcrypt  = new Bcrypt(array('cost' => '12'));
        $adapter = new DbBcryptAdapter($table, $bcrypt);
        return $adapter;
    }
}
