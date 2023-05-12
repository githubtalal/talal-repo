<?php

return [
    'product' => [
        'name' => 'permissions.product',
        'key' => 'products',
        'main_route' => 'products.index',
        'permissions' => [
            'index' => [
                'route' => 'index',
                'name' => 'permissions.product_permissions.index'
            ],
            'view' => [
                'route' => 'view',
                'name' => 'permissions.product_permissions.view'
            ],
            'create' => [
                'route' => 'create',
                'name' => 'permissions.product_permissions.create'
            ],
            'update' => [
                'route' => 'edit',
                'name' => 'permissions.product_permissions.update'
            ],
            'delete' => [
                'route' => 'delete',
                'name' => 'permissions.product_permissions.delete'
            ],
        ],
    ],
    'order' => [
        'name' => 'permissions.order',
        'key' => 'orders',
        'main_route' => 'orders.index',
        'permissions' => [
            'index' => [
                'route' => 'index',
                'name' => 'permissions.order_permissions.index'
            ],
            'view' => [
                'route' => 'view',
                'name' => 'permissions.order_permissions.view'
            ],
            'create' => [
                'route' => 'create',
                'name' => 'permissions.order_permissions.create'
            ],
            'update' => [
                'route' => 'edit',
                'name' => 'permissions.order_permissions.update'
            ],
            'delete' => [
                'route' => 'delete',
                'name' => 'permissions.order_permissions.delete'
            ],
        ],
    ],
    'category' => [
        'name' => 'permissions.category',
        'key' => 'categories',
        'main_route' => 'category.index',
        'permissions' => [
            'index' => [
                'route' => 'create',
                'name' => 'permissions.category_permissions.index',
            ],
            'view' => [
                'route' => 'create',
                'name' => 'permissions.category_permissions.view',
            ],
            'create' => [
                'route' => 'create',
                'name' => 'permissions.category_permissions.create',
            ],
            'update' => [
                'route' => 'edit',
                'name' => 'permissions.category_permissions.update',
            ],
            'delete' => [
                'route' => 'delete',
                'name' => 'permissions.category_permissions.delete',
            ],
        ],
    ],
    'messenger_bot' => [
        'name' => 'permissions.messenger_bot',
        'key' => 'bot.messenger',
        'main_route' => 'bot.messenger.view',
        'permissions' => [
            'messenger' => [
                'route' => 'view',
                'name' => 'permissions.messenger',
            ],
        ],
    ],
];
