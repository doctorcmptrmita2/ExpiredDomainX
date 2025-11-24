<?php

use App\Http\Controllers\Api\DomainApiController;
use App\Http\Middleware\AuthenticateApiKey;
use Illuminate\Support\Facades\Route;

Route::middleware([AuthenticateApiKey::class])->group(function () {
    Route::get('/domains', [DomainApiController::class, 'index'])->name('api.domains.index');
    Route::get('/domains/{domain}', [DomainApiController::class, 'show'])->name('api.domains.show');
});

