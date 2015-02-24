<?php

/**
 * Blog module configuration
 * 
 * @package    Blog
 */
return array(
    'router' => array(
        'routes' => array(
            'blog' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/blog',
                    'constraints' => array(
                    ),
                    'defaults' => array(
                        'controller' => 'blog',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'action' => array(
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
                    'page' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/:page',
                            'constraints' => array(
                                'page'   => '[0-9]+',
                            ),
                        ),
                    ),
                    'rss' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/rss',
                            'defaults' => array(
                                'action'     => 'rss',
                            ),
                        ),
                    ),
                ),
            ),
            'blog-admin' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/blog-admin',
                    'defaults' => array(
                        'controller' => 'blog-admin',
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
            'blog'       => 'Blog\Controller\BlogControllerFactory',
            'blog-admin' => 'Blog\Controller\AdminControllerFactory',
        ),
    ),
    
    'service_manager' => array(
        'invokables' => array(
            'Blog\Entity\Blog'   => 'Blog\Entity\BlogEntity',
        ),
        'factories' => array(
            'Blog\Table\Blog'    => 'Blog\Table\BlogTableFactory',
            'Blog\Form\Create'   => 'Blog\Form\CreateFormFactory',
            'Blog\Form\Update'   => 'Blog\Form\UpdateFormFactory',
            'Blog\Form\Delete'   => 'Blog\Form\DeleteFormFactory',
            'Blog\Service\Blog'  => 'Blog\Service\BlogServiceFactory',
        ),
    ),
    
    'input_filters' => array(
        'invokables' => array(
            'Blog\Filter\Blog'   => 'Blog\Filter\BlogFilter',
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewFeedStrategy',
        ),
    ),
    
    'navigation' => array(
        'default' => array(
            'blog' => array(
                'type'       => 'mvc',
                'order'      => '500',
                'label'      => 'Blog',
                'route'      => 'blog',
                'controller' => 'blog',
                'action'     => 'index',
                'pages'      => array(
                    'show' => array(
                        'type'       => 'mvc',
                        'label'      => 'Anzeigen',
                        'route'      => 'blog',
                        'controller' => 'blog',
                        'action'     => 'show',
                    ),
                    'blog-admin' => array(
                        'type'       => 'mvc',
                        'label'      => 'Blogverwaltung',
                        'route'      => 'blog-admin',
                        'controller' => 'blog-admin',
                        'action'     => 'index',
                    ),
                    'create' => array(
                        'type'       => 'mvc',
                        'label'      => 'Anlegen',
                        'route'      => 'blog-admin',
                        'controller' => 'blog-admin',
                        'action'     => 'create',
                    ),
                    'update' => array(
                        'type'       => 'mvc',
                        'label'      => 'Bearbeiten',
                        'route'      => 'blog-admin',
                        'controller' => 'blog-admin',
                        'action'     => 'update',
                    ),
                    'delete' => array(
                        'type'       => 'mvc',
                        'label'      => 'Löschen',
                        'route'      => 'blog-admin',
                        'controller' => 'blog-admin',
                        'action'     => 'delete',
                    ),
                ),
            ),
        ),
    ),
    
    'acl' => array(
        'guest'   => array(
            'blog' => array('allow' => null),
        ),
        'staff'   => array(
            'blog-admin' => array('allow' => null),
        ),
    ),
);
