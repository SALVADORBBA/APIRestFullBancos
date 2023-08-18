<?php

use App\Http\Controllers\ClassGlobais\ControllerMaster;
use App\Http\Controllers\CobrancaTituloController;
use App\Http\Controllers\ControllerCreateCobranca;
use App\Http\Controllers\ITAU\ControllerCreate;
use App\Http\Controllers\LogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::post('/itau/master', [ControllerMaster::class, 'GetCreate']);
Route::post('/itau/Create', [ControllerCreate::class, 'store']);
Route::post('/Cobranca/Create', [ControllerCreateCobranca::class, 'create']);


/// rotas globais
Route::post('/Buscar', [CobrancaTituloController::class, 'GetBoletoPage']);
Route::post('/BuscarOne', [CobrancaTituloController::class, 'GetBoleto']);
Route::post('/Cliente', [CobrancaTituloController::class, 'Getcliente']);
Route::post('/Beneficiario', [CobrancaTituloController::class, 'GetBeneficiario']);
Route::get('/logs', [LogController::class, 'showLogs']);
