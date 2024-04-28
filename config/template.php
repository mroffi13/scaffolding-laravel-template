<?php

return [
    'menu' => [
        [
            'header' => 'Dashboard',
        ],
        [
            'text' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'active' => [
                'dashboard'
            ],
            'permission' => [
                'dashboard'
            ],
            // 'sub_menu' => [
            //     [
            //         'text' => 'Dashboard',
            //         'route' => 'dashboard',
            //         'active' => [
            //             'dashboard'
            //         ]
            //     ],
            //     [
            //         'text' => 'Dashboard',
            //         'route' => 'dashboard',
            //         'active' => [
            //             'dashboard/{id}'
            //         ]
            //     ],
            // ]
        ],
    ],
];
