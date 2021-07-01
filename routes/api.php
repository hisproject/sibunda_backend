<?php

use App\Http\Controllers\Mobile\AuthController;
use App\Http\Controllers\Mobile\DataController;
use App\Http\Controllers\Mobile\KehamilankuController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function (){
    // without auth
    Route::post('login', [AuthController::class, 'login']);
    /*Route::post('register', [AuthController::class, 'register']);*/
    Route::post('register', [DataController::class, 'createBundaUser']);
    // with auth
    Route::middleware('auth:api')->group(function () {
        Route::get('logout', [AuthController::class, 'logout']);
    });
});

Route::prefix('data')->group(function() {
    // with auth
    Route::middleware('auth:api')->group(function () {
        Route::get('kota', [DataController::class, 'getKota']);
        Route::post('identitas-anak', [DataController::class, 'createDataAnak']);
        Route::post('identitas-ibu', [DataController::class, 'createDataIbu']);
        Route::post('identitas-ayah', [DataController::class, 'createDataAyah']);
        Route::put('identitas-anak', [DataController::class, 'updateDataAnak']);
        Route::put('identitas-ibu', [DataController::class, 'updateDataIbu']);
        Route::put('identitas-ayah', [DataController::class, 'updateDataAyah']);
    });
});

Route::prefix('kehamilanku')->group(function() {
   Route::middleware('auth:api')->group(function() {
       Route::get('overview', [KehamilankuController::class, 'getOverview']);
       Route::post('create-weekly-report', [KehamilankuController::class, 'createTriSemesterData']);
       Route::post('show-weekly-report', [KehamilankuController::class, 'showWeeklyTrisemesterData']);
       Route::post('show-weekly-report-analysis', [KehamilankuController::class, 'showTriSemesterAnalysis']);
       Route::get('immunization', [KehamilankuController::class, 'getImmunizationData']);
       Route::post('immunization', [KehamilankuController::class, 'createImmunizationData']);
       Route::get('graph/tfu', [KehamilankuController::class, 'getTfuGraphData']);
       Route::get('graph/djj', [KehamilankuController::class, 'getDjjGraphData']);
       Route::get('graph/map', [KehamilankuController::class, 'getMapGraphData']);
       Route::get('graph/mom-weight', [KehamilankuController::class, 'getWeightGraphData']);
   }) ;
});

Route::prefix('anaku')->group(function() {
    Route::middleware('auth:api')->group(function() {

    });
});
