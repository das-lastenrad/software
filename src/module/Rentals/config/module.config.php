<?php

return array(
    'router' => array(
        'routes' => array(
            'rentals' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/rentals',
                    'constraints' => array(
                    ),
                    'defaults' => array(
                        'controller' => 'rentals',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'url' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/:url',
                            'constraints' => array(
                                'url' => '[a-zA-Z][a-zA-Z0-9-]*',
                            ),
                            'defaults' => array(
                                'action'     => 'show',
                            ),
                        ),
                    ),
                		
                	'action' => array(
                			'type'    => 'segment',
                			'options' => array(
                					'route'    => '/:action[/:id]',
                					'constraints' => array(
                							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                							'id'     => '[0-9]+',
                					),
                			),
                	),

                    'page' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/:page',
                            'constraints' => array(
                                'page'   => '[0-9]+',
                            ),
                        ),
                    ),
                ),
            ),
            'rentals-admin' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/rentals-admin',
                    'defaults' => array(
                        'controller' => 'rentals-admin',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'action' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/:action[/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ),
                        ),
                    ),
                    'page' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/:page[/:sort]',
                            'constraints' => array(
                                'page'   => '[0-9]+',
                                'sort'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    
    'controllers' => array(
        'factories' => array(
            'rentals'       => 'Rentals\Controller\RentalsControllerFactory',
            'rentals-admin' => 'Rentals\Controller\AdminControllerFactory',
        ),
    ),
    
    'service_manager' => array(
        'invokables' => array(
            'Rentals\Entity\Rentals'       => 'Rentals\Entity\RentalsEntity',
        	'Rentals\Entity\Openinghours'       => 'Rentals\Entity\OpeninghoursEntity',
        ),
        'factories' => array(
            'Rentals\Table\Rentals'        => 'Rentals\Table\RentalsTableFactory',
            'Rentals\Form\Create'        => 'Rentals\Form\CreateFormFactory',
            'Rentals\Form\Update'        => 'Rentals\Form\UpdateFormFactory',
            'Rentals\Form\Delete'        => 'Rentals\Form\DeleteFormFactory',
            'Rentals\Service\Rentals'      => 'Rentals\Service\RentalsServiceFactory',
        	'Rentals\Table\Openinghours'        => 'Rentals\Table\OpeninghoursTableFactory',
        ),
    ),
    
    'input_filters' => array(
        'invokables' => array(
            'Rentals\Filter\Rentals'   => 'Rentals\Filter\RentalsFilter',
        ),
    ),
    
    'view_helpers' => array(
        'invokables'=> array(
            'RentalsShowPicture'  => 'Rentals\View\Helper\RentalsShowPicture',
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    
    'navigation' => array(
        'default' => array(
            'rentals' => array(
                'type'       => 'mvc',
                'order'      => '100',
                'label'      => 'Rentals',
                'route'      => 'rentals',
                'controller' => 'rentals',
                'action'     => 'index',
                'pages'      => array(
                    'show' => array(
                        'type'       => 'mvc',
                        'label'      => 'Anzeigen',
                        'route'      => 'rentals',
                        'controller' => 'rentals',
                        'action'     => 'show',
                    ),
                    'rentals-admin' => array(
                        'type'       => 'mvc',
                        'label'      => 'Rentalsverwaltung',
                        'route'      => 'rentals-admin',
                        'controller' => 'rentals-admin',
                        'action'     => 'index',
                    ),
                    'create' => array(
                        'type'       => 'mvc',
                        'label'      => 'Anlegen',
                        'route'      => 'rentals-admin',
                        'controller' => 'rentals-admin',
                        'action'     => 'create',
                    ),
                    'update' => array(
                        'type'       => 'mvc',
                        'label'      => 'Bearbeiten',
                        'route'      => 'rentals-admin',
                        'controller' => 'rentals-admin',
                        'action'     => 'update',
                    ),
                    'delete' => array(
                        'type'       => 'mvc',
                        'label'      => 'Löschen',
                        'route'      => 'rentals-admin',
                        'controller' => 'rentals-admin',
                        'action'     => 'delete',
                    ),
                ),
            ),
        ),
    ),
    
    'acl' => array(
        'guest'   => array(
            'rentals' => array(
            		'allow' => null,
            		'deny' => array('create', 'update', 'delete')
            ),
        ),
        'staff'   => array(
            'rentals-admin' => array('allow' => null),
        	'rentals' => array('allow' => null),
        ),
    ),
);
