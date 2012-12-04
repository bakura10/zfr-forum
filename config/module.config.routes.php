<?php

/**
 * Important note : ZfrForum is a fully client-side forum, which means that it is rendered in the client using
 * a JavaScript MVC library (in our case, we provide an integration with AngularJS, but you may override the views
 * to provide your own integration with another client-side library).
 *
 * This means that all routes are simply dispatched to the same action (index action of IndexController), and then,
 * the JS framework takes care of the routing. There are two exceptions : all the routes that begin by /forum/api,
 * which leads to REST API, and all the routes that begin by /forum/static, which are static routes that are here
 * just for SEO purposes (more on that in the doc).
 *
 * About the REST, here is the API. It should be pretty self-explanatory:
 *
 *  - /forum/api/categories
 *  - /forum/api/categories/4
 *  - /forum/api/categories/4/threads[?page=1&limit=25]
 *  - /forum/api/threads/5
 *  - /forum/api/threads/5/posts[?page=1&limit=25]
 *  - /forum/api/posts/6
 *  - /forum/api/users/7/threads[?page=1&limit=25]
 *  - /forum/api/users/7/posts[?page=1&limit=25]
 */
return array(
    'routes' => array(
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
                 * All the routes that begin by /forum/... are dispatched to the same action in same controller...
                 */
                'wildcard' => array(
                    'type'     => 'Wildcard',
                    'priority' => 500
                ),

                /**
                 * ... except all the API routes (that begin by /forum/api...
                 */
                'api' => array(
                    'type'     => 'Literal',
                    'priority' => 1000,
                    'options'  => array(
                        'route' => '/api'
                    ),
                    'may_terminate' => false,
                    'child_routes'  => array(
                        /**
                         * Categories end-point
                         */
                        'categories' => array(
                            'type'    => 'Literal',
                            'options' => array(
                                'route'    => '/categories',
                                'defaults' => array(
                                    'controller' => 'ZfrForum\Controller\CategoryRest',
                                    'action'     => null
                                )
                            ),
                            'may_terminate' => true,
                            'child_routes'  => array(
                                'threads' => array(
                                    'type'    => 'Segment',
                                    'options' => array(
                                        'route'       => '/:categoryId/threads',
                                        'constraints' => array(
                                            'categoryId' => '[0-9]+'
                                        ),
                                        'defaults' => array(
                                            'controller' => 'ZfrForum\Controller\ThreadRest'
                                        )
                                    )
                                )
                            )
                        ),

                        /**
                         * Threads end-point
                         */
                        'threads' => array(
                            'type'    => 'Literal',
                            'options' => array(
                                'route'    => '/threads',
                                'defaults' => array(
                                    'controller' => 'ZfrForum\Controller\ThreadRest',
                                    'action'     => null
                                )
                            ),
                            'may_terminate' => true,
                            'child_routes'  => array(
                                'threads' => array(
                                    'type'    => 'Segment',
                                    'options' => array(
                                        'route'       => '/:threadId/posts',
                                        'constraints' => array(
                                            'threadId' => '[0-9]+'
                                        ),
                                        'defaults' => array(
                                            'controller' => 'ZfrForum\Controller\PostRest'
                                        )
                                    )
                                )
                            )
                        ),

                        /**
                         * Posts end-point
                         */
                        'posts' => array(
                            'type'    => 'Literal',
                            'options' => array(
                                'route'    => '/posts',
                                'defaults' => array(
                                    'controller' => 'ZfrForum\Controller\PostRest',
                                    'action'     => null
                                )
                            )
                        ),

                        /**
                         * Users end-point
                         */
                        'users' => array(
                            'type'    => 'Literal',
                            'options' => array(
                                'route'    => '/users',
                                'defaults' => array(
                                    'controller' => 'ZfrForum\Controller\UserRest',
                                    'action'     => null
                                )
                            ),
                            'may_terminate' => true,
                            'child_routes'  => array(
                                'threads' => array(
                                    'type'    => 'Segment',
                                    'options' => array(
                                        'route' => '/:userId/threads'
                                    )
                                ),
                                'posts' => array(
                                    'type'    => 'Segment',
                                    'options' => array(
                                        'route' => '/:userId/posts'
                                    )
                                )
                            )
                        )
                    )
                ),

                /**
                 * ... and all the static pages for SEO
                 */
                'static' => array(
                    'type'     => 'Literal',
                    'priority' => 1000,
                    'options'  => array(
                        'route' => '/static'
                    ),
                    'may_terminate' => false
                )
            )
        )
    )
);