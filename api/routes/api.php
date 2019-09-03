<?php

use Illuminate\Http\Request;

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
Route::get('/chapters', 'APIController@chapters')->name('api.chapters');
Route::get('/chapters/{id}/info', 'APIController@chapter_info')->name('api.chapters');
Route::get('/chapter/{id}', 'APIController@chapter')->name('api.chapter');
Route::get('/chapters/{id}/verses', 'APIController@verses')->name('api.verses');
Route::get('/options/languages', 'APIController@languages')->name('api.languages');
Route::get('/chapters/{id}/verses/{verse_id}/audio_files', 'APIController@audio_files')->name('api.audio_files');
