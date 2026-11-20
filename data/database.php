<?php

declare(strict_types=1);

/**
 * Penyimpanan Data Katalog & Produk (Object Class / Array PHP).
 * Sesuai Catatan Soal UTS: "Untuk penyimpanan e-book/katalog boleh menggunakan XML/JSON atau juga Object Class PHP".
 */
return [
    'stores' => [
        [
            'id' => 1,
            'slug' => 'toko-a',
            'name' => 'Toko A',
            'category' => 'Kategori A',
            'description' => 'Deskripsi singkat Toko A - Penyedia busana dan perlengkapan berkualitas.',
            'address' => 'Jl. Raya Tlogomas No. 246, Malang',
            'whatsapp_number' => '+62 857-0424-4288',
            'operational_hours' => '08.00 - 17.00 WIB',
        ],
        [
            'id' => 2,
            'slug' => 'toko-b',
            'name' => 'Toko B',
            'category' => 'Kategori B',
            'description' => 'Deskripsi singkat Toko B - Pilihan produk herbal dan minuman segar.',
            'address' => 'Jl. Bendungan Sutami No. 12, Malang',
            'whatsapp_number' => '+62 895-8107-11221',
            'operational_hours' => '09.00 - 21.00 WIB',
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
            'description' => 'Barang Contoh 1 terbuat dari katun premium dengan daya serap tinggi dan sangat nyaman dipakai sehari-hari.',
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
            'description' => 'Barang Contoh 2 merupakan tas kanvas stylish yang kuat untuk membawa barang bawaan kuliah atau kerja.',
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
            'description' => 'Minuman olahan herbal alami tanpa bahan pengawet dengan khasiat menyegarkan tubuh.',
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
