<?php

/**
 * namespace definition and usage
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Console\Request as ConsoleRequest;

/**
 * About controller
 * 
 * Handles the homepage and other pages
 * 
 * @package    Application
 */
class ConsoleController extends AbstractActionController
{
    /**
     * Handle about page
     */
    public function indexAction()
    {
    	echo "test";
        return; //new ViewModel();
    }
        
    /**
     * Handle stationen page
     */
    public function updateAction()
    {
    	echo "test";
        return;
    }


}
