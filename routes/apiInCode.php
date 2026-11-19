<?php

use Illuminate\Support\Facades\Route;
use Src\Contracts\CatalogSourceInterface;
use Src\Entities\StoreEntity;

// API sementara membaca sumber data in-code melalui CatalogSourceInterface
Route::get('/stores', function (CatalogSourceInterface $catalog) {
    return response()->json(
        array_map(fn (StoreEntity $store) => $store->toArray(), $catalog->getStores()),
    );
});

Route::get('/stores/{slug}', function (string $slug, CatalogSourceInterface $catalog) {
    $store = $catalog->findStoreBySlug($slug);
    abort_if(! $store, 404);

    return response()->json($store->toArray());
});

Route::get('/stores/{slug}/barang/{productSlug}', function (string $slug, string $productSlug, CatalogSourceInterface $catalog) {
    $store = $catalog->findStoreBySlug($slug);
    abort_if(! $store, 404);

    $product = $store->findProductBySlug($productSlug);
    abort_if(! $product, 404);

    return response()->json($product->toArray());
});
