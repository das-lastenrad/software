<?php

namespace Lastenrad\Table;

use Lastenrad\Entity\RentalsEntityInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGatewayInterface;

interface RentalsTableInterface extends TableGatewayInterface
{
	public function __construct(
			Adapter $adapter, RentalsEntityInterface $entity
			);
	//public function fetchSingleByUrl($url);
	public function fetchSingleById($id);
	
}


