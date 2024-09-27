<?php

use App\Http\MessageWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [MessageWebhook::class, 'test']);
Route::post('/', [MessageWebhook::class, 'handle']);
