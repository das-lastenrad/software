<?php

namespace Lastenrad\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Create blog service factory
 *
 * @package Blog
 */
class LastenradServiceFactory implements FactoryInterface
{
	/**
	 * Create Service Factory
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 */
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$table = $serviceLocator->get('Lastenrad\Table\Rentals');
		$service = new LastenradService($table);
		return $service;
	}
}

