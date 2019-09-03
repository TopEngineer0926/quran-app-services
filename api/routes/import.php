<?php

/*
|--------------------------------------------------------------------------
| Import Routes
|--------------------------------------------------------------------------
|
| Routes to import data from quran.com api
|
*/
Route::get('/all', 'ImportController@all')->name('import.all');
Route::get('/languages', 'ImportController@languages')->name('import.languages');
Route::get('/chapters', 'ImportController@chapters')->name('import.chapters');
Route::get('/chapters-info', 'ImportController@chapter_info')->name('import.chapter_info');
Route::get('/juzs', 'ImportController@juzs')->name('import.juzs');
Route::get('/index-wbw', 'ImportController@wbw_index_generator')->name('import.wbw_index_generator');
Route::get('/verses/{truncate}', 'ImportController@verses')->name('import.verses');
Route::get('/create-wbw-indexes/{truncate}', 'ImportController@create_wbw_index')->name('import.create_wbw_index');
Route::get('/update-pages', 'ImportController@update_pages')->name('import.update_pages');
Route::get('/test-xml', 'ImportController@testxml')->name('import.testxml');
Route::get('/get-xml', 'ImportController@getxml')->name('import.getxml');
Route::get('/audio-files', 'ImportController@audiofiles')->name('import.audiofiles');
Route::get('/update-chapters', 'ImportController@update_chapters')->name('import.update_chapters');
Route::get('/chapter-info-description', 'ImportController@chapter_info_description')->name('import.chapter_info_description');
Route::get('/options-translations', 'ImportController@options_translations')->name('import.options_translations');
Route::get('/translations', 'ImportController@translations')->name('import.translations');
