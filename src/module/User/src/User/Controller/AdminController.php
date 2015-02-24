<?php

/**
 * namespace definition and usage
 */
namespace User\Controller;

use Zend\Http\PhpEnvironment\Response;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use User\Service\UserServiceInterface;

/**
 * Admin controller
 * 
 * Handles the user admin pages
 * 
 * @package    User
 */
class AdminController extends AbstractActionController
{
    /**
     * @var UserServiceInterface
     */
    protected $userService;
    
    /**
     * set the user service
     * 
     * @param UserServiceInterface
     */
    public function setUserService(UserServiceInterface $userService)
    {
        $this->userService = $userService;

        return $this;
    }
    
    /**
     * Get the user service
     * 
     * @return UserServiceInterface
     */
    public function getUserService()
    {
        return $this->userService;
    }
    
    /**
     * Handle user page
     */
    public function indexAction()
    {
        // read page from route
        $page = (int) $this->params()->fromRoute('page');
        
        // set max user per page
        $maxPage = 10;
        
        // read data and pass to view
        return new ViewModel(array(
            'userList'  => $this->getUserService()->fetchList($page, $maxPage),
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
            return $this->redirect()->toRoute('user-admin');
        }
        
        // prepare Post/Redirect/Get Plugin
        $prg = $this->prg(
            $this->url()->fromRoute('user-admin/action', array(), array(), true), 
            true
        );
        
        // check PRG plugin for redirect to send
        if ($prg instanceof Response) {
            return $prg;
            
        // check PRG for redirect to process
        } elseif ($prg !== false) {
            // check for cancel
            if (isset($prg['cancel'])) {
                // Redirect to list of blogs
                return $this->redirect()->toRoute('user-admin');
            }
            
            // update with redirected data
            $user = $this->getUserService()->save($prg, $id);
            
            // check user
            if ($user) {
                // add messages to flash messenger
                $this->flashMessenger()->addMessage(
                    $this->getUserService()->getMessage()
                );
                
                // Redirect to update user
                return $this->redirect()->toRoute(
                    'user-admin/action', array(), array(), true
                );
            }
        }
        
        // get user
        $user = $this->getUserService()->fetchSingleById($id);
        
        // check user
        if ($user === false) {
            return $this->redirect()->toRoute('user-admin');
        }
        
        // get form and bind object
        $form = $this->getUserService()->getForm('update');
        
        //check prg
        if ($prg === false) {
            $form->bind($user);
        }
        
        // add messages to flash messenger
        if ($this->getUserService()->getMessage()) {
            $this->flashMessenger()->addMessage(
                $this->getUserService()->getMessage()
            );
        }
        
        // no post or update unsuccesful
        return new ViewModel(array(
            'form' => $form,
            'user' => $user,
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
            return $this->redirect()->toRoute('user-admin');
        }
        
        // prepare Post/Redirect/Get Plugin
        $prg = $this->prg(
            $this->url()->fromRoute('user-admin/action', array(), array(), true), 
            true
        );
        
        // check PRG plugin for redirect to send
        if ($prg instanceof Response) {
            return $prg;
            
        // check PRG for redirect to process
        } elseif ($prg !== false) {
            // check for cancel
            if (isset($prg['cancel'])) {
                // Redirect to list of blogs
                return $this->redirect()->toRoute('user-admin');
            }
            
            // update with redirected data
            $user = $this->getUserService()->delete($id);
            
            // check user
            if ($user) {
                // add messages to flash messenger
                $this->flashMessenger()->addMessage(
                    $this->getUserService()->getMessage()
                );
                
                // Redirect to list of user
                return $this->redirect()->toRoute('user-admin');
            }
        }
        
        // get user
        $user = $this->getUserService()->fetchSingleById($id);
        
        // check user
        if ($user === false) {
            return $this->redirect()->toRoute('user-admin');
        }
        
        // get form and bind object
        $form = $this->getUserService()->getForm('delete');
        
        //check prg
        if ($prg === false) {
            $form->bind($user);
        }
        
        // add messages to flash messenger
        if ($this->getUserService()->getMessage()) {
            $this->flashMessenger()->addMessage(
                $this->getUserService()->getMessage()
            );
        }
        
        // no post or update unsuccesful
        return new ViewModel(array(
            'form' => $form,
            'user' => $user,
        ));
    }
}
