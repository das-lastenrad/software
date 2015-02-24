<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
		
	'console' => array(
				'router' => array(
						'routes' => array(
								'cronroute' => array(
										'options' => array(
												'route'    => 'test',
												'defaults' => array(
														'__NAMESPACE__' => 'Application\Controller',
														'controller' => 'Console',
														'action' => 'index'
												)
										)
								)
						)
				)
	),
		
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            /*
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            */
            
            'application' => array(
            		'type' => 'segment',
            		'options' => array(
            				'route' => '[/:controller[/:action[/:page]]]',
            				'constraints' => array(
            						'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'page' => '[0-9_-]*',
            				),
            				'defaults' => array(
            						'controller' => 'index',
            						'action' => 'index',
            						'page' => '1',
            				),
            		),
            ),
            
            
            
        ),
    ),
    'service_manager' => array(
    		'factories' => array(
    				'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory'
    		),
    ),
    
    'filters' => array(
    		'invokables'=> array(
    				'stringToUrl'        => 'Application\Filter\StringToUrl',
    				'stringHtmlPurifier' => 'Application\Filter\StringHtmlPurifier',
    		),
    ),

    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Console' => 'Application\Controller\ConsoleController',
            'about'   => 'Application\Controller\AboutController',
            
        ),
    ),
    
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'pagination/sliding'      => __DIR__ . '/../view/pagination/sliding.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
        		'ViewJsonStrategy',
        ),
    ),
    
    'view_helpers' => array(
    		'invokables'=> array(
    				'pageTitle'    => 'Application\View\Helper\PageTitle',
    				'showForm'     => 'Application\View\Helper\ShowForm',
    				'date'         => 'Application\View\Helper\Date',
    		),
    		'factories'=> array(
    				'showMessages' => 'Application\View\Helper\ShowMessagesFactory',
    		),
    ),
    
    // Placeholder for console routes
    /*
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    */
    'session' => array(
    		'save_path' => realpath(LASTENRAD_ROOT . '/data/session'),
    		'name' => 'LASTENRAD_SESSION',
    ),
    'navigation' => array(
    		'default' => array(
    				'service' => array(
    						'type' => 'mvc',
    						'order' => '900',
    						'label' => 'Über uns',
    						'route' => 'application',
    						'controller' => 'about',
    						'action' => 'index',
    						'pages' => array(
    								'team' => array(
    										'type' => 'mvc',
    										'label' => 'Team',
    										'route' => 'application',
    										'controller' => 'about',
    										'action' => 'team',
    								),
    								'contact' => array(
    										'type' => 'mvc',
    										'label' => 'Kontakt',
    										'route' => 'application',
    										'controller' => 'about',
    										'action' => 'contact',
    								),
    								'imprint' => array(
    										'type' => 'mvc',
    										'label' => 'Impressum',
    										'route' => 'application',
    										'controller' => 'about',
    										'action' => 'imprint',
    								),
    						),
    				),
    		),
    ),
    
    
    'acl' => array(
    	'guest' => array(
    		'Application\Controller\Index' => array('allow' => null),
    		'Application\Controller\Console' => array('allow' => null),
    		'kalender' => array('allow' => null),
    		'kalenderjson' => array('allow' => null),
    		'about' => array('allow' => null),
    	), 
    	'customer' => array(
    		'Application\Controller\Index' => array('allow' => null),
    		'kalender' => array('allow' => null),
    		'kalenderjson' => array('allow' => null),
    		'rentals' => array('allow' => null),
    		'about' => array('allow' => null),
    	), 
    
    ),
    
    
    
    
    
    
    
    
    
    
);
