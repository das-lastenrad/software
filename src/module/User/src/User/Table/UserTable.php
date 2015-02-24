<?php

/**
 * namespace definition and usage
 */
namespace User\Table;

use User\Entity\UserEntityInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

/**
 * User table
 * 
 * Handles the users table for the User module 
 * 
 * @package    User
 */
class UserTable extends TableGateway implements UserTableInterface
{
    /**
     * Constructor
     * 
     * @param Adapter $adapter database adapter
     */
    public function __construct(Adapter $adapter, UserEntityInterface $entity)
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype($entity);
        
        parent::__construct('users', $adapter, null, $resultSet);
    }
    
    /**
     * Fetch single user by email
     * 
     * @param varchar $email email address of user
     * @return UserEntityInterface
     */
    public function fetchSingleByEmail($email)
    {
        $select = $this->getSql()->select();
        $select->where->equalTo('email', $email);
        
        return $this->selectWith($select)->current();
    }
    
    /**
     * Fetch single user by id
     * 
     * @param integer $id id ofuser
     * @return UserEntityInterface
     */
    public function fetchSingleById($id)
    {
        $select = $this->getSql()->select();
        $select->where->equalTo('id', $id);
        
        return $this->selectWith($select)->current();
    }
}
