<?php

return array(
    'routes' => array(
        'zfrforum' => array(
            'type'    => 'Literal',
            'options' => array(
                'route'    => '/forum/',
                'defaults' => array(
                    'controller' => 'ZfrForum\Controller\Thread'
                )
            ),
            'may_terminate' => true,
            'child_routes'  => array(
            )
        )
    )
);