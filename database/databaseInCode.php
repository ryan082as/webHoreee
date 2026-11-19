<?php

/**
 * Database sementara di dalam kode (dipakai saat APP_IN_CODE=true sebelum server/DB dibuat).
 */
return [
    'stores' => [
        [
            'id' => 1,
            'slug' => 'toko-a',
            'name' => 'Toko A',
            'category' => 'Kategori A',
            'description' => 'Deskripsi singkat Toko A',
            'address' => 'Alamat Toko A',
            'whatsapp_number' => '+62 857-0424-4288',
            'operational_hours' => '08.00 - 17.00',
        ],
        [
            'id' => 2,
            'slug' => 'toko-b',
            'name' => 'Toko B',
            'category' => 'Kategori B',
            'description' => 'Deskripsi singkat Toko B',
            'address' => 'Alamat Toko B',
            'whatsapp_number' => '+62 895-8107-11221',
            'operational_hours' => '09.00 - 21.00',
        ],
    ],

    'products' => [
        [
            'id' => 101,
            'store_id' => 1,
            'slug' => 'barang-contoh-1',
            'name' => 'Barang Contoh 1',
            'price' => 50000,
            'thumbnail_url' => null,
            'image_url' => null,
            'description' => 'Deskripsi barang contoh 1',
            'specs' => [
                'Ukuran' => 'M / L',
                'Berat' => '250 gram',
                'Varian' => 'Hitam',
                'Bahan' => 'Katun',
                'Status Stok' => 'Tersedia',
            ],
        ],
        [
            'id' => 102,
            'store_id' => 1,
            'slug' => 'barang-contoh-2',
            'name' => 'Barang Contoh 2',
            'price' => 75000,
            'thumbnail_url' => null,
            'image_url' => null,
            'description' => 'Deskripsi barang contoh 2',
            'specs' => [
                'Ukuran' => 'Standar',
                'Berat' => '300 gram',
                'Varian' => 'Putih',
                'Bahan' => 'Kanvas',
                'Status Stok' => 'Tersedia',
            ],
        ],
        [
            'id' => 201,
            'store_id' => 2,
            'slug' => 'barang-toko-b-1',
            'name' => 'Barang Toko B 1',
            'price' => 25000,
            'thumbnail_url' => null,
            'image_url' => null,
            'description' => 'Deskripsi barang contoh Toko B',
            'specs' => [
                'Ukuran' => '250 ml',
                'Berat' => '280 gram',
                'Varian' => 'Original',
                'Bahan' => 'Pilihan',
                'Status Stok' => 'Tersedia',
            ],
        ],
    ],
];
