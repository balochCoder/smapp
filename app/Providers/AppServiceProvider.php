<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Inertia\Inertia;
use Illuminate\Support\Facades\Vite;
use Illuminate\Http\Resources\Json\JsonResource;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Model::shouldBeStrict();

        Vite::prefetch(concurrency: 3);

        JsonResource::withoutWrapping();

    }
}
