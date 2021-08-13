<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VerifyEmailController;

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

Route::post('user_bankcard/secure', 'UserController@post3dSecure');

Route::middleware('auth:api')->group(function () {
    Route::get('/user_bankcard/list', 'UserController@getUserBankCard');
	Route::post('/user_bankcard/create', 'UserController@createUserBankCard');
    Route::post('/user_bankcard/destroy', 'UserController@destroyUserBankCard');

    Route::post('/order/create', 'OrderController@createNewOrder');
    Route::get('/order/list', 'OrderController@orderList');
    Route::get('/order/{id?}', 'OrderController@orderById');
});


Route::post('auth/login', 'AuthController@login');
Route::post('auth/register', 'AuthController@register');
Route::get('verify/phone', 'AuthController@verifyPhone');

// Verify email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Resend link to verify email
Route::post('/email/verify/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

//main getters for mobile app
Route::get('banners', 'BannerController@getBanners');
Route::get('place', 'IndexController@getPlaces');
Route::get('menu/{placeId}', 'IndexController@getMenu');
Route::get('table/menu/{code}', 'IndexController@getMenuByQr');
Route::get('categories/{menuId}', 'IndexController@getCategories');

Route::get('table', 'IndexController@getTables');
Route::get('search', 'IndexController@search');

Route::get('migrate', function () {
	\Artisan::call('migrate');
});


Route::get('cache/clear', function () {
	\Artisan::call('cache:clear');
	\Artisan::call('config:clear');
	\Artisan::call('view:clear');
	\Artisan::call('route:clear');

	return 'ok';
});
