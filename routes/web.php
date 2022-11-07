<?php

Route::namespace('Nue\SSOSamarinda\Http\Controllers')->middleware('web')->group(function() {

	Route::prefix('oauth/sso')->group(function() {
		Route::get('authorize', 'OAuthController@login')->name('sso.authorize');
		Route::get('callback', 'OAuthController@callback');

		Route::get('logout', 'OAuthController@logout')->name('sso.logout');
	});

	Route::prefix('ext')->group(function() {
		Route::resource('sso-users', 'UserController');
	});
});