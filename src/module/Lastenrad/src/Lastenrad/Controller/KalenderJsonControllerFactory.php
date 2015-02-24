<?php


namespace Lastenrad\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class KalenderJsonControllerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sm         = $serviceLocator->getServiceLocator();
        $service    = $sm->get('Lastenrad\Service\Lastenrad');
        $controller = new KalenderJsonController();
        $controller->setLastenradService($service);
        return $controller;
    }
}
