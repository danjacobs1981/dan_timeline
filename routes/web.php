<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers'], function()
{   

    // home
    Route::get('/', 'HomeController@show')->name('home.show');

    // test area
    //Route::resource('events', 'TestController');

    Route::group(['middleware' => ['guest']], function() {

        // registration
        Route::get('/register', 'Auth\RegisterController@show')->name('register.show');
        Route::get('/register/modal', 'Auth\RegisterController@showModal')->name('register.showModal');
        Route::post('/register', 'Auth\RegisterController@register')->name('register.perform');

        // logging in
        Route::get('/login', 'Auth\LoginController@show')->name('login.show');
        Route::get('/login/modal', 'Auth\LoginController@showModal')->name('login.showModal');
        Route::post('/login', 'Auth\LoginController@login')->name('login.perform');

    });

    // only authenticated
    Route::group(['middleware' => ['auth']], function() {

        // email verification
        Route::get('/verify', 'Auth\VerificationController@show')->name('verification.notice');
        Route::get('/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify')->middleware(['signed']);
        Route::post('/resend', 'Auth\VerificationController@resend')->name('verification.resend');

        Route::get('/logout', 'Auth\LogoutController@perform')->name('logout.perform');

        // only verified account
        Route::group(['middleware' => ['verified']], function() {

            Route::get('/dashboard', 'Portal\DashboardController@index')->name('dashboard.show');

            // crud of timelines
            Route::resource('timelines', Portal\TimelineController::class);

            // timeline editing modals
            Route::get('/timelines/{timeline}/privacy', 'Portal\TimelineEditController@showModalPrivacy')->name('timelines.privacy.showModal');
            Route::get('/timelines/{timeline}/privacy/share', 'Portal\TimelineEditController@showModalPrivacyShare')->name('timelines.privacy-share.showModal');
            Route::get('/timelines/{timeline}/delete', 'Portal\TimelineEditController@showModalDelete')->name('timelines.delete.showModal');
            
            // ajax routes for timeline actions (background saving)
            Route::put('/timelines/{timeline}/settings', 'Portal\TimelineEditController@settings');
            Route::put('/timelines/{timeline}/privacy', 'Portal\TimelineEditController@privacy');
            Route::put('/timelines/{timeline}/privacy/share', 'Portal\TimelineEditController@privacyShare');

             // timeline events reordering
            Route::put('/timelines/{timeline}/reorder', 'Portal\TimelineEditController@reorderEvents');

            // crud of events
            Route::resource('timelines.events', Portal\TimelineEventController::class);

            // quick change event date & location
            Route::get('/timelines/{timeline}/events/{event}/edit/date', 'Portal\TimelineEventEditController@showModalDate')->name('timelines.events.edit.date');
            Route::get('/timelines/{timeline}/events/{event}/edit/location', 'Portal\TimelineEventEditController@showModalLocation')->name('timelines.events.edit.location');

            // event editing modals
            Route::get('/timelines/{timeline}/events/{event}/delete', 'Portal\TimelineEventEditController@showModalDelete')->name('timelines.events.delete.showModal');

            // ajax routes for event actions (background saving)
            Route::put('/timelines/{timeline}/events/{event}/delete', 'Portal\TimelineEventEditController@delete');
            
            // crud of profile
            Route::singleton('profile', Portal\ProfileController::class);
            
        });

    });

    // profile
    Route::get('/profile/{username}', 'ProfileController@show')->name('profile.show');

    // timeline
    Route::get('/{timeline}/{slug?}', 'TimelineController@show')->name('timeline.show');

    // ajax routes for timeline content
    Route::get('/timeline/{timeline}/events', 'TimelineController@events')->name('timeline.events.ajax');
    Route::get('/timeline/{timeline}/tags', 'TimelineController@tags')->name('timeline.tags.ajax');
    Route::get('/timeline/{timeline}/comments/{event?}', 'TimelineController@comments')->name('timeline.comments.ajax');

});