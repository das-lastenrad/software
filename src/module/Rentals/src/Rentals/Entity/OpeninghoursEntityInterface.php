<?php

/**
 * namespace definition and usage
 */
namespace Rentals\Entity;

use Zend\Filter\StaticFilter;
use Zend\Stdlib\ArraySerializableInterface;

/**
 * Rentals entity interface
 * 
 * @package    Rentals
 */
interface OpeninghoursEntityInterface extends ArraySerializableInterface
{
    /**
     * Set id
     * 
     * @param integer $id
     */
    public function setId($id);
    
    /**
     * Get id
     * 
     * @return integer $id
     */
    public function getId();
    
    
    public function setFrom($from);
    public function getFrom();

    public function setTo($to);
    public function getTo();
    
    
    /**
     * Set description
     * 
     * @param string $description
     */
    public function setStation($station);
    
    /**
     * Get description
     * 
     * @return string $description
     */
    public function getStation();
    

}
