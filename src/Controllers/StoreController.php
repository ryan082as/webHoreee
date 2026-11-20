<?php

declare(strict_types=1);

namespace Src\Controllers;

use Src\Contracts\CatalogSourceInterface;
use Src\Core\Request;
use Src\Core\Response;

class StoreController
{
    public function __construct(
        private CatalogSourceInterface $catalog
    ) {
    }

    public function show(Request $request, string $slug): Response
    {
        $store = $this->catalog->findStoreBySlug($slug);

        if ($store === null) {
            return new Response('Toko tidak ditemukan', 404);
        }

        return Response::view('store', [
            'store' => $store,
            'title' => $store->getName(),
        ]);
    }
}
