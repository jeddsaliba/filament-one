<?php

use Illuminate\Support\Facades\Route;

Route::domain(config('app.url'))->group(function () {
    Route::get('/{slug}', App\Http\Controllers\PageBuilderController::class);
});