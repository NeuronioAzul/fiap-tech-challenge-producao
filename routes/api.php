<?php

use Illuminate\Support\Facades\Route;
use TechChallenge\Api\Product\Product;
use TechChallenge\Api\Category\Category;

Route::controller(Product::class)
    ->prefix('/product')
    ->group(function () {
        Route::get('/', [Product::class, "index"]);
        Route::get('/{id}', [Product::class, "show"]);
        Route::post('/', [Product::class, "store"]);
        Route::put('/{id}', [Product::class, "update"]);
        Route::delete('/{id}', [Product::class, "delete"]);
    });

Route::controller(Category::class)
    ->prefix('/category')
    ->group(function () {
        Route::get('/', [Category::class, "index"]);
        Route::get('/{id}', [Category::class, "show"]);
        Route::post('/', [Category::class, "store"]);
        Route::put('/{id}', [Category::class, "update"]);
        Route::delete('/{id}', [Category::class, "delete"]);
    });
