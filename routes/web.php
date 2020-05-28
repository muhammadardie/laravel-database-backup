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
		Route::put('/user/changePassword/{id}', 'UserManagement\UserController@changePassword')->name('user.changePassword');
		Route::put('/user/changeProfile/{id}', 'UserManagement\UserController@changeProfile')->name('user.changeProfile');
		Route::resource('user', 'UserManagement\UserController', ['names' => 'user']);
	});

	/* Filesystem */
	Route::prefix('filesystem')->group(function () {
		Route::get('/disk/ajaxDatatable', 'Filesystem\DiskController@ajaxDatatable')->name('disk.ajaxDatatable');
		Route::resource('disk', 'Filesystem\DiskController', ['names' => 'disk']);
	});

	/* Database */
	Route::prefix('database')->group(function () {
		Route::get('/source/ajaxDatatable', 'Database\SourceController@ajaxDatatable')->name('source.ajaxDatatable');
		Route::resource('source', 'Database\SourceController', ['names' => 'source']);

		Route::get('/backup', 'Database\BackupController@index')->name('backup.index');
		Route::get('/backup/ajaxDatatable', 'Database\BackupController@ajaxDatatable')->name('backup.ajaxDatatable');
		Route::get('/backup/getDatabaseList', 'Database\BackupController@getDatabaseList')->name('backup.getDatabaseList');
		Route::get('/backup/show/{id}', 'Database\BackupController@show')->name('backup.show');
		Route::get('/backup/download/{id}', 'Database\BackupController@download')->name('backup.download');
		Route::delete('/backup/destroy/{id}', 'Database\BackupController@destroy')->name('backup.destroy');
		Route::post('/backup/createBackup', 'Database\BackupController@createBackup')->name('backup.createBackup');
	});

	
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
