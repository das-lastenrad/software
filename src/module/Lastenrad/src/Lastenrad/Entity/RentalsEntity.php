<?php

namespace Lastenrad\Entity;

class RentalsEntity implements RentalsEntityInterface
{
	protected $id;
	protected $description;
	protected $from;
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function setFrom($from)
	{
		$this->from = $from;
	}
	
	public function setTo($to)
	{
		$this->to = $to;
	}
	
	public function getId()
	{
		return $this->id;			
	}
	
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function getFrom()
	{
		return $this->from;
	}
	
	public function getTo()
	{
		return $this->to;
	}
	
	public function exchangeArray(array $array)
	{
		foreach ($array as $key => $value)
		{
			if (empty($value))
			{
				continue;
			}
			$method = 'set' . ucfirst($key);
			if (!method_exists($this, $method))
			{
				continue;
			}
			$this->$method($value);
			
		}
	}
	
	public function getArrayCopy()
	{
		return array(
				'id' => $this->getId(),
				'description' => $this->getDescription(),
				'from' => $this->getFrom(),
				'to' => $this->getTo(),
				);

		
	}

}
