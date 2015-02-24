<?php

/**
 * namespace definition and usage
 */
namespace Application\View\Helper;

use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Helper\AbstractHelper;

/**
 * Show messages view helper
 * 
 * Outputs all messages from FlashMessenger and view
 * 
 * @package    Application
 */
class ShowMessages extends AbstractHelper
{
    /**
     * FlashMessenger
     *
     * @var FlashMessenger
     */
    protected $flashMessenger;

    /**
     * Constructor
     *
     * @param  FlashMessenger $flashMessenger
     */
    public function __construct(FlashMessenger $flashMessenger)
    {
        $this->setFlashMessenger($flashMessenger);
    }

    /**
     * Outputs message depending on flag
     *
     * @return string 
     */
    public function __invoke()
    {
        // get messages
        $messages = array_unique(array_merge(
            $this->flashMessenger->getMessages(), 
            $this->flashMessenger->getCurrentMessages()
        ));
        
        $warning_messages = array_unique(array_merge(
            $this->flashMessenger->getWarningMessages(), 
            $this->flashMessenger->getCurrentWarningMessages()
        ));
        
        $error_messages = array_unique(array_merge(
            $this->flashMessenger->getErrorMessages(), 
            $this->flashMessenger->getCurrentErrorMessages()
        ));
        
        $success_messages = array_unique(array_merge(
            $this->flashMessenger->getSuccessMessages(), 
            $this->flashMessenger->getCurrentSuccessMessages()
        )); 
        
        
        $this->flashMessenger->getSuccessMessages();
        
        // initialize output
        $output = '';
        
        // loop through messages
        foreach ($messages as $message) {
            // create output
            $output.= '<div class="alert" role="alert">';
            $output.= $message;
            $output.= '</div>';
        }
        
        foreach ($error_messages as $error_message) {
        	// create output
        	$output.= '<div class="alert alert-danger" role="alert">';
        	$output.= $error_message;
        	$output.= '</div>';
        }
        
        foreach ($warning_messages as $warning_message) {
        	// create output
        	$output.= '<div class="alert alert-warning" role="alert">';
        	$output.= $warning_message;
        	$output.= '</div>';
        }
        
        foreach ($success_messages as $success_message) {
        	// create output
        	$output.= '<div class="alert alert-success" role="alert">';
        	$output.= $success_message;
        	$output.= '</div>';
        }

        // clear messages
        $this->flashMessenger->clearMessages();
        $this->flashMessenger->clearCurrentMessages();
        
        // return output
        return $output . "\n";
    }
    
    /**
     * Sets FlashMessenger
     *
     * @param  FlashMessenger $flashMessenger
     * @return AbstractHelper
     */
    public function setFlashMessenger(FlashMessenger $flashMessenger = null)
    {
        $this->flashMessenger = $flashMessenger;
        return $this;
    }
    
    /**
     * Returns FlashMessenger
     *
     * @return FlashMessenger
     */
    public function getFlashMessenger()
    {
        return $this->flashMessenger;
    }
}
