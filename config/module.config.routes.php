<?php

/**
 * Available routes:
 *      /forum : index
 *      /forum/categories/45/my-category : index page of category 45 with slug my-category
 *
 */
return array(
    'routes' => array(
        /**
         * Base route (index page of forum)
         */
        'zfrforum' => array(
            'type'    => 'Literal',
            'options' => array(
                'route'    => '/forum',
                'defaults' => array(
                    'controller' => 'ZfrForum\Controller\Index',
                    'action'     => 'index'
                )
            ),
            'may_terminate' => true,
            'child_routes'  => array(
                /**
                 * Categories route
                 */
                'categories' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => 'categories',
                        'defaults' => array(
                            'controller' => 'ZfrForum\Controller\Category'
                        )
                    ),
                    'may_terminate' => false,
                    'child_routes'  => array(
                        'display' => array(
                            'type'    => 'Segment',
                            'options' => array(
                                'route'       => '/:id/:slug',
                                'constraints' => array(
                                    'id'   => '[0-9]+',
                                    'slug' => '[a-zA-Z][a-zA-Z0-9_-]*'
                                )
                            )
                        )
                    )
                ),

                /**
                 * Threads routes
                 */
                'threads' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => 'threads',
                        'defaults' => array(
                            'controller' => 'ZfrForum\Controller\Thread'
                        )
                    ),
                    'may_terminate' => false,
                    'child_routes'  => array(
                        'display' => array(
                            'type'    => 'Segment',
                            'options' => array(
                                'route'       => '/:id/:slug',
                                'constraints' => array(
                                    'id'   => '[0-9]+',
                                    'slug' => '[a-zA-Z][a-zA-Z0-9_-]*'
                                )
                            )
                        )
                    )
                ),
            )
        )
    )
);
