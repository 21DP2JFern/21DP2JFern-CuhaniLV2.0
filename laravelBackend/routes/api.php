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
Route::middleware(['auth:api', 'throttle:60,1'])->group(function () {
    Route::get('/profile', [ApiController::class, 'profile']);
    Route::get('/logout', [ApiController::class, 'logout']);
    Route::get('/deleteUser', [ApiController::class, 'deleteUser']);
    Route::get('/getUserProfile', [ApiController::class, 'getUserProfile']);
    Route::post('/updateProfile', [ApiController::class, 'updateProfile']);
    Route::delete('/deleteProfile', [ApiController::class, 'deleteProfile']);
    Route::post('/createForumPost', [ApiController::class, 'createForumPost']);
    Route::get('/getForumPost', [ApiController::class, 'getForumPost']);
    Route::get('/getAllForumPosts', [ApiController::class, 'getAllForumPosts']);
    Route::get('/post/{id}', [ApiController::class, 'show']);
    Route::delete('/deletePost/{id}', [ApiController::class, 'deletePost']);
    Route::post('/editForumPost/{id}', [ApiController::class, 'editForumPost']);
    Route::get('/countForumPosts', [ApiController::class, 'countForumPosts']);
    Route::post('/searchUsers', [ApiController::class, 'searchUsers']);
    Route::post('/createComment', [ApiController::class, 'createComment']);
    Route::get('/getComments', [ApiController::class, 'getComments']);
    Route::post('/addFriend', [ApiController::class, 'addFriend']);
    Route::get('/getUserFriends', [ApiController::class, 'getUserFriends']);
    Route::get('/getAllUsersForumPosts', [ApiController::class, 'getAllUsersForumPosts']);
    Route::get('/getAllFriendsForumPosts', [ApiController::class, 'getAllFriendsForumPosts']);
});


