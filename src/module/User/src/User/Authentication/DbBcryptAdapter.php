<?php

/**
 * namespace definition and usage
 */
namespace User\Authentication;

use Zend\Authentication\Result;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Crypt\Password\Bcrypt;
use User\Table\UserTableInterface;

/**
 * Database authentication with bcrypt
 * 
 * Handles the user authentication with a database and bcrypt
 * 
 * @package    User
 */
class DbBcryptAdapter implements AdapterInterface
{
    /**
     * @var UserTableInterface
     */
    protected $table = null;
    
    /**
     * @var Bcrypt
     */
    protected $bcrypt = null;
    
    /**
     * $identity - Identity value
     *
     * @var string
     */
    protected $identity = null;

    /**
     * $credential - Credential values
     *
     * @var string
     */
    protected $credential = null;

    /**
     * $authenticateResultInfo
     *
     * @var array
     */
    protected $authenticateResultInfo = null;

    /**
     * Constructor
     * 
     * @param UserTableInterface $table
     * @param Bcrypt $bcrypt
     */
    public function __construct(UserTableInterface $table, Bcrypt $bcrypt)
    {
        $this->setTable($table);
        $this->setBcrypt($bcrypt);
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
     * @return DbBcryptAdapter
     */
    public function setTable(UserTableInterface $table)
    {
        $this->table = $table;
        return $this;
    }
    
    /**
     * Get service bcrypt
     * 
     * @return Bcrypt
     */
    public function getBcrypt()
    {
        return $this->bcrypt;
    }
    
    /**
     * Set service bcrypt
     * 
     * @param Bcrypt $bcrypt
     * @return DbBcryptAdapter
     */
    public function setBcrypt(Bcrypt $bcrypt)
    {
        $this->bcrypt = $bcrypt;
        return $this;
    }
    
    /**
     * Get identity value
     * 
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }
    
    /**
     * set the value to be used as the identity
     *
     * @param  string $value
     * @return DbBcryptAdapter
     */
    public function setIdentity($value)
    {
        $this->identity = trim($value);
        return $this;
    }

    /**
     * Get credential value
     * 
     * @return string
     */
    public function getCredential()
    {
        return $this->credential;
    }
    
    /**
     * set the credential value to be used
     *
     * @param  string $credential
     * @return DbBcryptAdapter
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;
        return $this;
    }

    /**
     * Setup for authentication result
     */
    protected function setupResult()
    {
        // setup result info
        $this->authenticateResultInfo = array(
            'code'     => Result::FAILURE,
            'identity' => $this->getIdentity(),
            'messages' => array()
        );
        
        return true;
    }
    
    /**
     * Creates a Zend\Authentication\Result object 
     *
     * @return AuthenticationResult
     */
    protected function createResult()
    {
        return new Result(
            $this->authenticateResultInfo['code'],
            $this->authenticateResultInfo['identity'],
            $this->authenticateResultInfo['messages']
        );
    }
    
    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        // set authentication
        $this->setupResult();
        
        // check for identity
        if (!$this->getIdentity()) {
            $this->authenticateResultInfo['messages'][] = 
                'Sie haben keine E-Mail Adresse eingegeben!';
            return $this->createResult();
        }
        
        // check for credential
        if (!$this->getCredential()) {
            $this->authenticateResultInfo['messages'][] = 
                'Sie haben kein Passwort eingegeben!';
            return $this->createResult();
        }
        
        // fetch dataset for identity
        $user = $this->getTable()->fetchSingleByEmail($this->getIdentity());
        
        // check user
        if (!$user) {
            $this->authenticateResultInfo['code'      ] = 
                Result::FAILURE_IDENTITY_NOT_FOUND;
            $this->authenticateResultInfo['messages'][] = 
                'Es gibt keinen Benutzer für die E-Mail Adresse!';
            return $this->createResult();
        }
        
        // verify password
        $bcrypt = $this->getBcrypt();
        $verify = $bcrypt->verify($this->getCredential(), $user->getPass());
        
        // check password
        if (!$verify) {
            $this->authenticateResultInfo['code'      ] = 
                Result::FAILURE_CREDENTIAL_INVALID;
            $this->authenticateResultInfo['messages'][] = 
                'Das Passwort ist falsch!';
            return $this->createResult();
        }
        
        // clear password
        $user->setPass('');
        
        // successful
        $this->authenticateResultInfo['code'      ] = Result::SUCCESS;
        $this->authenticateResultInfo['identity'  ] = $user;
        $this->authenticateResultInfo['messages'][] = 
            'Du hast dich erfolgreich angemeldet!';
        return $this->createResult();
    }
}
