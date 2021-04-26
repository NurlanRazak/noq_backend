<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login', 'AuthController@login');
Route::get('verify/phone', 'AuthController@verifyPhone');

Route::get('banners', 'BannerController@getBanners');

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
