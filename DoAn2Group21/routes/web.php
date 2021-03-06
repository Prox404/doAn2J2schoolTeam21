<?php

use App\Http\Controllers\AuthController;
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

Route::get('/', function () {
    return view('home');
})->middleware('requiredLogin');

Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard'); 
Route::get('login', [AuthController::class, 'index'])->name('login')->middleware('isLogin');
Route::post('custom-login', [AuthController::class, 'customLogin'])->name('login.custom'); 
Route::get('registration', [AuthController::class, 'registration'])->name('register-user')->middleware('isLogin');
Route::post('custom-registration', [AuthController::class, 'customRegistration'])->name('register.custom'); 
Route::get('signout', [AuthController::class, 'signOut'])->name('signout');

Route::group([
    'prefix'=>'users', 
    'as' => 'user.',
    // 'middleware' => 'requiredLogin'
], function (){
    Route::get('/', [\App\Http\Controllers\UsersController::class,'index'])->name('index');
    Route::get('api', [\App\Http\Controllers\UsersController::class,'api'])->name('api');
    Route::get('edit/{user}', [\App\Http\Controllers\UsersController::class,'edit'])->name('edit');
    Route::put('update/{user}', [\App\Http\Controllers\UsersController::class,'update'])->name('update');
    Route::delete('/destroy/{user}', [\App\Http\Controllers\UsersController::class,'destroy'])->name('destroy');
    Route::post('import/user', [\App\Http\Controllers\UsersController::class,'import'])->name('import');
    Route::post('advancedImport/', [\App\Http\Controllers\UsersController::class,'advancedImport'])->name('advancedImport');
});

Route::group(['prefix'=>'subjects', 'as' => 'subject.'], function (){
    Route::get('/', [\App\Http\Controllers\SubjectsController::class,'index'])->name('index');
    Route::get('api', [\App\Http\Controllers\SubjectsController::class,'api'])->name('api');
    Route::get('edit/{subject}', [\App\Http\Controllers\SubjectsController::class,'edit'])->name('edit');
    Route::put('update/{subject}', [\App\Http\Controllers\SubjectsController::class,'update'])->name('update');
    Route::delete('/destroy/{subject}', [\App\Http\Controllers\SubjectsController::class,'destroy'])->name('destroy');
    Route::post('/create', [\App\Http\Controllers\SubjectsController::class,'store'])->name('store');
});

Route::group(['prefix'=>'classes', 'as' => 'class.'], function (){
    Route::get('/', [\App\Http\Controllers\ClassesController::class,'index'])->name('index');
    Route::get('test', [\App\Http\Controllers\ClassesController::class,'test'])->name('test');
    Route::get('api', [\App\Http\Controllers\ClassesController::class,'api'])->name('api');
    Route::get('userApi/{id}', [\App\Http\Controllers\ClassesController::class,'userApi'])->name('userApi');
    Route::get('edit/{classes}', [\App\Http\Controllers\ClassesController::class,'edit'])->name('edit');
    Route::get('autoSchedule/{id}', [\App\Http\Controllers\ClassesController::class,'autoSchedule'])->name('autoSchedule');
    Route::put('update/{class}', [\App\Http\Controllers\ClassesController::class,'update'])->name('update');
    Route::delete('/destroy/{classes}', [\App\Http\Controllers\ClassesController::class,'destroy'])->name('destroy');
    Route::get('/addTeacher/{classes}', [\App\Http\Controllers\ClassesController::class,'addTeacher'])->name('addTeacher');
    Route::put('storeTeacher', [\App\Http\Controllers\ClassesController::class,'storeTeacher'])->name('storeTeacher');
    Route::post('/create', [\App\Http\Controllers\ClassesController::class,'store'])->name('store');
});

Route::group(['prefix'=>'attendance', 'as' => 'attendance.'], function (){
    Route::get('/', [\App\Http\Controllers\AttendanceController::class,'index'])->name('index');
    Route::get('api', [\App\Http\Controllers\AttendanceController::class,'api'])->name('api');
    Route::get('history/{class}', [\App\Http\Controllers\AttendanceController::class,'history'])->name('history');
    Route::get('/history/{class_id}/{schedule_id}', [\App\Http\Controllers\AttendanceController::class,'attendance'])->name('attendance');
    Route::put('store', [\App\Http\Controllers\AttendanceController::class,'store'])->name('store');
    Route::delete('/destroy/{classes}', [\App\Http\Controllers\AttendanceController::class,'destroy'])->name('destroy');

});

Route::group(['prefix'=>'schedules', 'as' => 'schedule.'], function (){
    Route::get('/', [\App\Http\Controllers\SchedulesController::class,'index'])->name('index');
    Route::get('classApi', [\App\Http\Controllers\SchedulesController::class,'classApi'])->name('classApi');
    Route::get('edit/{class}', [\App\Http\Controllers\SchedulesController::class,'edit'])->name('edit');
    Route::get('edit/getSchedule/{schedule}', [\App\Http\Controllers\SchedulesController::class,'getSchedule'])->name('getSchedule');
    Route::delete('destroy/{schedule}', [\App\Http\Controllers\SchedulesController::class,'destroy'])->name('destroy');
    Route::put('update', [\App\Http\Controllers\SchedulesController::class,'update'])->name('update');
});

Route::group(['prefix'=>'classStudent', 'as' => 'classStudent.'], function (){
    Route::post('import/classStudent', [\App\Http\Controllers\ClassStudentController::class,'import'])->name('import');
    Route::delete('/destroy/{id}', [\App\Http\Controllers\ClassStudentController::class,'destroy'])->name('destroy');
});
