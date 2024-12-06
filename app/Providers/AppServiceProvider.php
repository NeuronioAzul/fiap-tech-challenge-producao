<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use TechChallenge\Infra\DB\Eloquent\{Order\Model as Order};


class AppServiceProvider extends ServiceProvider
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
        Factory::guessFactoryNamesUsing(function ($modelName) {            
            if ($modelName === Order::class) {
                return 'Database\\Factories\\OrderFactory';
            }
            return 'Database\\Factories\\' . class_basename($modelName) . 'Factory';
        });
    }
}
