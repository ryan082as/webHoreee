<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Catalog\DatabaseCatalogSource;
use Src\Catalog\InCodeCatalogSource;
use Src\Contracts\CatalogSourceInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CatalogSourceInterface::class, function () {
            if (config('app.in_code')) {
                return new InCodeCatalogSource(database_path('databaseInCode.php'));
            }

            return new DatabaseCatalogSource();
        });
    }

    public function boot(): void
    {
        //
    }
}
