<?php

/**
 * namespace definition and usage
 */
namespace User\Listener;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use User\Acl\Service as AclService;
use Zend\Mvc\MvcEvent;

/**
 * User listener
 * 
 * 
 * @package    Application
 */
class UserListener implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * Attach to an event manager
     *
     * @param  EventManagerInterface $events
     * @param  integer $priority
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH, array($this, 'checkAcl'), 100
        );
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_RENDER, array($this, 'addAclToNavigation'), -100
        );
    }

    /**
     * Detach all our listeners from the event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Listen to the "route" event and check the user rights
     *
     * @param  MvcEvent $e
     * @return null
     */
    public function checkAcl(EventInterface $e)
    {
        // get route match, params and objects
        $routeMatch       = $e->getRouteMatch();
        $controllerParam  = $routeMatch->getParam('controller');
        $actionParam      = $routeMatch->getParam('action');
        $serviceManager   = $e->getApplication()->getServiceManager();
        $controllerLoader = $serviceManager->get('ControllerLoader');
        $acl              = $serviceManager->get('User\Acl\Service');
        
        // try to load current controller
        try {
            $controller = $controllerLoader->get($controllerParam);
        } catch (\Exception $exception) {
            return;
        }
        
        // check acl
        if (!$acl->isAllowed($controllerParam, $actionParam)) {
            // check for guests
            if ($acl->getRole() == 'guest') {
                $routeMatch->setParam('controller', 'user');
                $routeMatch->setParam('action', 'login');
            } else {
                $routeMatch->setParam('controller', 'user');
                $routeMatch->setParam('action', 'forbidden');
            }
        }
    }

    /**
     * Listen to the "render" event and add the acl to the navigation
     *
     * @param  MvcEvent $e
     * @return null
     */
    public function addAclToNavigation(EventInterface $e)
    {
        // get service manager, view manager and acl service
        $serviceManager = $e->getApplication()->getServiceManager();
        $viewManager    = $serviceManager->get('viewmanager');
        $aclService     = $serviceManager->get('User\Acl\Service');
        
        // set navigation plugin and set acl and role
        $plugin = $viewManager->getRenderer()->plugin('navigation');
        $plugin->setRole($aclService->getRole());
        $plugin->setAcl($aclService->getAcl());
    }
}
