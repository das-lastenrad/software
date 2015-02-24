<?php


/**
 * namespace definition and usage
 */
namespace User\Service;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Session\Container;
use User\Entity\UserEntity;
use User\Entity\UserEntityInterface;
use User\Form\UserFormInterface;
use User\Table\UserTableInterface;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;


/**
 * User Service
 * 
 * @package    User
 */
class UserService implements 
    EventManagerAwareInterface, 
    UserServiceInterface
{
    /**
     * @var EventManagerInterface
     */
    protected $eventManager = null;
    
    /**
     * @var UserTableInterface
     */
    protected $table = null;
    
    /**
     * @var AuthenticationService
     */
    protected $authentication = null;
    
    /**
     * @var UserFormInterface[]
     */
    protected $forms = array();

    /**
     * @var string
     */
    protected $message = null;
    
    /**
     * Constructor
     * 
     * @param UserTableInterface $table
     * @param AuthenticationService $authentication
     */
    public function __construct(UserTableInterface $table, AuthenticationService $authentication)
    {
        $this->setTable($table);
        $this->setAuthentication($authentication);
    }
    
    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(array(__CLASS__));
        $this->eventManager = $eventManager;
    }
    
    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }
    
    /**
     * Get user table
     * 
     * @return UserTableInterface
     */
    public function getTable()
    {
        return $this->table;
    }
    
    /**
     * Set user table
     * 
     * @param UserTableInterface $table
     * @return UserServiceInterface;
     */
    public function setTable(UserTableInterface $table)
    {
        $this->table = $table;
        return $this;
    }
    
    /**
     * Get user authentication
     * 
     * @return AuthenticationService
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }
    
    /**
     * Set user authentication
     * 
     * @param AuthenticationService $authentication
     * @return UserServiceInterface;
     */
    public function setAuthentication(AuthenticationService $authentication)
    {
        $this->authentication = $authentication;
        return $this;
    }

    /**
     * Get service message
     * 
     * @return array
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Clear service message
     */
    public function clearMessage()
    {
        $this->message = null;
    }
    
    /**
     * Add service message
     * 
     * @param string new message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
    
    /**
     * Get form with triggering the Event-Manager
     * 
     * @param  string $type form type
     * @return UserFormInterface
     */
    public function getForm($type = 'login')
    {
        if (!isset($this->forms[$type])) {
            $this->getEventManager()->trigger(
                'set-user-form', __CLASS__, array('type' => $type)
            );
        }
        
        return $this->forms[$type];
    }

    /**
     * Set register form
     * 
     * @param UserFormInterface $form
     * @param string $type form type
     */
    public function setForm(UserFormInterface $form, $type = 'login')
    {
        $this->forms[$type] = $form;
    }

    /**
     * Fetch single by email
     *
     * @param varchar $email
     * @return UserEntityInterface
     */
    public function fetchSingleByEmail($email)
    {
        return $this->getTable()->fetchSingleByEmail($email);
    }
    
    /**
     * Fetch single by id
     *
     * @param varchar $id
     * @return UserEntityInterface
     */
    public function fetchSingleById($id)
    {
        return $this->getTable()->fetchSingleById($id);
    }
    
    /**
     * Fetch list of users
     *
     * @param integer $page number of page
     * @return Paginator
     */
    public function fetchList($page = 1, $perPage = 15)
    {
        // Initialize select
        $select = $this->getTable()->getSql()->select();
        $select->order('lastname');
        $select->order('firstname');
        
        // Initialize paginator
        $adapter = new DbSelect(
            $select, 
            $this->getTable()->getAdapter(), 
            $this->getTable()->getResultSetPrototype()
        );
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($perPage);
        $paginator->setPageRange(9);
        
        // return paginator
        return $paginator;
    }
    
    /**
     * Save a user
     *
     * @param array $data input data
     * @param integer $id id of user entry
     * @return UserEntityInterface
     */
    public function save(array $data, $id = null)
    {
        // check mode
        $mode = is_null($id) ? 'register' : 'update';
        
        // get user entity
        if ($mode == 'register') {
            $user = new UserEntity();            
        } else {
            $user = $this->fetchSingleById($id);
        }
        
        // get form and set data
        $form = $this->getForm($mode);
        $form->setData($data);
        
        // check for invalid data
        if (!$form->isValid()) {
            $this->setMessage('Bitte Eingaben überprüfen!');
            return false;
        }
        
                
        // check if email already registered
        if ($mode == 'register') {
          //$existing_users = $this->fetchSingleByEmail($data['email']);
          //if ( isset($existing_users) & ($existing_users->getId() > 0) )
          if ($this->fetchSingleByEmail($data['email']))
          {
          	$this->setMessage('Deine E-Mail-Adresse ist schon im System eingetragen. Schreib uns bitte ein Mail wenn du dein Passwort vergessen hast.');
          	return false;          	
          }
          //file_put_contents('c:/projekte/lastenrad/data/log/test.log', "email: {$data['email']}\n", FILE_APPEND);
          //file_put_contents('c:/projekte/lastenrad/data/log/test.log', "existing_users: {$existing_users->getId()}\n", FILE_APPEND);
        } 
        
        // get valid user entity object
        $user->exchangeArray($form->getData());
        
        // check for password
        if ($data['pass'] != '') {
            // encrypt password
            $bcrypt = $this->getAuthentication()->getAdapter()->getBcrypt();
            $hash = $bcrypt->create($user->getPass());
            
            // set values
            $user->setPass($hash);
        }
        
        // set values
        if ($mode == 'register') {
            $user->setRole('customer');
        }
        
        // get insert data
        $saveData = $user->getArrayCopy();
        
        // insert new user
        try {
            if ($mode == 'register') {
                $this->getTable()->insert($saveData);
                
                // get last insert value
                $id = $this->getTable()->getLastInsertValue();
            } else {
                $this->getTable()->update($saveData, array('id' => $id));
            }
        } catch (InvalidQueryException $e) {
            $this->setMessage('Benutzer konnte nicht gespeichert werden!');
            return false;
        }

        // reload user
        $user = $this->fetchSingleById($id);
        
        // set success message
        $this->setMessage('Die Daten wurden gespeichert!');
        
        // return user
        return $user;
    }
    
    /**
     * Reset a users password
     *
     * @param array $data input data
     * @param integer $id id of user entry
     * @return UserEntityInterface
     */
    public function resetPassword(array $data)
    {
    
        // check if email already registered
        if (!$user = $this->fetchSingleByEmail($data['email']))
        {
        	$this->setMessage('Deine E-Mail-Adresse ist uns nicht bekannt.');
        	return false;
        }
        
    	// get form and set data
    	$form = $this->getForm('resetPassword');
    	$form->setData($data);
    
    	// check for invalid data
    	if (!$form->isValid()) {
    		$this->setMessage('Bitte Eingaben überprüfen!');
    		return false;
    	}    	
    
    	// get valid user entity object
    	$user->setEmail($data['email']);
    
    	// generate new password
    	$new_password = rand(10000, 99999);
    	// encrypt password
    	$bcrypt = $this->getAuthentication()->getAdapter()->getBcrypt();
    	$hash = $bcrypt->create($new_password);
    	$user->setPass($hash);
    	
    	// sendmail
    	$this->sendNewPasswordMail($user->getEmail(), $new_password, $id = $user->getId());
    
    	// get insert data
    	$saveData = $user->getArrayCopy();
    
    	// insert new user
    	try {
    		  $this->getTable()->update($saveData, array('id' => $user->getId()));
    	} catch (InvalidQueryException $e) {
    		$this->setMessage('Benutzer konnte nicht gespeichert werden!');
    		return false;
    	}
    
    	// reload user
    	$user = $this->fetchSingleById($id);
    
    	// set success message
    	$this->setMessage('Es wurde ein neues Passwort vergeben. Dieses sollte bald in deinem E-Mail Posteingang landen!');
    
    	// return user
    	return $user;
    }
    
    /**
     * Delete existing user
     *
     * @param integer $id user id
     * @param array $data input data
     * @return UserEntityInterface
     */
    public function delete($id)
    {
        // fetch user entity
        $user = $this->fetchSingleById($id);
        
        // delete existing user
        try {
            $result = $this->getTable()->delete(array('id' => $id));
        } catch (InvalidQueryException $e) {
            return;
        }

        // set success message
        $this->setMessage('Der Benutzer wurde gelöscht!');
        
        // return result
        return true;
    }
    
    /**
     * Login user
     *
     * @param array $data input data
     * @return UserEntityInterface|false
     */
    public function login(array $data)
    {
        // get form and set data
        $form = $this->getForm('login');
        $form->setData($data);
        
        // check for invalid data
        if (!$form->isValid()) {
            $this->setMessage('Bitte Eingaben überprüfen!');
            return false;
        }
        
        // get valid user entity object
        $user = $form->getData();
        
        // get authentication
        $authentication = $this->getAuthentication();
        $authentication->getAdapter()->setIdentity($user['email']);
        $authentication->getAdapter()->setCredential($user['pass']);
        
        // authenticate
        $result = $authentication->authenticate();
        
        // get messages
        $messages = $result->getMessages();
        
        // set first message
        $this->setMessage($messages[0]);
        
        // check result
        if (!$result->isValid()) {
            return false;
        }
        
        return $result->getIdentity();
    }
    
    /**
     * Logout user
     *
     * @return boolean
     */
    public function logout()
    {
        // get authentication
        $authentication = $this->getAuthentication();
        
        // clear identity
        $authentication->clearIdentity();
        
        // get session namespace
        $authNamespace = new Container(Session::NAMESPACE_DEFAULT);
        
        // clear session
        $authNamespace->getManager()->destroy();
        
        // set message
        $this->setMessage('Sie wurden abgemeldet!');
        
        return true;
    }
    
    /**
     * Logout user
     *
     * @return boolean
     */
    public function getNumberOfUsers()
    {
    	return '1000';
    }
    
    /**
     * Send Mail to User
     *
     * @param integer $id rentals id
     * @param array $data input data
     * @return RentalsEntityInterface
     */
    public function sendNewPasswordMail($address, $password, $id)
    {
    
    	$message = new Message();
    	$message->addTo($address)
    	->addFrom('mail@das-lastenrad.at')
    	->setSubject('das-lastenrad.at - Dein neues Passwort');
    	// Setup SMTP transport using LOGIN authentication
    	$transport = new SmtpTransport();
    	$options = new SmtpOptions(array(
    			'host' => 'smtp.mailserver.com',
    			'connection_class' => 'login',
    			'connection_config' => array(
    					'ssl' => 'tls',
    					'username' => 'outgoing@rentalplatform.com',
    					'password' => 'PasSw0rD'
    			),
    			'port' => 587,
    	));
    	 
    	$html = new MimePart("<p>Hallo,<br>Dein neues Passwort lautet: {$password}<br>ID: {$id}</p>");
    	$html->type = "text/html";
    	 
    	$body = new MimeMessage();
    	$body->addPart($html);
    	 
    	$message->setBody($body);
    	 
    	$transport->setOptions($options);
    	$transport->send($message);
    	return true;
    }
    
    
}
