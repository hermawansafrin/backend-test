<?php

return [
    'title' => 'Manajemen Pengguna',
    'index' => [
        'subtitle' => 'Daftar Pengguna',
        'table' => [
            'no' => 'No',
            'name' => 'Nama',
            'email' => 'Email',
            'role' => 'Peran',
            'active_status' => 'Status',
            'action' => 'Action',
        ],
    ],
    'create' => [
        'subtitle' => 'Buat Pengguna Baru',
    ],
    'edit' => [
        'subtitle' => 'Edit Pengguna',
    ],
    'form' => [
        'name' => ['title' => 'Nama Pengguna', 'placeholder' => 'Masukkan nama pengguna..'],
        'email' => ['title' => 'Email Pengguna', 'placeholder' => 'Masukkan email pengguna..'],
        'role_id' => ['title' => 'Peran Pengguna', 'placeholder' => 'Pilih peran pengguna..'],
        'is_active' => ['title' => 'Status Pengguna', 'placeholder' => 'Pilih status pengguna..'],
    ]
];
