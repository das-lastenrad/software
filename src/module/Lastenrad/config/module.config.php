<?php

return array(
		'router' => array(
				'routes' => array(
						'lastenrad' => array(
								'type' => 'Literal',
								'options' => array(
										'route' => '/lastenrad',
										'defaults' => array(
												'controller' => 'lastenrad',
												'action' => 'index',
										),
								),
						),
						'kalender' => array(
								'type' => 'Literal',
								'options' => array(
										'route' => '/kalender',
										'defaults' => array(
												'controller' => 'kalender',
												'action' => 'index',
										),
								),
						),
						'kalenderjson' => array(
								'type' => 'Literal',
								'options' => array(
										'route' => '/kalenderjson',
										'defaults' => array(
												'controller' => 'kalenderjson',
												'action' => 'index',
										),
								),
						),
				),
		),
		'controllers' => array(
				'invokables' => array(
						'lastenrad' => 'Lastenrad\Controller\IndexController',						
						
				),
				'factories' => array(
						'kalender' => 'Lastenrad\Controller\KalenderControllerFactory',
						'kalenderjson' => 'Lastenrad\Controller\KalenderJsonControllerFactory',
				),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						__DIR__ . '/../view',
				),
				'strategies' => array(
						'ViewJsonStrategy',
				),
		),
		'service_manager' => array(
				'invokables' => array(
						'Lastenrad\Entity\Rentals' => 'Lastenrad\Entity\RentalsEntity',
						
				),
				'factories' => array(
						'Lastenrad\Table\Rentals' => 'Lastenrad\Table\RentalsTableFactory',
						'Lastenrad\Service\Lastenrad'  => 'Lastenrad\Service\LastenradServiceFactory',
				),
		),
);
