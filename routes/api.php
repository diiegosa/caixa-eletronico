<?php

use App\Http\Controllers\Api\AtmFillController;
use App\Http\Controllers\Api\WithdrawController;
use Illuminate\Support\Facades\Route;

Route::post('/fill', [AtmFillController::class, 'store']);
Route::post('/withdraw', [WithdrawController::class, 'store']);
