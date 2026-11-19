<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

if (config('app.in_code', true)) {
    require __DIR__.'/apiInCode.php';
} else {
    Route::get('/stores', [ApiController::class, 'stores']);
    Route::get('/stores/{slug}', [ApiController::class, 'storeDetail']);
    Route::get('/stores/{slug}/barang/{productSlug}', [ApiController::class, 'productDetail']);
}
