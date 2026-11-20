<?php

declare(strict_types=1);

namespace Src\Controllers;

use Src\Contracts\CatalogSourceInterface;
use Src\Core\Request;
use Src\Core\Response;

class HomeController
{
    public function __construct(
        private CatalogSourceInterface $catalog
    ) {
    }

    public function index(Request $request): Response
    {
        $stores = $this->catalog->getStores();

        return Response::view('home', [
            'stores' => $stores,
            'title' => 'Katalog Multi-Toko',
        ]);
    }
}
