<?php

return array(
    'routes' => array(
        'zfrforum' => array(
            'type'    => 'Literal',
            'options' => array(
                'route'    => '/forum/',
                'defaults' => array(
                    'controller' => 'ZfrForum\Controller\Category'
                )
            ),
            'may_terminate' => true,
            'child_routes'  => array(
                'categories' => array(
                    'type'    => 'Segment',
                    'options' => array(
                        'route'       => '/categories[/:id/:slug]',
                        'constraints' => array(
                            'id'   => '[0-9]+',
                            'slug' => '[a-zA-Z][a-zA-Z0-9_-]*'
                        ),
                        'defaults' => array(
                            'controller' => 'ZfrForum\Controller\Category'
                        )
                    )
                ),

                'threads' => array(
                    'type'    => 'Segment',
                    'options' => array(
                        'route'       => '/threads[/:id/:slug]',
                        'constraints' => array(
                            'id'   => '[0-9]+',
                            'slug' => '[a-zA-Z][a-zA-Z0-9_-]*'
                        ),
                        'defaults' => array(
                            'controller' => 'ZfrForum\Controller\Thread'
                        )
                    )
                ),

                'messages' => array(
                    'type'    => 'Segment',
                    'options' => array(
                        'route'       => '/messages[/:id]',
                        'constraints' => array(
                            'id' => '[0-9]+'
                        ),
                        'defaults' => array(
                            'controller' => 'ZfrForum\Controller\Message'
                        )
                    )
                )
            )
        )
    )
);