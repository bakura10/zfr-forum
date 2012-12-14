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
     * Controllers
     */
    'controllers' => array(
        'invokables' => array(
            'ZfrForum\Controller\Category' => 'ZfrForum\Controller\CategoryController',
            'ZfrForum\Controller\Index'    => 'ZfrForum\Controller\IndexController',
            'ZfrForum\Controller\Post'     => 'ZfrForum\Controller\PostController',
            'ZfrForum\Controller\Thread'   => 'ZfrForum\Controller\ThreadController'
        )
    ),

    /**
     * View manager configuration
     */
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy'
        ),

        'template_map' => array(
            'zfr-forum/category/display' => __DIR__ . '/../view/zfr-forum/category/display.phtml',
            'zfr-forum/index/index'      => __DIR__ . '/../view/zfr-forum/index/index.phtml',
            'zfr-forum/post/display'     => __DIR__ . '/../view/zfr-forum/post/display.phtml',
            'zfr-forum/thread/display'   => __DIR__ . '/../view/zfr-forum/thread/display.phtml'
        ),
    ),
);
