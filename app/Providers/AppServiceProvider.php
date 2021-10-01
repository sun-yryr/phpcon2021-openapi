<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tests\Feature\OpenApiValidator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OpenApiValidator::class, function ($app) {
            return new OpenApiValidator('schema.json');
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
