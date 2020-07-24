<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User;
use Illuminate\Support\Facades\Hash;

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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('/task/search', 'TaskController@search');
    Route::resource('task', 'TaskController');
    Route::get('/statuses', 'ApiController@statuses');
    Route::get('/users-count', 'ApiController@getUsersCount');
    Route::put('/setStatus/{id}', 'ApiController@setStatus');
});


Route::post('/login', 'ApiController@login');
