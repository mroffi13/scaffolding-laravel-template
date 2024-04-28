<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => true,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'superadministrator' => [
            'users' => 'c,r,u,d,m',
            'acl' => 'c,r,u,d,m',
            'pemagang' => 'c,r,u,d,m',
            'perusahaan-penerima' => 'c,r,u,d,m',
            'organisasi-penerima' => 'c,r,u,d,m',
            'master-status-pemagang' => 'c,r,u,d,m',
            'dashboard' => 'm'
        ],
        'administrator' => [
            'users' => 'c,r,u,d',
            'pemagang' => 'c,r,u,d',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'm' => 'menu'
    ],
];
