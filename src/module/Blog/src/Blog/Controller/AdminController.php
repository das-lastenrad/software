<?php

/**
 * namespace definition and usage
 */
namespace Blog\Controller;

use Zend\Http\PhpEnvironment\Response;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Blog\Service\BlogServiceInterface;

/**
 * Admin controller
 * 
 * Handles the admin pages
 * 
 * @package    Blog
 */
class AdminController extends AbstractActionController
{
    /**
     * @var BlogServiceInterface
     */
    protected $blogService;
    
    /**
     * set the blog service
     * 
     * @param BlogServiceInterface
     */
    public function setBlogService(BlogServiceInterface $blogService)
    {
        $this->blogService = $blogService;

        return $this;
    }
    
    /**
     * Get the blog service
     * 
     * @return BlogServiceInterface
     */
    public function getBlogService()
    {
        return $this->blogService;
    }
    
    /**
     * Handle admin page
     */
    public function indexAction()
    {
        // read page from route
        $page = (int) $this->params()->fromRoute('page');
        
        // set max blog per page
        $maxPage = 10;
        
        // read data and pass to view
        return new ViewModel(array(
            'blogList' => $this->getBlogService()->fetchList($page, $maxPage),
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
                'blog-admin/action', array('action' => 'create')
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
                // Redirect to list of blogs
                return $this->redirect()->toRoute('blog-admin');
            }
            
            // create with redirected data
            $blog = $this->getBlogService()->save($prg);
            
            // check blog
            if ($blog) {
                // add messages to flash messenger
                $this->flashMessenger()->addMessage(
                    $this->getBlogService()->getMessage()
                );
                
                // Redirect to home page
                return $this->redirect()->toRoute(
                    'blog-admin/action', 
                    array('action' => 'update', 'id' => $blog->getId())
                );
            }
        }
        
        // get form
        $form = $this->getBlogService()->getForm('create');
        
        // add messages to flash messenger
        if ($this->getBlogService()->getMessage()) {
            $this->flashMessenger()->addMessage(
                $this->getBlogService()->getMessage()
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
            return $this->redirect()->toRoute('blog-admin');
        }
        
        // prepare Post/Redirect/Get Plugin
        $prg = $this->prg(
            $this->url()->fromRoute('blog-admin/action', array(), array(), true), 
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
                return $this->redirect()->toRoute('blog-admin');
            }
            
            // update with redirected data
            $blog = $this->getBlogService()->save($prg, $id);
            
            // check blog
            if ($blog) {
                // add messages to flash messenger
                $this->flashMessenger()->addMessage(
                    $this->getBlogService()->getMessage()
                );
                
                // Redirect to update blog
                return $this->redirect()->toRoute(
                    'blog-admin/action', array(), array(), true
                );
            }
        }
        
        // get blog
        $blog = $this->getBlogService()->fetchSingleById($id);
        
        // check blog
        if ($blog === false) {
            return $this->redirect()->toRoute('blog-admin');
        }
        
        // get form and bind object
        $form = $this->getBlogService()->getForm('update');
        
        //check prg
        if ($prg === false) {
            $form->bind($blog);
        }
        
        // add messages to flash messenger
        if ($this->getBlogService()->getMessage()) {
            $this->flashMessenger()->addMessage(
                $this->getBlogService()->getMessage()
            );
        }
        
        // no post or update unsuccesful
        return new ViewModel(array(
            'form' => $form,
            'blog' => $blog,
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
            return $this->redirect()->toRoute('blog-admin');
        }
        
        // prepare Post/Redirect/Get Plugin
        $prg = $this->prg(
            $this->url()->fromRoute('blog-admin/action', array(), array(), true), 
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
                return $this->redirect()->toRoute('blog-admin');
            }
            
            // delete with redirected data
            $blog = $this->getBlogService()->delete($id);
            
            // check blog
            if ($blog) {
                // add messages to flash messenger
                $this->flashMessenger()->addMessage(
                    $this->getBlogService()->getMessage()
                );
                
                // Redirect to list of blog
                return $this->redirect()->toRoute('blog-admin');
            }
        }
        
        // get blog
        $blog = $this->getBlogService()->fetchSingleById($id);
        
        // check blog
        if ($blog === false) {
            return $this->redirect()->toRoute('blog-admin');
        }
        
        // get form and bind object
        $form = $this->getBlogService()->getForm('delete');
        
        //check prg
        if ($prg === false) {
            $form->bind($blog);
        }
        
        // add messages to flash messenger
        if ($this->getBlogService()->getMessage()) {
            $this->flashMessenger()->addMessage(
                $this->getBlogService()->getMessage()
            );
        }
        
        // no post or update unsuccesful
        return new ViewModel(array(
            'form' => $form,
            'blog' => $blog,
        ));
    }
}
