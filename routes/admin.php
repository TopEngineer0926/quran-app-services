<?php

/*-------------------------------Admin Routes------------------------------------*/
/////////////////////////////Routes for Admin/////////////////////////////////////////////////////////////
/////////////////////////////View Routes////////////////////////////////////////////////////////////////
Route::get('/', 'AdminLoginController@showLoginForm')->name('admin.default');
Route::get('/login','AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/login','AdminLoginController@login')->name('admin.login');
Route::post('/logout','AdminLoginController@logout')->name('admin.logout');
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Password Reset Routes...
Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset', 'ResetPasswordController@showResetForm')->name('password.reset.token');
Route::post('password/update', 'ResetPasswordController@reset')->name('password.update');
Route::get('password/redirector', 'ResetPasswordController@redirector')->name('password.redirector');
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/dashboard', 'AdminController@index')->name('admin.dashboard');

Route::resource('profile', 'ProfileController')->only([
    'index', 'update'
]);
Route::resource('vbv', 'VBVController')->except([
    'edit','update'
]);
Route::resource('wbw', 'WBWController')->except([
    'edit','update'
]);
Route::get('/vbv/activate/{id}', 'VBVController@activate')->name('vbv.activate');
Route::get('/vbv/deactivate/{id}', 'VBVController@deactivate')->name('vbv.deactivate');
/*---------------------------------------------------------------------------------------------*/
