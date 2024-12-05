<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use TechChallenge\Infra\DB\Eloquent\{Product\Model as Product, Category\Model as Category};


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
            if ($modelName === Product::class) {
                return 'Database\\Factories\\ProductFactory';
            }

            if ($modelName === Category::class) {
                return 'Database\\Factories\\CategoryFactory';
            }

            return 'Database\\Factories\\' . class_basename($modelName) . 'Factory';
        });
    }
}
