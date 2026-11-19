<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Src\Contracts\CatalogSourceInterface;

class StoreController extends Controller
{
    public function __construct(
        private CatalogSourceInterface $catalog,
    ) {
    }

    public function index(): View
    {
        return view('home', [
            'stores' => $this->catalog->getStores(),
        ]);
    }

    public function show(string $slug): View
    {
        $store = $this->catalog->findStoreBySlug($slug);
        abort_if(! $store, 404);

        return view('store', [
            'store' => $store,
        ]);
    }
}
