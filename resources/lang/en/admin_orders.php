<?php

return [
    'title' => 'Order Transaction Management',
    'index' => [
        'subtitle' => 'Order Transaction List',
        'table' => [
            'uuid' => 'Transaction Code',
            'customer' => 'Customer',
            'total_amount' => 'Total Amount',
            'status_flow' => 'Status',
            'paid_date_time' => 'Payment Date',
            'created_at' => 'Created At',
            'action' => 'Action',
        ],
    ],
    'detail' => [
        'subtitle' => 'Order Transaction Detail',
    ],
    'create' => [
        'subtitle' => 'Create New Order Transaction',
    ],
    'edit' => [
        'subtitle' => 'Edit Order Transaction',
    ],
    'form' => [
        'customer_id' => ['title' => 'Customer', 'placeholder' => 'Select customer..'],
        'status_flow_id' => ['title' => 'Status', 'placeholder' => 'Select status..'],
        'discount_percentage' => ['title' => 'Discount Percentage', 'placeholder' => 'Enter discount percentage..'],
        'paid_date_time' => ['title' => 'Payment Date', 'placeholder' => 'Enter payment date..'],
        'products' => [
            'product_id' => ['title' => 'Product', 'placeholder' => 'Select product..'],
            'qty' => ['title' => 'Quantity', 'placeholder' => 'Enter quantity..'],
        ],
        'note' => ['title' => 'Note', 'placeholder' => 'Enter note..'],
    ],
    'info' => [
        'title_customer' => 'Customer Data',
        'title_transaction' => 'Transaction Data',
        'title_transaction_items' => 'Transaction Items',
        'customer' => [
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
        ],
        'transaction' => [
            'total_amount' => 'Total Amount',
            'total_without_discount' => 'Total Amount Without Discount',
            'total_discount' => 'Total Discount',
            'discount_percentage' => 'Discount Percentage',
            'paid_date_time' => 'Payment Date',
            'status_flow' => 'Status',
            'note' => 'Note',
            'code' => 'Code',
            'created_at' => 'Created At',
            'created_user' => 'Created By',
            'updated_user' => 'Updated By',
            'items' => [
                'product' => 'Product',
                'qty' => 'Quantity',
                'price' => 'Price',
                'price_per_item' => 'Price Per Item',
                'total' => 'Total',
            ],
        ],
    ],
];
