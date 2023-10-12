<?php

use App\Http\Controllers\Api\NominationController;
use App\Http\Controllers\Api\OperatorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {

    Route::get('/', function () {
        return [
            'Laravel version' => app()->version(),
            'PHP version' => PHP_VERSION
        ];
    });

    Route::post('/nominations', [NominationController::class,'index']);
    Route::post('/nominations/create', [NominationController::class,'store']);

    Route::post('/operators', [OperatorController::class,'index']);
    Route::post('/operators/create', [OperatorController::class,'store']);

});

