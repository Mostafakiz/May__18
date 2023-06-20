<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\products\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\users\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
Route::post('/register',[AuthController::class,'register']);
Route::post('/logout',[AuthController::class,'logout']);;
Route::post('/login',[AuthController::class,'login'])->name('login');


Route::group([

  //'prefix' => 'products',
  //  'middleware' => ['auth:sanctum','throttle:60,1']
    'middleware' => ['auth:sanctum']
],function (){
    Route::match(['put', 'patch'], '/update-user/{id}',[UserController::class,'updateRoles']);
    Route::group([
        'middleware' => 'isadmin'
    ],function(){
        Route::post('/add-product',[ProductController::class,'store']);
        Route::match(['put', 'patch'], '/update-product/{id}',[ProductController::class,'update']);
        Route::delete( '/delete-product/{id}',[ProductController::class,'destroy']);
        Route::get('/u',[UserController::class,'getUsersByRole']);
    });

    Route::get('/all-products',[ProductController::class,'index']);
  //There is something wrong right here....i wish you discover it
    Route::get('/product/{letter}',[ProductController::class,'filterProductsByCategory']);
    Route::get('/product/{id}',[ProductController::class,'show']);
    Route::get('/all-users',[UserController::class,'index']);
    /// reviews
    Route::get('/all-reviews',[ReviewController::class,'index']);
    Route::get('/review/{id}', [ReviewController::class,'show']);
    Route::post('/add-review',[ReviewController::class,'store']);
    Route::match(['put','patch'],'/update-review/{review}',[ReviewController::class,'update']);
    Route::delete('delete-product/{review}',[ReviewController::class,'destroy']);
    Route::get('/g-u-r/{userId}', [ReviewController::class,'getUserReviews']);
    Route::get('/g-p-r/{productId}', [ReviewController::class,'getUProductReviews']);
    Route::get('/g-m-a',[ReviewController::class,'getMostActiveUsers']);
    // order  
    Route::get('/all-orders',[OrderController::class,'index']);
    Route::get('/order/{order}',[OrderController::class,'show']);
    Route::post('/add-order',[OrderController::class,'store']);
    Route::match(['put','patch'],'/update-order/{order}',[OrderController::class,'update']);
    Route::delete('/deler-order',[OrderController::class,'destroy']);
});


