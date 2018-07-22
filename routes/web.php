<?php

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

Route::prefix('admin')->middleware('auth')->group(function () {

	Route::get('/', function () {
		return redirect('admin/punch_record');
	});
	Route::get('/punch_record/{member_no?}', 'PunchRecordController@index')->name('punch_record');
	Route::get('/punch_record_download/', 'PunchRecordController@punch_record_download')->name('punch_record_download');
	Route::get('/absence/', 'AbsenceController@absence')->name('absence');

});



Route::get('/get_punch_record/', 'ScraperController@get_punch_record')->name('get_punch_record');



Route::get('/', function () {
	return redirect('login');
});
