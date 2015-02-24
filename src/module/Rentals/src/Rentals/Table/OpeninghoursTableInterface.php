<?php

/**
 * namespace definition and usage
 */
namespace Rentals\Table;

use Rentals\Entity\OpeninghoursEntityInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGatewayInterface;

/**
 * Rentals table interface
 * 
 * Handles the rentalss table for the Rentals module 
 * 
 * @package    Rentals
 */
interface OpeninghoursTableInterface extends TableGatewayInterface
{
    /**
     * Constructor
     * 
     * @param Adapter $adapter database adapter
     */
    public function __construct(Adapter $adapter, OpeninghoursEntityInterface $entity);
    
    /**
     * Fetch single rentals by url
     * 
     * @param varchar $url url address of rentals
     * @return RentalsEntityInterface
     */
    public function fetchSingleByUrl($url);
    
    public function fetchAll();
    
    /**
     * Fetch single rentals by id
     * 
     * @param integer $id id of rentals
     * @return RentalsEntityInterface
     */
    public function fetchSingleById($id);
}
