<?php

/**
 * namespace definition and usage
 */
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;
use User\Service\UserServiceInterface;
use Zend\View\Model\ViewModel;

/**
 * User is allowed view helper
 * 
 * Checks if a user is allowed to access
 * 
 * @package    User
 */
class UserShowCounter extends AbstractHelper
{

	/**
	 * @var UserServiceInterface
	 */
	protected $userService;
	
	
    /**
     * Constructor
     *
     * @param  
     */
    public function __construct(UserServiceInterface $userService)
    {
    	$this->setUserService($userService);
    }
    
    
    /**
     * Sets user service
     *
     * @param  UserServiceInterface $userService
     * @return AbstractHelper
     */
    public function setUserService(UserServiceInterface $userService = null)
    {
    	$this->userService = $userService;
    	return $this;
    }
    
    /**
     * Returns UserService
     *
     * @return UserServiceInterface
     */
    public function getUserService()
    {
    	return $this->userService;
    }

    
    /**
     * Checks if current role is allowed to access resource with privilege
     *
     * @return boolean
     */
    public function __invoke()
    {
        $vm = new ViewModel(array(
            'numberOfUsers' => $this->getUserService()->getNumberOfUsers(),
        ));
        
        $vm->setTemplate('widget/counter');
        
        return $this->getView()->render($vm);
    }
}