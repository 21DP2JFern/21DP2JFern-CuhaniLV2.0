<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

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
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
   // return $request->user();
//});

Route::post('/register', [ApiController::class, 'register']);
Route::post('/login', [ApiController::class, 'login']);
// Protected routes
Route::group([
    "middleware" => "auth:api"
], function (){
    Route::get('/profile', [ApiController::class, 'profile']);
    Route::get('/logout', [ApiController::class, 'logout']);
    Route::get('/deleteUser', [ApiController::class, 'deleteUser']);
    Route::get('/getUserProfile', [ApiController::class, 'getUserProfile']);
    Route::post('/updateProfile', [ApiController::class,'updateProfile']);
    Route::delete('/deleteProfile', [ApiController::class, 'deleteProfile']);
    Route::post('/createForumPost', [ApiController::class, 'createForumPost']);
    Route::get('/getForumPost', [ApiController::class,'getForumPost']);
    Route::get('/getAllForumPosts', [ApiController::class,'getAllForumPosts']);
    // routes/web.php
    Route::get('/post/{id}', [ApiController::class, 'show']);
    Route::middleware('auth:api')->delete('/deletePost/{id}', [ApiController::class, 'deletePost']);
    Route::post('/editForumPost/{id}', [ApiController::class, 'editForumPost']);
    Route::get('/countForumPosts', [ApiController::class, 'countForumPosts']);
/*     Route::get('/getForumPost/{id}', [ApiController::class,'getForumPost']); */

Route::post('/createComment', [ApiController::class, 'createComment']);
Route::get('/getComments', [ApiController::class, 'getComments']);


});


