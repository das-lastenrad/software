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
class RentalsEntity implements RentalsEntityInterface
{
    protected $statusNames = array(
        'blocked'  => 'gesperrt',
        'approved' => 'genehmigt',
    );
    
    protected $id;
    protected $status;
    protected $name;
    protected $description = '';
    protected $from;
    protected $to;
    protected $user;

    
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
    
    public function setUser($user)
    {
    	$this->user = $user;
    }
    
    public function getUser()
    {
    	return $this->user;
    }
    
    /**
     * Set description
     * 
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * Get description
     * 
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
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
            'id'          => $this->getId(),
            'description' => $this->getDescription(),
            'from'        => $this->getFrom(),
            'to'          => $this->getTo(),
        	'user'        => $this->getUser(),
        );
    }
}
