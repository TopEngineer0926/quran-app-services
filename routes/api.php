<?php

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

Route::get('/chapters', 'APIController@chapters')->name('api.chapters');
Route::get('/chapters/{id}/info', 'APIController@chapter_info')->name('api.chapters');
Route::get('/chapter/{id}', 'APIController@chapter')->name('api.chapter');
Route::get('/chapters/{id}/verses', 'APIController@verses')->name('api.verses');
Route::get('/options/languages', 'APIController@languages')->name('api.languages');
Route::get('/chapters/{id}/verses/{verse_id}/audio_file', 'APIController@audio_file')->name('api.audio_file');
Route::get('/chapters/{id}/verses/audio_files', 'APIController@audio_files')->name('api.audio_files');
Route::get('/options/recitations', 'APIController@recitations')->name('api.recitations');
Route::get('/options/translations', 'APIController@translations')->name('api.translations');
Route::get('/search', 'APIController@search')->name('api.search');
Route::get('/suggest', 'APIController@suggest')->name('api.suggest');
