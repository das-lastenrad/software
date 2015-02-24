<?php

/**
 * namespace definition and usage
 */
namespace User\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Show user widget view helper factory
 * 
 * Generates the Show user widget view helper object
 * 
 * @package    User
 */
class UserShowWidgetFactory implements FactoryInterface
{
    /**
     * Create Service Factory
     * 
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sm       = $serviceLocator->getServiceLocator();
        $identity = $sm->get('User\Auth\Service')->getIdentity();
        $service  = $sm->get('User\Service\User');
        $helper   = new UserShowWidget($identity, $service);
        return $helper;
    }
}
