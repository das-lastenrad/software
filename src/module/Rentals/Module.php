<?php

namespace Rentals;

use Zend\Filter\StaticFilter;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class Module implements 
    BootstrapListenerInterface,
    ConfigProviderInterface, 
    AutoloaderProviderInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;
    
    /**
     * Listen to the bootstrap event
     *
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        // set service locator
        $this->serviceLocator = $e->getApplication()->getServiceManager();
        
        // get shared event manager
        $sharedEventManager = $this->serviceLocator->get('SharedEventManager');
        
        // add form event
        $sharedEventManager->attach(
            'Rentals\Service\RentalsService', 
            'set-rentals-form', 
            array($this, 'onFormSet')
        );

        // add table event
        $sharedEventManager->attach(
            'Rentals\Service\RentalsService',
            'set-Rentals-table',
            array($this, 'onTableSet')
        );
    }
    
    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
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
    
    /**
     * Inject Form defined by type to PizzaService when triggered
     *
     * @param EventInterface
     */
    public function onFormSet(EventInterface $e)
    {
        $type = $e->getParam('type', 'create');
        $service = $this->serviceLocator->get('Rentals\Service\Rentals');
        $form    = $this->serviceLocator->get('Rentals\Form\\' . ucfirst($type));
        $service->setForm($form, $type);
    }
    
    

    
    /**
     * Inject Table defined by type to PizzaService when triggered
     *
     * @param EventInterface
     */
    public function onTableSet(EventInterface $e)
    {
        $type      = $e->getParam('type', 'rentals');
        $tableName = StaticFilter::execute($type, 'wordunderscoretocamelcase');
        $service   = $this->serviceLocator->get('Rentals\Service\Rentals');
        $form      = $this->serviceLocator->get('Rentals\Table\\' . $tableName);
        $service->setTable($form, $type);
    }
    
    
    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'mail.transport' => function ($serviceManager) {
    						$config = $serviceManager->get('Config');
    						$transport = new SmtpTransport();
    						$transport->setOptions(new SmtpOptions($config['mail']['transport']['options']));
    
    						return $transport;
    					},
    			),
    	);
    }
    
}
