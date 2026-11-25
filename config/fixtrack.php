<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Initial Administrator Configuration
    |--------------------------------------------------------------------------
    |
    | Used by the initial setup command or seeder when environment variables
    | are explicitly provided. Never hardcode production passwords in source.
    |
    */
    'admin' => [
        'name' => env('FIXTRACK_ADMIN_NAME', 'Administrator IT'),
        'email' => env('FIXTRACK_ADMIN_EMAIL'),
        'password' => env('FIXTRACK_ADMIN_PASSWORD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | IT Problem Types
    |--------------------------------------------------------------------------
    */
    'problem_types' => [
        'Perangkat Rusak',
        'Perangkat Tidak Berfungsi',
        'Koneksi Jaringan Bermasalah',
        'Kerusakan Hardware',
        'Kerusakan Software',
        'Kehilangan Perangkat',
        'Kekurangan Stok',
        'Permintaan Pengadaan',
        'Maintenance / Perawatan',
        'Masalah Akses atau Akun',
    ],

    /*
    |--------------------------------------------------------------------------
    | Report Statuses
    |--------------------------------------------------------------------------
    */
    'statuses' => [
        'Pending',
        'Sedang Diproses',
        'Selesai',
    ],
];
