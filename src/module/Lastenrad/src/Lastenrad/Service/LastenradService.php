<?php

namespace Lastenrad\Service;

use Zend\Paginator\Paginator;

use Zend\Paginator\Adapter\DbSelect;

use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;

use Lastenrad\Entity\RentalEntity;
use Lastenrad\Entity\RentalsEntityInterface;
use Lastenrad\Table\RentalsTableInterface;

class LastenradService implements 
	EventManagerAwareInterface,
	LastenradServiceInterface
{
	protected $eventManager = null;
	protected $table = null;
	
	
	public function __construct(RentalsTableInterface $table)
	{
		$this->setTable($table);		
	}
	
	
	public function setEventManager(
			EventManagerInterface $eventManager
	)
	{
		$eventManager->setIdentifiers(array(__CLASS__));
		$this->eventManager = $eventManager;
	}
	
	public function getEventManager()
	{
		return $this->eventManager;
	}

	public function getTable()
	{
		return $this->table;	
	}

	
	public function setTable(RentalsTableInterface $table)
	{
		$this->table = $table;
		return $this;
	}
	
	public function fetchSingleById($id)
	{
		return $this->getTable()->fetchSingelById($id);
	}
	
	public function fetchList($page = 1, $perPage = 15)
	{
		$select = $this->getTable()->getSql()->select();
		
		$adapter = new DbSelect(
				$select, 
				$this->getTable()->getAdapter(),
				$this->getTable()->getResultSetPrototype()
		);
		$paginator = new Paginator($adapter);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage($perPage);
		$paginator->setPageRange(9);
		
		return $paginator;
		
	}
	
}
