<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Listener\ApplicationListener;
use Zend\EventManager\EventInterface;
use Zend\Filter\StaticFilter;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Config\SessionConfig;
use Zend\Validator\StaticValidator;




class Module implements 
	BootstrapListenerInterface,
	ConfigProviderInterface,
	AutoloaderProviderInterface
{

   	public function onBootstrap(EventInterface $e)
	{
		// get managers
		$eventManager = $e->getApplication()->getEventManager();

		/*
		$eventManager->attach(
				'render', array($this, 'registerJsonStrategy'), 100
		);
		*/
		
		
		$serviceManager = $e->getApplication()->getServiceManager();
	
		// attach module listener
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);
	
		// add application listener
		/*
		if ($e->getRequest())
		{
			$uri = $e->getRequest()->getUri();
			if (false === strpos($uri, 'json')) {
				$eventManager->attachAggregate(new ApplicationListener());
			}				
		}
		*/
		$eventManager->attachAggregate(new ApplicationListener());
		
	
		// get config
		$config = $serviceManager->get('config');
	
		// configure session
		$sessionConfig = new SessionConfig();
		$sessionConfig->setOptions($config['session']);
		
		// get filter and validator manager
		$filterManager = $serviceManager->get('FilterManager');
		$validatorManager = $serviceManager->get('ValidatorManager');
		
		// add custom filters and validators
		StaticFilter::setPluginManager($filterManager);
		StaticValidator::setPluginManager($validatorManager);
	
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    

    public function getAutoloaderConfig()
    {
    	
    	return array(
    			'Zend\Loader\ClassMapAutoloader' => array(
    					'application' => __DIR__ . '/autoload_classmap.php',
    			),
    			'Zend\Loader\StandardAutoloader' => array(
    					'namespaces' => array(
    							__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
    					),
    			),
    	);
    	
    }
    
    public function registerJsonStrategy($e)
    {
    	$controller = $e->getRouteMatch()->getParam('controller');
    	
    	if (false === strpos($controller, 'json')) {
    		return;
    	}
    	
    	$serviceManager = $e->getApplication()->getServiceManager();
    	$view = $serviceManager->get('Zend\View\View');
    	$jsonStrategy = $serviceManager->get('ViewJsonStrategy');
    	
    	$view->getEventManager()->attach($jsonStrategy, 100);
    }
    
    
}
