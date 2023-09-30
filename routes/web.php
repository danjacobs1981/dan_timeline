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

    // home
    Route::get('/', 'HomeController@show')->name('home.show');

    // test area
    Route::resource('events', 'TestController');

    Route::group(['middleware' => ['guest']], function() {

        Route::get('/register', 'Auth\RegisterController@show')->name('register.show');
        Route::get('/register/modal', 'Auth\RegisterController@showModal')->name('register.showModal');
        Route::post('/register', 'Auth\RegisterController@register')->name('register.perform');

        Route::get('/login', 'Auth\LoginController@show')->name('login.show');
        Route::get('/login/modal', 'Auth\LoginController@showModal')->name('login.showModal');
        Route::post('/login', 'Auth\LoginController@login')->name('login.perform');

    });

    // only authenticated
    Route::group(['middleware' => ['auth']], function() {

        Route::get('/verify', 'Auth\VerificationController@show')->name('verification.notice');
        Route::get('/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify')->middleware(['signed']);
        Route::post('/resend', 'Auth\VerificationController@resend')->name('verification.resend');

        Route::get('/logout', 'Auth\LogoutController@perform')->name('logout.perform');

        // only verified account
        Route::group(['middleware' => ['verified']], function() {

            Route::get('/dashboard', 'Portal\DashboardController@index')->name('dashboard.show');

            // crud of timelines
            Route::resource('timelines', Portal\TimelineController::class);

            // timeline editing
            Route::get('/timelines/{timeline}/privacy/modal', 'Portal\TimelineEditController@showModalPrivacy')->name('timeline.privacy.showModal');
            Route::get('/timelines/{timeline}/privacy/share/modal', 'Portal\TimelineEditController@showModalPrivacyShare')->name('timeline.privacy-share.showModal');
            
            // ajax routes for editing
            Route::put('/timelines/{timeline}/settings', 'Portal\TimelineEditController@settings');
            Route::put('/timelines/{timeline}/privacy', 'Portal\TimelineEditController@privacy');
            Route::put('/timelines/{timeline}/privacy/share', 'Portal\TimelineEditController@privacyShare');

            Route::resource('timelines.events', Portal\TimelineEventController::class);
            
            // crud of profile
            Route::singleton('profile', Portal\ProfileController::class);
            
        });

    });

    // profile
    Route::get('/profile/{username}', 'ProfileController@show')->name('profile.show');

    // timeline
    Route::get('/{timeline}/{slug?}', 'TimelineController@show')->name('timeline.show');

    // ajax
    Route::post('/ajax/timeline/events/{timeline}', 'TimelineController@events')->name('timeline.events.ajax');
    Route::get('/ajax/timeline/tags/{timeline}', 'TimelineController@tags')->name('timeline.tags.ajax');
    Route::get('/ajax/timeline/comments/{timeline}/{event?}', 'TimelineController@comments')->name('timeline.comments.ajax');

});