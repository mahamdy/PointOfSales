<?php



Route::group(
['prefix' => LaravelLocalization::setLocale(),'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]],function(){

Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function(){

	Route::get('/','WelcomeController@index')->name('welcome');

    //category route
    Route::resource('categories','CategoryController')->except(['show']);

    //product route
    Route::resource('products','ProductController')->except(['show']);

    //client route
    Route::resource('clients','ClientController')->except(['show']);
    Route::resource('clients.orders','Client\OrderController')->except(['show']);


    //order route
    Route::resource('orders','OrderController');
    Route::get('/orders/{order}/products','OrderController@products')->name('orders.products');

    //user route
	Route::resource('users','UserController')->except(['show']);



});//end of dashboard route


});

