<?php

use App\Http\Controllers\ClassGlobais\ControllerMaster;
use App\Http\Controllers\CobrancaTituloController;
use App\Http\Controllers\ControllerCreateCobranca;
use App\Http\Controllers\Itau\BoletoITAU;
use App\Http\Controllers\ITAU\ControllerBaixaBoleto;
use App\Http\Controllers\ITAU\ControllerBuscaFull;
use App\Http\Controllers\ITAU\ControllerCreate;
use App\Http\Controllers\ITAU\ControllerUpdate;
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
Route::get('/itau/Print', [BoletoITAU::class, 'pdf']);
Route::put('/itau/UpdateDate', [ControllerUpdate::class, 'update']);

Route::post('/itau/Baixa', [ControllerBaixaBoleto::class, 'update']);
Route::post('/itau/SeachFull', [ControllerBuscaFull::class, 'index']);


/// rotas globais
Route::post('/BuscarBoleto', [CobrancaTituloController::class, 'GetBoletoPage']);
Route::post('/BuscarBanco', [CobrancaTituloController::class, 'GetBancosPage']);
Route::post('/BuscarCliente', [CobrancaTituloController::class, 'GetClientePage']);



Route::post('/BuscarOne', [CobrancaTituloController::class, 'GetBoleto']);
Route::post('/Cliente', [CobrancaTituloController::class, 'Getcliente']);
Route::post('/Beneficiario', [CobrancaTituloController::class, 'GetBeneficiario']);
Route::get('/logs', [LogController::class, 'showLogs']);