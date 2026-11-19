<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Src\Contracts\CatalogSourceInterface;

class ProductController extends Controller
{
    public function __construct(
        private CatalogSourceInterface $catalog,
    ) {
    }

    public function show(string $slug, string $productSlug): View
    {
        $store = $this->catalog->findStoreBySlug($slug);
        abort_if(! $store, 404);

        $product = $store->findProductBySlug($productSlug);
        abort_if(! $product, 404);

        return view('product', compact('store', 'product'));
    }
}
