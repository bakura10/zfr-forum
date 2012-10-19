<?php

return array(
    'router' => include 'module.config.routes.php',

    'controllers' => array(
        'invokables' => array()
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'ZfrForum' => __DIR__ . '/../view',
        ),
    ),
);
