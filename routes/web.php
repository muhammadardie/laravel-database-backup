<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', function() {
    return redirect('login');
});

Route::group(['middleware' => ['auth', 'web']], function () {
	Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

	/* user management */
	Route::prefix('user-management')->group(function () {
		Route::get('/user/ajaxDatatable', [App\Http\Controllers\UserManagement\UserController::class, 'ajaxDatatable'])->name('user.ajaxDatatable');
		Route::put('/user/changePassword/{id}', [App\Http\Controllers\UserManagement\UserManagement\UserController::class, 'changePassword'])->name('user.changePassword');
		Route::put('/user/changeProfile/{id}', [App\Http\Controllers\UserManagement\UserManagement\UserController::class, 'changeProfile'])->name('user.changeProfile');
		Route::resource('user', 'App\Http\Controllers\UserManagement\UserController', ['names' => 'user']);
	});

	/* Master Data */
	Route::prefix('master-data')->group(function () {
		Route::get('/storage/ajaxDatatable', [App\Http\Controllers\MasterData\StorageController::class, 'ajaxDatatable'])->name('storage.ajaxDatatable');
		Route::resource('storage', 'App\Http\Controllers\MasterData\StorageController', ['names' => 'storage']);

		Route::get('/database-source/ajaxDatatable', [App\Http\Controllers\MasterData\DatabaseSourceController::class, 'ajaxDatatable'])->name('database-source.ajaxDatatable');
		Route::resource('database-source', 'App\Http\Controllers\MasterData\DatabaseSourceController', ['names' => 'database-source']);
	});

	/* Database */
	Route::prefix('database')->group(function () {
		Route::get('/histories/ajaxDatatable', [App\Http\Controllers\Database\BackupHistoryController::class, 'ajaxDatatable'])->name('histories.ajaxDatatable');
		Route::get('/histories/getDatabaseList', [App\Http\Controllers\Database\BackupHistoryController::class, 'getDatabaseList'])->name('histories.getDatabaseList');
		Route::get('/histories/download/{id}', [App\Http\Controllers\Database\BackupHistoryController::class, 'download'])->name('histories.download');
		Route::resource('histories', 'App\Http\Controllers\Database\BackupHistoryController', ['names' => 'histories']);

		Route::get('/scheduler/ajaxDatatable', [App\Http\Controllers\Database\SchedulerController::class, 'ajaxDatatable'])->name('scheduler.ajaxDatatable');
		Route::get('/scheduler/getDatabaseList', [App\Http\Controllers\Database\SchedulerController::class, 'getDatabaseList'])->name('scheduler.getDatabaseList');
		Route::resource('scheduler', 'App\Http\Controllers\Database\SchedulerController', ['names' => 'scheduler']);
	});

	
});