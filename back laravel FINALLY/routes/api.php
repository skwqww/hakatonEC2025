<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;

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
//
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// Login route
Route::post('login', [ProfileController::class, 'login']);

// User-related routes with authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [ProfileController::class, 'logout']);
    Route::get('getUser', [ProfileController::class, 'getUser']);
    Route::get('getUserRole', [ProfileController::class, 'getUserRole']);
    Route::post('setWorkTime', [ProfileController::class, 'setWorkTime']);

    Route::get('getChats', [MessageController::class, 'getChats']);
    Route::post('getMessages', [MessageController::class, 'getMessages']);
    Route::post('sendMessage', [MessageController::class, 'sendMessage']);
    Route::post('updateMessage', [MessageController::class, 'updateMessage']);
    Route::post('deleteMessage', [MessageController::class, 'deleteMessage']);

});

// Admin routes with authentication and 'admin' prefix
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::get('getUsers', [AdminController::class, 'index']);
    Route::get('getRoles', [AdminController::class, 'getRoles']);
    Route::post('createUser', [AdminController::class, 'store']);
    Route::get('getUser/{id}', [AdminController::class, 'show']);
    Route::post('updateUser/{id}', [AdminController::class, 'update']);
    Route::post('deleteUser/{id}', [AdminController::class, 'destroy']);

    Route::post('storeChat', [AdminController::class, 'storeChat']);
    Route::post('addUserChat', [AdminController::class, 'addUserChat']);
    Route::get('getChats', [AdminController::class, 'getChats']);

});
