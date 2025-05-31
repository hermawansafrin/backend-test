<?php

return [
    'title' => 'Manajemen Produk',
    'index' => [
        'subtitle' => 'Daftar Produk',
        'table' => [
            'no' => 'No',
            'name' => 'Nama',
            'price' => 'Harga',
            'stock' => 'Stok',
            'is_active' => 'Status Aktif',
            'action' => 'Aksi',
        ],
    ],
    'create' => [
        'subtitle' => 'Buat Produk Baru',
    ],
    'edit' => [
        'subtitle' => 'Edit Produk',
    ],
    'form' => [
        'name' => ['title' => 'Nama Produk', 'placeholder' => 'Masukkan nama produk..'],
        'price' => ['title' => 'Harga Produk', 'placeholder' => 'Masukkan harga produk..'],
        'stock' => ['title' => 'Stok Produk', 'placeholder' => 'Masukkan stok produk..'],
        'is_active' => ['title' => 'Status Aktif', 'placeholder' => 'Masukkan status aktif..'],
    ]
];
