<?php

namespace App\Providers;

use App\Interfaces\QuoteRepositoryInterface;
use App\Repositories\QuoteRepository;
use Illuminate\Support\ServiceProvider;

class QuoteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(QuoteRepositoryInterface::class, QuoteRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
