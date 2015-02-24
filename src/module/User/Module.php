<?php

/**
 * namespace definition and usage
 */
namespace User;

use User\Listener\UserListener;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * User module class
 * 
 * @package    User
 */
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
        // add route listener
        $e->getApplication()->getEventManager()->attachAggregate(new UserListener());
        
        // set service locator
        $this->serviceLocator = $e->getApplication()->getServiceManager();
        
        // get shared event manager
        $sharedEventManager = $this->serviceLocator->get('SharedEventManager');
        
        // add form event
        $sharedEventManager->attach('User\Service\UserService', 'set-user-form', array($this, 'onFormSet'));
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
     * Inject Form defined by type to UserService when triggered
     *
     * @param EventInterface
     */
    public function onFormSet(EventInterface $e)
    {
        $type = $e->getParam('type', 'login');
        $service = $this->serviceLocator->get('User\Service\User');
        $form    = $this->serviceLocator->get('User\Form\\' . ucfirst($type));
        $service->setForm($form, $type);
    }
}
