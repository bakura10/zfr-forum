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
            )
        )
    )
);