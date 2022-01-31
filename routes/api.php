<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

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

// Public routes

Route::Get('test',function (){
    Storage::disk('public')->delete('UUij7xxPT9dIsxzSHtUwqBpJBLdHpbsAUiG4PO1j.png');
});

// Users routes
Route::Post('authenticate',[UserController::class,'authenticate']);
Route::Get('me',[UserController::class,'me']);

// Posts routes
Route::Get('posts/public',[PostController::class,'index'])->name('list.post');
Route::Get('posts/{id}',[PostController::class,'show'])->name('get.post');
Route::Post('posts/{id}',[PostController::class,'update']);

// Authentication Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::resources([
        'users' => UserController::class,
        'categories' => CategoryController::class,
        'posts' => PostController::class,
    ]);
});



