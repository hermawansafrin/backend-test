<?php

return [
    'title' => 'Product Management',
    'index' => [
        'subtitle' => 'List of products',
        'table' => [
            'no' => 'No',
            'name' => 'Name',
            'price' => 'Price',
            'stock' => 'Stock',
            'is_active' => 'Active Status',
            'action' => 'Action',
        ],
    ],
    'create' => [
        'subtitle' => 'Create new product',
    ],
    'edit' => [
        'subtitle' => 'Edit product',
    ],
    'form' => [
        'name' => ['title' => 'Product Name', 'placeholder' => 'Enter product name..'],
        'price' => ['title' => 'Product Price', 'placeholder' => 'Enter product price..'],
        'stock' => ['title' => 'Product Stock', 'placeholder' => 'Enter product stock..'],
        'is_active' => ['title' => 'Active Status', 'placeholder' => 'Enter active status..'],
    ]
];
