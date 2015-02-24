<?php

/**
 * namespace definition and usage
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * About controller
 * 
 * Handles the homepage and other pages
 * 
 * @package    Application
 */
class AboutController extends AbstractActionController
{
    /**
     * Handle about page
     */
    public function indexAction()
    {
        return new ViewModel();
    }
    
    /**
     * Handle imprint page
     */
    public function imprintAction()
    {
        return new ViewModel();
    }
    
    /**
     * Handle stationen page
     */
    public function stationenAction()
    {
        return new ViewModel();
    }

    /**
     * Handle stationen page
     */
    public function infoAction()
    {
    	return new ViewModel();
    }
    
    /**
     * Handle contact page
     */
    public function contactAction()
    {
        return new ViewModel();
    }
}
