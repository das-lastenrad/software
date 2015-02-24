<?php

namespace Lastenrad\Table;

use Lastenrad\Entity\RentalsEntityInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;


class RentalsTable extends TableGateway implements RentalsTableInterface
{
	public function __construct(
			Adapter $adapter, RentalsEntityInterface $entity
			)
	{
		$resultSet = new ResultSet();
		$resultSet->setArrayObjectPrototype($entity);
		
		parent::__construct('rentals', $adapter, null, $resultSet);			
	}
	
	/*
	public function fetchSingleByUrl($url)
	{
		$select = $this->getSql()->select();
		$select->where->equalTo('url', $url);
		
		return $this->selectWith($select)->current();
	}
	*/
	
	public function fetchSingleById($id)
	{
		$select = $this->getSql()->select();
		$select->where->equalTo('id', $id);
	
		return $this->selectWith($select)->current();
	}

	public function fetchAll()
	{
		$select = $this->getSql()->select();
		$select->where->equalTo('id', 1);
	
		return $this->selectWith($select)->current();
	}
}


