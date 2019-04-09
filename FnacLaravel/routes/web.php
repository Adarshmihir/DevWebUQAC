<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::auth();
Route::get('/logout', 'Auth\\LoginController@getLogout')->name('logout');

Route::get('/', 'VideogameController@showHome')->name('home');
Route::get('/recherche', 'RechercheController@search')->name('search');

Route::group(['as' => 'videogame_', 'prefix' => 'videogame/'], function () {
    Route::get('/', 'VideogameController@showAll')->name('all');
    Route::get('/{id}-{slug}', 'VideogameController@showOne')->name('one');
});

Route::group(['as' => 'cart_', 'prefix' => 'cart/'],function(){
    Route::get('/', 'CartController@show')->name('show');
	Route::post('ajax/add', 'CartController@addToCart')->name('ajax_add');
	Route::post('ajax/delete', 'CartController@delete')->name('ajax_delete');
});

Route::group(['as' => 'order_','prefix'=>'order/'],function(){
    Route::get('',['as' => 'home','uses'=> 'OrderController@home']);
});

Route::get('profile',['as'=>'profile','uses'=>'ClientController@profile']);
Route::post('profile',['as'=>'profilePost','uses'=>'ClientController@profilePost']);

Route::group(['as' => 'comment_','prefix' => 'comment/'],function(){
    Route::get('signal/{id}',['as'=> 'signal','uses' => 'AvisController@signal']);
});

Route::post('thumb',['as' => 'changeThumb','uses'=>'AvisController@thumb']);

Route::post('favorite',['as' => 'favoriteAdd' ,'uses' => 'VideogameController@favoriteAdd']);

Route::group(['as' => 'admin::', 'prefix' => 'admin/', 'middleware' => ['auth', 'admin']], function () {
    Route::get('dashboard', 'AdminController@showDashboard')->name('dashboard');
    Route::get('',function(){
        return redirect()->route('admin::dashboard');
    });

    Route::group(['as' => 'videogame_', 'prefix' => 'videogame/'], function () {
        Route::get('/', 'VideogameController@adminShowAll')->name('all');
        Route::get('/new', 'VideogameController@adminShowNew')->name('new');
        Route::post('/new', 'VideogameController@adminShowNewPost')->name('newPost');
        Route::get('/{id}', 'VideogameController@adminShowOne')->where('id', '[0-9]+')->name('one');
        Route::get('/delete/{id}','VideogameController@adminDelete')->where('id', '[0-9]+')->name('delete');
    });

    Route::group(['as' => 'reviews_', 'prefix' => 'review/'], function () {
        Route::get('/', 'AvisController@adminShowAll')->name('all');
        Route::get('/waiting', 'AvisController@adminShowWaiting')->name('waiting');
        Route::get('/{id}', 'AvisController@adminShowOne')->where('id', '[0-9]+')->name('one');
        Route::delete('/{id}', 'AvisController@adminDeleteAvis')->where('id', '[0-9]+')->name('delete');
        Route::delete('/{id}-{cid}', 'AvisController@adminDeleteAbusif')->where('id', '[0-9]+')->where('cid', '[0-9]+')->name('delete-abusif');
    });

    Route::group(['as' => 'clients_', 'prefix' => 'client/'], function () {
        Route::get('/', 'ClientController@adminShowAll')->name('all');
        Route::get('/new', 'ClientController@adminShowNew')->name('new');
        Route::get('/{id}', 'ClientController@adminShowOne')->where('id', '[0-9]+')->name('one');
        Route::delete('/{id}', 'ClientController@adminDeleteClient')->where('id', '[0-9]+')->name('delete');
    });
});
