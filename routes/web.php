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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'ExamController@index')->name('home');
Route::get('/data', 'ExamController@getData')->name('data');
Route::get('/exams', 'ExamController@exams')->name('exams');
Route::get('/attendance_check/{exam_id}', 'ExamController@attendance_check')->name('attendance_check');
Route::post('/attendance_submit', 'ExamController@attendance_submit')->name('attendance_submit');
Route::post('/add_exam_student', 'ExamController@add_exam_student')->name('add_exam_student');
Route::post('/get_signature_from_teacher', 'ExamController@get_signature_from_teacher')->name('get_signature_from_teacher');
Route::post('/get_signature_from_other', 'ExamController@get_signature_from_other')->name('get_signature_from_other');
Route::post('/get_signature', 'ExamController@get_signature')->name('get_signature');
Route::get('/report/{exam_id}', 'ExamController@report')->name('report');
