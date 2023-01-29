<?php

use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\API\PeopleController;
use App\Http\Controllers\API\OfficerController;
use App\Http\Controllers\API\ComplaintController;

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

Route::get('/', function () {
    return ResponseFormatter::error('', 'Unauthenticated...');
})->name('unauthenticated');

Route::post('/register/officer', [OfficerController::class, 'register']);
Route::post('/register/people', [PeopleController::class, 'register']);

Route::post('/login/officer', [OfficerController::class, 'login']);
Route::post('/login/people', [PeopleController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::group(['middleware' => 'officer'], function () {
        Route::post('/list-officers', [OfficerController::class, 'listOfficers'])->middleware('admin');
        Route::delete('/officer', [OfficerController::class, 'delete'])->middleware('admin');
        Route::post('/upcoming', [OfficerController::class, 'upcoming']);
        Route::post('/report-verification', [OfficerController::class, 'verification']);
        Route::post('/response', [OfficerController::class, 'makeResponse']);
    });

    Route::group(['middleware' => 'masyarakat'], function () {
        Route::post('/complaint', [ComplaintController::class, 'complaint']);
        Route::post('/list-reports', [ComplaintController::class, 'reports']);
        Route::post('/get-profile/people', [PeopleController::class, 'getProfile']);
    });
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
