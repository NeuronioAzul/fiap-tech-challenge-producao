<?php

use Illuminate\Support\Facades\Route;
use TechChallenge\Api\Order\Order;

Route::controller(Order::class)
    ->prefix('/order')
    ->group(function () {       
        Route::post('/checkout/{id}', [Order::class, "checkout"]);
        Route::post('/status/{id}', [Order::class, "changeStatus"]);
        Route::post('/webhook', [Order::class, "webhook"]);
    });
