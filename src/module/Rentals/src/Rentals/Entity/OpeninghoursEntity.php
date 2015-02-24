<?php


/**
 * namespace definition and usage
 */
namespace Rentals\Entity;

use Zend\Filter\StaticFilter;
use Application\View\Helper;

/**
 * Rentals entity
 * 
 * @package    Rentals
 */
class OpeninghoursEntity implements OpeninghoursEntityInterface
{
    
    protected $id;
    protected $from;
    protected $to;
    protected $station;
    
    /**
     * Set id
     * 
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * Get id
     * 
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function setFrom($from)
    {
    	$this->from = $from;
    }
    
    public function getFrom()
    {
    	return $this->from;
    }

    public function setTo($to)
    {
    	$this->to = $to;
    }
    
    public function getTo()
    {
    	return $this->to;
    }
    
    public function setStation($station)
    {
    	$this->station = $station;
    }
    
    public function getStation()
    {
    	return $this->station;
    }
    
    
    /**
     * Exchange internal values from provided array
     *
     * @param  array $array
     * @return void
     */
    public function exchangeArray(array $array)
    {
        foreach ($array as $key => $value) {
            if (empty($value)) {
                continue;
            }
            $method = 'set' . StaticFilter::execute(
                $key, 'wordunderscoretocamelcase'
            );
            if (!method_exists($this, $method)) {
                continue;
            }
            $this->$method($value);
        }
    }

    /**
     * Return an array representation of the object
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return array(
            'id'        => $this->getId(),
            'station'   => $this->getDescription(),
            'from'      => $this->getFrom(),
            'to'        => $this->getTo(),
        );
    }
}
