<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $db = require database_path('databaseInCode.php');
        $storeIdMap = [];

        foreach ($db['stores'] as $storeData) {
            $store = Store::query()->updateOrCreate(
                ['slug' => $storeData['slug']],
                [
                    'name' => $storeData['name'],
                    'category' => $storeData['category'],
                    'description' => $storeData['description'],
                    'address' => $storeData['address'],
                    'whatsapp_number' => $storeData['whatsapp_number'],
                    'operational_hours' => $storeData['operational_hours'],
                ],
            );

            $storeIdMap[$storeData['id']] = $store->id;
        }

        foreach ($db['products'] as $productData) {
            $storeId = $storeIdMap[$productData['store_id']] ?? null;
            if (! $storeId) {
                continue;
            }

            Product::query()->updateOrCreate(
                [
                    'store_id' => $storeId,
                    'slug' => $productData['slug'],
                ],
                [
                    'name' => $productData['name'],
                    'price' => $productData['price'],
                    'thumbnail_url' => $productData['thumbnail_url'],
                    'image_url' => $productData['image_url'],
                    'description' => $productData['description'],
                    'specs' => $productData['specs'],
                ],
            );
        }
    }
}
