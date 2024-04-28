<?php

return [
    'menu' => [
        [
            'header' => 'Dashboard',
            'permission' => [
                'dashboard-menu'
            ],
        ],
        [
            'text' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'active' => [
                'dashboard'
            ],
            'permission' => [
                'dashboard-menu'
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
