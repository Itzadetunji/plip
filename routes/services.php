<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\MonoWebhookController;

Route::post('/mono/webhook', MonoWebhookController::class)
    ->middleware("mono.secure")
    ->name("mono.webhook");
