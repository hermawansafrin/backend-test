<?php

return [
    'title' => 'Manajemen Transaksi Pesanan',
    'index' => [
        'subtitle' => 'Daftar Transaksi Pesanan',
        'table' => [
            'uuid' => 'Kode Transaksi',
            'customer' => 'Pelanggan',
            'total_amount' => 'Total Jumlah',
            'status_flow' => 'Status',
            'paid_date_time' => 'Tanggal Pembayaran',
            'created_at' => 'Dibuat Pada',
            'action' => 'Aksi',
        ],
    ],
    'detail' => [
        'subtitle' => 'Detail Transaksi Pesanan',
    ],
    'create' => [
        'subtitle' => 'Buat Transaksi Pesanan Baru',
    ],
    'edit' => [
        'subtitle' => 'Edit Transaksi Pesanan',
    ],
    'form' => [
        'customer_id' => ['title' => 'Pelanggan', 'placeholder' => 'Pilih pelanggan..'],
        'status_flow_id' => ['title' => 'Status', 'placeholder' => 'Pilih status..'],
        'discount_percentage' => ['title' => 'Persentase Diskon', 'placeholder' => 'Masukkan persentase diskon..'],
        'paid_date_time' => ['title' => 'Tanggal Pembayaran', 'placeholder' => 'Masukkan tanggal pembayaran..'],
        'products' => [
            'product_id' => ['title' => 'Produk', 'placeholder' => 'Pilih produk..'],
            'qty' => ['title' => 'Jumlah', 'placeholder' => 'Masukkan jumlah..'],
        ],
        'note' => ['title' => 'Catatan', 'placeholder' => 'Masukkan catatan..'],
    ],
    'info' => [
        'title_customer' => 'Data Pelanggan',
        'title_transaction' => 'Data Transaksi',
        'title_transaction_items' => 'Item Transaksi',
        'customer' => [
            'name' => 'Nama',
            'email' => 'Email',
            'phone' => 'Telepon',
        ],
        'transaction' => [
            'total_amount' => 'Total Jumlah',
            'total_without_discount' => 'Total Jumlah Tanpa Diskon',
            'total_discount' => 'Total Diskon',
            'discount_percentage' => 'Persentase Diskon',
            'paid_date_time' => 'Tanggal Pembayaran',
            'status_flow' => 'Status',
            'note' => 'Catatan',
            'code' => 'Kode',
            'created_at' => 'Dibuat Pada',
            'created_user' => 'Dibuat Oleh',
            'updated_user' => 'Diperbarui Oleh',
            'items' => [
                'product' => 'Produk',
                'qty' => 'Jumlah',
                'price' => 'Harga',
                'price_per_item' => 'Harga Per Item',
                'total' => 'Total',
            ],
        ],
    ],
];
