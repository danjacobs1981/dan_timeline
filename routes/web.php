<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('home');
});*/

Route::group(['namespace' => 'App\Http\Controllers'], function()
{   

    Route::get('/', 'HomeController@index')->name('home.index');

    // test area
    Route::resource('events', 'TestController');

    Route::group(['middleware' => ['guest']], function() {

        Route::get('/register', 'RegisterController@show')->name('register.show');
        Route::get('/register/modal', 'RegisterController@showModal')->name('register.showModal');
        Route::post('/register', 'RegisterController@register')->name('register.perform');

        Route::get('/login', 'LoginController@show')->name('login.show');
        Route::get('/login/modal', 'LoginController@showModal')->name('login.showModal');
        Route::post('/login', 'LoginController@login')->name('login.perform');

    });

    Route::group(['middleware' => ['auth']], function() {

        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');

    });

    Route::get('/{timeline}/{slug?}', 'TimelineController@show')->name('timeline.show');
    
    Route::post('/ajax/timeline/events/{timeline}', 'TimelineController@events')->name('timeline.events.ajax');
    Route::get('/ajax/timeline/tags/{timeline}', 'TimelineController@tags')->name('timeline.tags.ajax');
    Route::get('/ajax/timeline/comments/{timeline}/{event?}', 'TimelineController@comments')->name('timeline.comments.ajax');

});