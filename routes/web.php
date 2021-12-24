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

Route::get('/', function() {
    return redirect('login');
});

Route::group(['middleware' => ['auth', 'web']], function () {
	Route::get('/home', 'HomeController@index')->name('home');

	/* user management */
	Route::prefix('user-management')->group(function () {
		Route::get('/user/ajaxDatatable', 'UserManagement\UserController@ajaxDatatable')->name('user.ajaxDatatable');
		Route::put('/user/changePassword/{id}', 'UserManagement\UserController@changePassword')->name('user.changePassword');
		Route::put('/user/changeProfile/{id}', 'UserManagement\UserController@changeProfile')->name('user.changeProfile');
		Route::resource('user', 'UserManagement\UserController', ['names' => 'user']);
	});

	/* Master Data */
	Route::prefix('master-data')->group(function () {
		Route::get('/storage/ajaxDatatable', 'MasterData\StorageController@ajaxDatatable')->name('storage.ajaxDatatable');
		Route::resource('storage', 'MasterData\StorageController', ['names' => 'storage']);

		Route::get('/database-source/ajaxDatatable', 'MasterData\DatabaseSourceController@ajaxDatatable')->name('database-source.ajaxDatatable');
		Route::resource('database-source', 'MasterData\DatabaseSourceController', ['names' => 'database-source']);
	});

	/* Database */
	Route::prefix('database')->group(function () {
		Route::get('/histories/ajaxDatatable', 'Database\BackupHistoryController@ajaxDatatable')->name('histories.ajaxDatatable');
		Route::get('/histories/getDatabaseList', 'Database\BackupHistoryController@getDatabaseList')->name('histories.getDatabaseList');
		Route::get('/histories/download/{id}', 'Database\BackupHistoryController@download')->name('histories.download');
		Route::resource('histories', 'Database\BackupHistoryController', ['names' => 'histories']);

		Route::get('/scheduler/ajaxDatatable', 'Database\SchedulerController@ajaxDatatable')->name('scheduler.ajaxDatatable');
		Route::get('/scheduler/getDatabaseList', 'Database\SchedulerController@getDatabaseList')->name('scheduler.getDatabaseList');
		Route::resource('scheduler', 'Database\SchedulerController', ['names' => 'scheduler']);
	});

	
});
