<?php

return array(
    /**
     * Router configuration
     */
    'router' => include 'module.config.routes.php',

    /**
     * Override ZfcUser options
     */
    'zfcuser' => array(
        // We don't want the original User entity to be generated as we have our own extended class
        // for ZfrForum
        'enable_default_entities' => false
    ),

    /**
     * Doctrine configuration
     */
    'doctrine' => array(
        'driver' => array(
            'zfr_forum_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => dirname(__DIR__) . '/src/ZfrForum/Entity'
            ),

            'orm_default' => array(
                'drivers' => array(
                    'ZfrForum\Entity' => 'zfr_forum_driver'
                )
            )
        ),

        'entity_resolver' => array(
            'orm_default' => array(
                'resolvers' => array(
                    'ZfrForum\Entity\UserInterface' => 'ZfrForum\Entity\User'
                )
            )
        ),

        /*'eventmanager' => array(
            'orm_default' => array(
                'subscribers' => array(
                    'ZfrForum\DoctrineExtensions\TablePrefix'
                )
            )
        )*/
    ),

    /**
     * ZfrForum configuration
     */
    'zfr_forum' => array(
        'db_options' => array(
            'table_prefix' => 'Zfr_'
        ),

        'forum_settings' => array(
            /**
             * Count settings
             */
            'num_threads_per_page'    => 25,
            'num_messages_per_thread' => 25,

            /**
             * Style settings
             */
            'styles' => array('Default' => 'default.css'),
            'default_style_name' => 'Default',

            /**
             * Signature settings
             */
            'show_signatures'       => true,
            'max_signatures_length' => 250,

            /**
             * Other
             */
            'can_settings_be_overridden_by_user' => true,
        )
    ),

    /**
     * Controllers
     */
    'controllers' => array(
        'invokables' => array(
            'ZfrForum\Controller\Category' => 'ZfrForum\Controller\CategoryController',
            'ZfrForum\Controller\Message'  => 'ZfrForum\Controller\MessageController',
            'ZfrForum\Controller\Thread'   => 'ZfrForum\Controller\ThreadController'
        )
    ),

    /**
     * View manager configuration
     */
    'view_manager' => array(
        'template_path_stack' => array(
            'ZfrForum' => __DIR__ . '/../view',
        ),
    ),
);
