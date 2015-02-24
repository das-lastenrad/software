<?php

/**
 * namespace definition and usage
 */
namespace Pizza\Controller;

use Zend\Http\PhpEnvironment\Response;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Pizza\Service\PizzaServiceInterface;

/**
 * Admin controller
 * 
 * Handles the admin pages
 * 
 * @package    Pizza
 */
class AdminController extends AbstractActionController
{
    /**
     * @var PizzaServiceInterface
     */
    protected $pizzaService;
    
    /**
     * set the pizza service
     * 
     * @param PizzaServiceInterface
     */
    public function setPizzaService(PizzaServiceInterface $pizzaService)
    {
        $this->pizzaService = $pizzaService;

        return $this;
    }
    
    /**
     * Get the pizza service
     * 
     * @return PizzaServiceInterface
     */
    public function getPizzaService()
    {
        return $this->pizzaService;
    }
    
    /**
     * Handle admin page
     */
    public function indexAction()
    {
        // read page from route
        $page = (int) $this->params()->fromRoute('page');
        
        // set max pizza per page
        $maxPage = 10;
        
        // read data and pass to view
        return new ViewModel(array(
            'pizzaList' => $this->getPizzaService()->fetchList($page, $maxPage),
        ));
    }
    
    /**
     * Handle create page
     */
    public function createAction()
    {
        // prepare Post/Redirect/Get Plugin
        $prg = $this->prg(
            $this->url()->fromRoute(
                'pizza-admin/action', array('action' => 'create')
            ), 
            true
        );

        // check PRG plugin for redirect to send
        if ($prg instanceof Response) {
            return $prg;
            
        // check PRG for redirect to process
        } elseif ($prg !== false) {
            // check for cancel
            if (isset($prg['cancel'])) {
                // Redirect to list of pizzas
                return $this->redirect()->toRoute('pizza-admin');
            }
            
            // create with redirected data
            $pizza = $this->getPizzaService()->save($prg);
            
            // check pizza
            if ($pizza) {
                // add messages to flash messenger
                $this->flashMessenger()->addMessage(
                    $this->getPizzaService()->getMessage()
                );
                
                // Redirect to home page
                return $this->redirect()->toRoute(
                    'pizza-admin/action', 
                    array('action' => 'update', 'id' => $pizza->getId())
                );
            }
        }
        
        // get form
        $form = $this->getPizzaService()->getForm('create');
        
        // add messages to flash messenger
        if ($this->getPizzaService()->getMessage()) {
            $this->flashMessenger()->addMessage(
                $this->getPizzaService()->getMessage()
            );
        }
        
        // no post or registration unsuccesful
        return new ViewModel(array(
            'form' => $form,
        ));
    }
    
    /**
     * Handle update page
     */
    public function updateAction()
    {
        // read id from route and check
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('pizza-admin');
        }
        
        // prepare Post/Redirect/Get Plugin
        $prg = $this->prg(
            $this->url()->fromRoute('pizza-admin/action', array(), array(), true), 
            true
        );
        
        // check PRG plugin for redirect to send
        if ($prg instanceof Response) {
            return $prg;
            
        // check PRG for redirect to process
        } elseif ($prg !== false) {
            // check for cancel
            if (isset($prg['cancel'])) {
                // Redirect to list of pizzas
                return $this->redirect()->toRoute('pizza-admin');
            }
            
            // update with redirected data
            $pizza = $this->getPizzaService()->save($prg, $id);
            
            // check pizza
            if ($pizza) {
                // add messages to flash messenger
                $this->flashMessenger()->addMessage(
                    $this->getPizzaService()->getMessage()
                );
                
                // Redirect to update pizza
                return $this->redirect()->toRoute(
                    'pizza-admin/action', array(), array(), true
                );
            }
        }
        
        // get pizza
        $pizza = $this->getPizzaService()->fetchSingleById($id);
        
        // check pizza
        if ($pizza === false) {
            return $this->redirect()->toRoute('pizza-admin');
        }
        
        // get form and bind object
        $form = $this->getPizzaService()->getForm('update');
        
        //check prg
        if ($prg === false) {
            $form->bind($pizza);
        }
        
        // add messages to flash messenger
        if ($this->getPizzaService()->getMessage()) {
            $this->flashMessenger()->addMessage(
                $this->getPizzaService()->getMessage()
            );
        }
        
        // no post or update unsuccesful
        return new ViewModel(array(
            'form' => $form,
            'pizza' => $pizza,
        ));
    }
    
    /**
     * Handle delete page
     */
    public function deleteAction()
    {
        // read id from route and check
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('pizza-admin');
        }
        
        // prepare Post/Redirect/Get Plugin
        $prg = $this->prg(
            $this->url()->fromRoute('pizza-admin/action', array(), array(), true), 
            true
        );
        
        // check PRG plugin for redirect to send
        if ($prg instanceof Response) {
            return $prg;
            
        // check PRG for redirect to process
        } elseif ($prg !== false) {
            // check for cancel
            if (isset($prg['cancel'])) {
                // Redirect to list of pizzas
                return $this->redirect()->toRoute('pizza-admin');
            }
            
            // delete with redirected data
            $pizza = $this->getPizzaService()->delete($id);
            
            // check pizza
            if ($pizza) {
                // add messages to flash messenger
                $this->flashMessenger()->addMessage(
                    $this->getPizzaService()->getMessage()
                );
                
                // Redirect to list of pizza
                return $this->redirect()->toRoute('pizza-admin');
            }
        }
        
        // get pizza
        $pizza = $this->getPizzaService()->fetchSingleById($id);
        
        // check pizza
        if ($pizza === false) {
            return $this->redirect()->toRoute('pizza-admin');
        }
        
        // get form and bind object
        $form = $this->getPizzaService()->getForm('delete');
        
        //check prg
        if ($prg === false) {
            $form->get('id')->setValue($pizza->getId());
        }
        
        // add messages to flash messenger
        if ($this->getPizzaService()->getMessage()) {
            $this->flashMessenger()->addMessage(
                $this->getPizzaService()->getMessage()
            );
        }
        
        // no post or update unsuccesful
        return new ViewModel(array(
            'form' => $form,
            'pizza' => $pizza,
        ));
    }
}
