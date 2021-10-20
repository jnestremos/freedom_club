<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'admin' => [],
        'customer' => [
            'customer_order' => 'c,r,u,d',
            'payments' => 'c,r,u,d',
            'credentials' => 'c,r,u,d',
        ],
        'store_owner' => [
            'supplier_profiles' => 'c,r,u,d',
            'employee_profiles' => 'c,r,u,d',
            'stock_inventory' => 'r',
            'transfer_requests' => 'r,u,d',
            'product_inventory' => 'r',
            'shipments' => 'r',
            'sales' => 'r',
            'expenses' => 'c,r,u,d',
            'balance_sheet' => 'r',
            'customer_orders' => 'r,u,d',
            'purchase_records' => 'c,r,u',
        ],
        'warehouse_manager' => [
            'stock_inventory' => 'r',
            'transfer_requests' => 'c,r',
            'product_inventory' => 'r',
            'shipments' => 'r,u',
            'expenses' => 'c,r',
            'customer_orders' => 'r',
        ],
        'product_manager' => [
            'stock_inventory' => 'r',
            'transfer_requests' => 'r',
            'product_inventory' => 'r',
            'shipments' => 'r',
            'expenses' => 'c,r',
            'customer_orders' => 'r',
        ]
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
