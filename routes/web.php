<?php

use Illuminate\Support\Facades\Route;


Route::get('/', 'MovieController@index')->name('movies.index');
Route::get('/movies/{movie}', 'MovieController@show')->name('movies.show');

Route::get('/popular-movies', 'MovieController@popularMovies')->name('movies.list');
Route::get('/popular-movies/page/{page}', 'MovieController@popularMovies');

Route::get('/now-playing', 'MovieController@nowplaying')->name('movies.now-playing');
Route::get('/now-playing/page/{page}', 'MovieController@nowplaying');

Route::get('/upcoming', 'MovieController@upcoming')->name('movies.upcoming');
Route::get('/upcoming/page/{page}', 'MovieController@upcoming');

Route::get('/top-rated', 'MovieController@topRated')->name('movies.top-rated');
Route::get('/top-rated/page/{page}', 'MovieController@topRated');


Route::get('/actors', 'PeopleController@index')->name('actors.index');
Route::get('/actors/page/{page?}', 'PeopleController@index');
Route::get('/actors/{actor}', 'PeopleController@show')->name('actors.show');

Route::get('/tv', 'TvController@index')->name('tv.index');
Route::get('/tv/page/{page?}', 'TvController@index');
Route::get('/tv/{id}', 'TvController@show')->name('tv.show');


Route::get('/airing-today', 'TvController@airingToday')->name('tv.airing-today');
Route::get('/airing-today/page/{page?}', 'TvController@airingToday');

Route::get('/on-tv', 'TvController@ontV')->name('tv.on-tV');
Route::get('/on-tv/page/{page?}', 'TvController@ontV');

Route::get('/top-rated-tv', 'TvController@topRated')->name('tv.top-rated');
Route::get('/top-rated-tv/page/{page?}', 'TvController@topRated');