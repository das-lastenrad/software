<?php

/**
 * namespace definition and usage
 */
namespace User\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * User is allowed view helper factory
 * 
 * Generates the Is allowed view helper object
 * 
 * @package    User
 */
class UserShowCounterFactory implements FactoryInterface
{
    /**
     * Create Service Factory
     * 
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sm     = $serviceLocator->getServiceLocator();
        $acl    = $sm->get('User\Acl\Service');
        $service  = $sm->get('User\Service\User');
        $helper = new UserShowCounter($service);
        return $helper;
    }
}
