<?php

namespace Lastenrad\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RentalsTableFactory implements FactoryInterface
{
	public function createService(
			ServiceLocatorInterface $serviceLocator
	)
	{
		$adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
		$entity = $serviceLocator->get('Lastenrad\Entity\Rentals');
		$table = new RentalsTable($adapter, $entity);
		return $table;
	}	
	
	
	
}

