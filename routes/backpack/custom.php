<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('city', 'CityCrudController');
    Route::crud('place', 'PlaceCrudController');
    Route::crud('menu', 'MenuCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('subcategory', 'SubcategoryCrudController');
    Route::crud('banner', 'BannerCrudController');
    Route::crud('product', 'ProductCrudController');
    Route::crud('order', 'OrderCrudController');
    Route::crud('userbankcard', 'UserBankCardCrudController');
    Route::crud('user', 'UserCrudController');
    Route::crud('table', 'TableCrudController');
    Route::crud('booking', 'BookingCrudController');
    Route::crud('bookinglist', 'BookingListCrudController');
}); // this should be the absolute last line of this file