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

Auth::routes();

Route::group(['middleware' => ['auth', 'web']], function () {
	Route::get('/', 'HomeController@index')->name('home');

	/* user management */
	Route::prefix('user-management')->group(function () {
		Route::get('/user/ajaxDatatable', 'UserManagement\UserController@ajaxDatatable')->name('user.ajaxDatatable');
		Route::resource('user', 'UserManagement\UserController', ['names' => 'user']);
	});

	/* Filesystem */
	Route::prefix('filesystem')->group(function () {
		Route::get('/disk/ajaxDatatable', 'Filesystem\DiskController@ajaxDatatable')->name('disk.ajaxDatatable');
		Route::resource('disk', 'Filesystem\DiskController', ['names' => 'disk']);

		Route::get('/file-manager', 'Filesystem\FileManagerController@index')->name('file-manager.index');
	});

	/* Database */
	Route::prefix('database')->group(function () {
		Route::get('/source/ajaxDatatable', 'Database\SourceController@ajaxDatatable')->name('source.ajaxDatatable');
		Route::resource('source', 'Database\SourceController', ['names' => 'source']);

		Route::get('/backup/ajaxDatatable', 'Database\SourceController@ajaxDatatable')->name('backup.ajaxDatatable');
		Route::resource('backup', 'Database\SourceController', ['names' => 'backup']);
	});

	
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
