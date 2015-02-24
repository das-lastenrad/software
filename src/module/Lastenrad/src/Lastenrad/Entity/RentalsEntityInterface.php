<?php

namespace Lastenrad\Entity;

use Zend\Stdlib\ArraySerializableInterface;

interface RentalsEntityInterface extends ArraySerializableInterface
{
	public function setId($id);
	public function getId();
	
}

