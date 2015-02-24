<?php


namespace Rentals\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Rentals\Service\RentalsServiceInterface;
use Zend\Http\Response;
use Application\View\Helper;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;

/**
 * Rentals controller
 * 
 * Handles the Rentals pages
 * 
 * @package    Rentals
 */
class RentalsController extends AbstractActionController
{
    /**
     * @var RentalsServiceInterface
     */
    protected $rentalsService;
    
    /**
     * set the Rentals service
     * 
     * @param RentalsServiceInterface
     */
    public function setRentalsService(RentalsServiceInterface $rentalsService)
    {
        $this->rentalsService = $rentalsService;

        return $this;
    }
    
    /**
     * Get the Rentals service
     * 
     * @return RentalsServiceInterface
     */
    public function getRentalsService()
    {
        return $this->rentalsService;
    }
    
    /**
     * Handle Rentals page
     */
    public function indexAction()
    {    	
        // read page from route
        $page = (int) $this->params()->fromRoute('page');
        
        // set max Rentals per page
        $maxPage = 10;                
        
        // read data and pass to view
        return new ViewModel(array(
            'rentalsList' => $this->getRentalsService()->fetchList( $page, $maxPage, array() ),
        	//'openinghoursList' => $this->getRentalsService()->checkIfStationIsOpen(),
        ));
    }
    
    /**
     * Handle show page
     */
    public function showAction()
    {
        // read url from route
        $id = $this->params()->fromRoute('id');
        
        // fetch data
        $rentalsData = $this->getRentalsService()->fetchSingleById($id);
        
        // check data
        if (!$rentalsData) {
            // Redirect to Rentals page
            return $this->redirect()->toRoute('rentals');
        }
        
        // read data and pass to view
        return new ViewModel(array(
            'rentalsData' => $rentalsData,
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
    					'rentals/action', array('action' => 'create')
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
    			// Redirect to list of rentalss
    			return $this->redirect()->toRoute('rentals');
    		}
    
    		// create with redirected data
    		$rentals = $this->getRentalsService()->save($prg);
    
    		// check rentals
    		if ($rentals) {
    			// add messages to flash messenger
    			$message = $this->getRentalsService()->getMessage();
    			$this->addToFlashMessenger($message);    			    			
    
    			// Redirect to home page
    			return $this->redirect()->toRoute(
    					'rentals/action',
    					array('action' => 'index', 'id' => $rentals->getId())
    			);
    		}
    	}
    
    	// get form
    	$form = $this->getRentalsService()->getForm('create');
    	if (!$form)
    	{
    		echo "Formproblem";
    	}
    
    	// add messages to flash messenger
    	if ($this->getRentalsService()->getMessage()) {
    		$message = $this->getRentalsService()->getMessage();
    		$this->addToFlashMessenger($message);
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
    		return $this->redirect()->toRoute('rentals');
    	}
    
    	// prepare Post/Redirect/Get Plugin
    	$prg = $this->prg(
    			$this->url()->fromRoute('rentals/action', array(), array(), true),
    			true
    	);
    
    	// check PRG plugin for redirect to send
    	if ($prg instanceof Response) {
    		return $prg;
    
    		// check PRG for redirect to process
    	} elseif ($prg !== false) {
    		// check for cancel
    		if (isset($prg['cancel'])) {
    			// Redirect to list of rentalss
    			return $this->redirect()->toRoute('rentals');
    		}
    
    		// update with redirected data
    		$rentals = $this->getRentalsService()->save($prg, $id);
    
    		// check rentals
    		if ($rentals) {
    			// add messages to flash messenger
    			$message = $this->getRentalsService()->getMessage();
    		    $this->addToFlashMessenger($message);
    
    			// Redirect to update rentals
    			return $this->redirect()->toRoute(
    					'rentals/action', array(), array(), true
    			);
    		}
    	}
    
    	// get rentals
    	$rentals = $this->getRentalsService()->fetchSingleById($id);
    	
    	// FIXX me - geht sicher schoener:
    	$date_from = date('d.m.Y H:i', strtotime($rentals->getFrom()));
    	$rentals->setFrom($date_from);
    	$date_to = date('d.m.Y H:i', strtotime($rentals->getTo()));
    	$rentals->setTo($date_to);
    
    	// check rentals
    	if ($rentals === false) {
    		return $this->redirect()->toRoute('rentals');
    	}
    
    	// get form and bind object
    	$form = $this->getRentalsService()->getForm('update');
    
    	//check prg
    	if ($prg === false) {
    		$form->bind($rentals);
    	}
    
    	// add messages to flash messenger
    	if ($this->getRentalsService()->getMessage()) {
    		$message = $this->getRentalsService()->getMessage();
    		$this->addToFlashMessenger($message);
    	}
    
    	// no post or update unsuccesful
    	return new ViewModel(array(
    			'form' => $form,
    			'rentals' => $rentals,
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
    		return $this->redirect()->toRoute('rentals');
    	}
    
    	// prepare Post/Redirect/Get Plugin
    	$prg = $this->prg(
    			$this->url()->fromRoute('rentals/action', array(), array(), true),
    			true
    	);
    
    	// check PRG plugin for redirect to send
    	if ($prg instanceof Response) {
    		return $prg;
    
    		// check PRG for redirect to process
    	} elseif ($prg !== false) {
    		// check for cancel
    		if (isset($prg['cancel'])) {
    			// Redirect to list of rentalss
    			return $this->redirect()->toRoute('rentals');
    		}
    
    		// delete with redirected data
    		$rentals = $this->getRentalsService()->delete($id);
    
    		// check rentals
    		if ($rentals) {
    			// add messages to flash messenger
    			$message = $this->getRentalsService()->getMessage();
    		$this->addToFlashMessenger($message);
    
    			// Redirect to list of rentals
    			return $this->redirect()->toRoute('rentals');
    		}
    	}
    
    	// get rentals
    	$rentals = $this->getRentalsService()->fetchSingleById($id);
    
    	// check rentals
    	if ($rentals === false) {
    		return $this->redirect()->toRoute('rentals');
    	}
    
    	// get form and bind object
    	$form = $this->getRentalsService()->getForm('delete');
    
    	//check prg
    	if ($prg === false) {
    		$form->get('id')->setValue($rentals->getId());
    	}
    
    	// add messages to flash messenger
    	if ($this->getRentalsService()->getMessage()) {
    		$message = $this->getRentalsService()->getMessage();
    		$this->addToFlashMessenger($message);
    	}
    
    	// no post or update unsuccesful
    	return new ViewModel(array(
    			'form' => $form,
    			'rentals' => $rentals,
    	));
    }
    
    function addToFlashMessenger($message) {

    	switch ($message['type'])
    	{
    		case 'info':
    			$this->flashMessenger()->addMessage($message['message']);
    			break;
    		case 'error':
    			$this->flashMessenger()->addErrorMessage($message['message']);
    			break;
    		case 'warning':
    			$this->flashMessenger()->addWarningMessage($message['message']);
    			break;
    		case 'success':
    			$this->flashMessenger()->addSuccessMessage($message['message']);
    			break;
    		default:
    			$this->flashMessenger()->addMessage($message['message']);
    	}
    	return;
    }
}
