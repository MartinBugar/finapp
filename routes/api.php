<?php

use App\Http\Controllers\API\ExpensesController;
use App\Http\Controllers\API\ExpensesTypesController;
use App\Http\Controllers\API\LoansController;
use App\Http\Controllers\API\PostsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('adminlogin', [\App\Http\Controllers\API\AdminController::class, 'adminlogin']);
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix' => 'users','middleware' => 'auth:sanctum'], function() {
    Route::get('/', [\App\Http\Controllers\API\AdminController::class,'index']);
});

Route::group(['prefix' => 'posts','middleware' => 'auth:sanctum'], function() {
    Route::get('/', [PostsController::class,'index']);
    Route::post('add', [PostsController::class,'add']);
    Route::post('update/{id}', [PostsController::class,'update']);
    Route::get('edit/{id}', [PostsController::class,'edit']);
    Route::delete('delete/{id}', [PostsController::class,'delete']);
});

Route::group(['prefix' => 'expenses','middleware' => 'auth:sanctum'], function() {
    Route::get('/', [ExpensesController::class,'index']);
    Route::post('add', [ExpensesController::class,'add']);
    Route::post('update/{id}', [ExpensesController::class,'update']);
    Route::get('edit/{id}', [ExpensesController::class,'edit']);
    Route::delete('delete/{id}', [ExpensesController::class,'delete']);
});

Route::group(['prefix' => 'expensestypes','middleware' => 'auth:sanctum'], function() {
    Route::get('/', [ExpensesTypesController::class,'index']);
    Route::post('add', [ExpensesTypesController::class,'add']);
    Route::post('update/{id}', [ExpensesTypesController::class,'update']);
    Route::get('edit/{id}', [ExpensesTypesController::class,'edit']);
    Route::delete('delete/{id}', [ExpensesTypesController::class,'delete']);
});

Route::group(['prefix' => 'loans','middleware' => 'auth:sanctum'], function() {
    Route::get('/', [LoansController::class,'index']);
    Route::post('add', [LoansController::class,'add']);
    Route::post('update/{id}', [LoansController::class,'update']);
    Route::get('edit/{id}', [LoansController::class,'edit']);
    Route::delete('delete/{id}', [LoansController::class,'delete']);
});
