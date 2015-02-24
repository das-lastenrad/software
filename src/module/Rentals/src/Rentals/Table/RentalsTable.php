<?php

/**
 * namespace definition and usage
 */
namespace Rentals\Table;

use Rentals\Entity\RentalsEntityInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

/**
 * Rentals table
 * 
 * Handles the rentalss table for the Rentals module 
 * 
 * @package    Rentals
 */
class RentalsTable extends TableGateway implements RentalsTableInterface
{
    /**
     * Constructor
     * 
     * @param Adapter $adapter database adapter
     */
    public function __construct(Adapter $adapter, RentalsEntityInterface $entity)
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype($entity);
        
        parent::__construct('rentals', $adapter, null, $resultSet);
    }
    
    /**
     * Fetch single rentals by url
     * 
     * @param varchar $url url address of rentals
     * @return RentalsEntityInterface
     */
    public function fetchSingleByUrl($url)
    {
        $select = $this->getSql()->select();
        $select->where->equalTo('url', $url);
        
        return $this->selectWith($select)->current();
    }
    
    /**
     * Fetch single rentals by id
     * 
     * @param integer $id id of rentals
     * @return RentalsEntityInterface
     */
    public function fetchSingleById($id)
    {
        $select = $this->getSql()->select();
        $select->where->equalTo('id', $id);
        
        return $this->selectWith($select)->current();
    }
}
