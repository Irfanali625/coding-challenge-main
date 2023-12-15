<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ConnectionController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('get-suggestions', [ConnectionController::class, 'getSuggestions']);
    Route::post('send-connection', [ConnectionController::class, 'sendConnection']);
    Route::get('get-connection-requests', [ConnectionController::class, 'getConnectionRequests']);
    Route::get('get-recent-requests', [ConnectionController::class, 'getRecentRequests']);
    Route::post('accept-connection-request', [ConnectionController::class, 'acceptConnectionRequest']);
    Route::get('get-connections', [ConnectionController::class, 'getConnection']);
    Route::post('remove-connection-request', [ConnectionController::class, 'removeConnectionRequest']);
    Route::get('get-common-connections/{id}', [ConnectionController::class, 'getCommonConnections']);
    Route::get('get-connections-count', [ConnectionController::class, 'getConnectionsCount']);
});
