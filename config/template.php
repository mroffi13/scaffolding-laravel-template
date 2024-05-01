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
        [
            'header' => 'Access Control',
            'permission' => [
                'acl-menu',
                'users-menu'
            ],
        ],
        [
            'text' => 'Users',
            'route' => 'users',
            'icon' => 'fas fa-users',
            'active' => ['users', 'users/{id}', 'users/create', 'users/{id}/edit',],
            'permission' => [
                'users-menu'
            ],
        ],
    ],
];
