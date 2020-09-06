<?php

use App\Role;
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




Auth::routes();



Route::get('/dashboard/{path}','DashboardController@index')->where( 'path', '([A-z\/_.\d-]+)?' );


// middleware auth in the controller __construct method

Route::resource('categories','CategoryController')->except('create','edit');
Route::resource('roles','RoleController')->except(['edit','create']);
Route::resource('users','UserController')->except(['edit','create']);
Route::resource('actors','ActorController')->except(['edit','create']);
Route::resource('movies','MovieController')->except(['edit','create','index']);


Route::post('/comments/{movie}','CommentController@store');
Route::put('/comments/{comment}','CommentController@update');
Route::delete('/comments/{comment}','CommentController@destroy');

//
Route::middleware('auth')->group(function(){

    Route::post('/actors/{actor}/photo','ActorController@uploadPhoto');
    Route::post('/movies/{movie}/poster','MovieController@updatePoster');
    Route::post('/movies/{entityId}/vote','VoteController@store');
    Route::delete('/movies/{entityId}/vote','VoteController@destroy');

    Route::post('/movies/{movie}/cast/{actor}','ActorMovieController@store');
    Route::post('/movies/{movie}/favourite','MovieController@addFavourite');

    Route::get('/favourites','MovieController@showFavourites');

    Route::put('/profiles/{user}','ProfileController@update')->name('profiles.update');
    Route::put('/profiles/{user}/changePassword','ProfileController@updatePassword')->name('profiles.updatePassword');
    Route::get('/profiles/{user}/edit','ProfileController@edit')->name('profiles.edit');

});



// no authentication


Route::get('/comments/{movie}','CommentController@index');
Route::get('/comments/{comment}/replies','CommentController@show');
Route::get('/abilities','AbilityController@index');
Route::post('/abilities','AbilityController@store');
Route::get('/','MovieController@index')->name('home');
Route::put('/movies/{movie}/views','MovieController@updateViews');
