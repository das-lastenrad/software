<?php

namespace Lastenrad;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class Module implements 
	ConfigProviderInterface,
	AutoloaderProviderInterface,
	BootstrapListenerInterface
{
		
	protected $serviceLocator = null;
	
	public function onBootstrap(EventInterface $e)
	{
		$this->serviceLocator = $e->getApplication()->getServiceManager();
		$sharedEventManager = $this->serviceLocator->get('SharedEventManager');
		$sharedEventManager->attach(
				'Lastenrad\Service\LastenradService', 'set-blog-form', array($this, 'onFormSet')
		);

	}
	
	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}
	
	public function getAutoloaderConfig()
	{
		return array(
				'Zend\Loader\StandardAutoloader' => array(
						'namespaces' => array(
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
						),
				),
		);
		
	}
	
	public function onFormSet(EventInterface $e)
	{
		$type = $e->getParam('type', 'create');
		$service = $this->serviceLocator->get('Lastenrad\Service\Lastenrad');
		
	}

}






