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
