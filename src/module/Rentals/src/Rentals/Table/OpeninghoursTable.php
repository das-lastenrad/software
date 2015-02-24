<?php

/**
 * namespace definition and usage
 */
namespace Rentals\Table;

use Rentals\Entity\OpeninghoursEntityInterface;
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
class OpeninghoursTable extends TableGateway implements OpeninghoursTableInterface
{
    /**
     * Constructor
     * 
     * @param Adapter $adapter database adapter
     */
    public function __construct(Adapter $adapter, OpeninghoursEntityInterface $entity)
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype($entity);
        
        parent::__construct('openinghours', $adapter, null, $resultSet);
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
     * Check if station is open at the lending and returning
     *
     * @param varchar $url url address of rentals
     * @return RentalsEntityInterface
     */
    public function isStationOpen($lending, $station)
    {
    	$select = $this->getSql()->select();
    	// check if $lending between from-to
    	$select->where->lessThanOrEqualTo('from', $lending);
    	$select->where->greaterThanOrEqualTo('to', $lending);
    	
    	$select->where->in('station', $station);
    	
    	//echo $select->getSqlString();
    	
    	return $this->selectWith($select);
    	
    }
    
    /**
     * Fetch all opening hours
     *
     * @param integer $id id of rentals
     * @return RentalsEntityInterface
     */
    public function fetchAll()
    {
    	$select = $this->getSql()->select();
    
    	return $this->selectWith($select);
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
