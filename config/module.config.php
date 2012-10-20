<?php

return array(
    /**
     * Router configuration
     */
    'router' => include 'module.config.routes.php',

    /**
     * Doctrine configuration
     */
    'doctrine' => array(
        'driver' => array(
            'zfr_forum_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array('module/ZfrForum/src/ZfrForum/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'ZfrForum\Entity' => 'zfr_forum_driver'
                )
            )
        ),

        'eventmanager' => array(
            'orm_default' => array(
                'subscribers' => array(
                    'ZfrForum\DoctrineExtensions\TablePrefix'
                )
            )
        )
    ),

    /**
     * ZfrForum configuration
     */
    'zfr_forum' => array(
        'tables_prefix' => 'Zfr_'
    ),

    /**
     * Controllers
     */
    'controllers' => array(
        'invokables' => array()
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
