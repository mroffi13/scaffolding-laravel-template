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
            'text' => 'Permissions',
            'route' => 'permissions',
            'icon' => 'fas fa-fingerprint',
            'active' => ['permissions', 'permissions/{permission}', 'permissions/create', 'permissions/{permission}/edit',],
            'permission' => [
                'acl-menu'
            ],
        ],
        [
            'text' => 'Roles',
            'route' => 'roles',
            'icon' => 'fas fa-lock',
            'active' => ['roles', 'roles/{role}', 'roles/create', 'roles/{role}/edit',],
            'permission' => [
                'acl-menu'
            ],
        ],
        [
            'text' => 'Users',
            'route' => 'users',
            'icon' => 'fas fa-users',
            'active' => ['users', 'users/{user}', 'users/create', 'users/{user}/edit',],
            'permission' => [
                'users-menu'
            ],
        ],
    ],
];
