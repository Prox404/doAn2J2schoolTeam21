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

Route::get('dashboard', [AuthController::class, 'dashboard']); 
Route::get('login', [AuthController::class, 'index'])->name('login')->middleware('isLogin');
Route::post('custom-login', [AuthController::class, 'customLogin'])->name('login.custom'); 
Route::get('registration', [AuthController::class, 'registration'])->name('register-user')->middleware('isLogin');
Route::post('custom-registration', [AuthController::class, 'customRegistration'])->name('register.custom'); 
Route::get('signout', [AuthController::class, 'signOut'])->name('signout');

Route::group([
    'prefix'=>'users', 
    'as' => 'user.',
    'middleware' => 'requiredLogin'
], function (){
    Route::get('/', [\App\Http\Controllers\UsersController::class,'index'])->name('index');
    Route::get('api', [\App\Http\Controllers\UsersController::class,'api'])->name('api');
    Route::get('edit/{user}', [\App\Http\Controllers\UsersController::class,'edit'])->name('edit');
    Route::put('update/{user}', [\App\Http\Controllers\UsersController::class,'update'])->name('update');
    Route::delete('/destroy/{user}', [\App\Http\Controllers\UsersController::class,'destroy'])->name('destroy');
    Route::post('import/user', [\App\Http\Controllers\UsersController::class,'import'])->name('import');
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
    Route::get('api', [\App\Http\Controllers\ClassesController::class,'api'])->name('api');
    Route::get('userApi/{id}', [\App\Http\Controllers\ClassesController::class,'userApi'])->name('userApi');
    Route::get('edit/{classes}', [\App\Http\Controllers\ClassesController::class,'edit'])->name('edit');
    Route::get('autoSchedule/{id}', [\App\Http\Controllers\ClassesController::class,'autoSchedule'])->name('autoSchedule');
    Route::put('update', [\App\Http\Controllers\ClassesController::class,'update'])->name('update');
    Route::delete('/destroy/{classes}', [\App\Http\Controllers\ClassesController::class,'destroy'])->name('destroy');
    Route::post('/create', [\App\Http\Controllers\ClassesController::class,'store'])->name('store');
});

Route::group(['prefix'=>'attendance', 'as' => 'attendance.'], function (){
    Route::get('/', [\App\Http\Controllers\AttendanceController::class,'index'])->name('index');
    Route::get('api', [\App\Http\Controllers\AttendanceController::class,'api'])->name('api');
    Route::get('userApi/{id}', [\App\Http\Controllers\AttendanceController::class,'userApi'])->name('userApi');
    Route::get('edit/{class}', [\App\Http\Controllers\AttendanceController::class,'edit'])->name('edit');
    Route::get('edit/{class}', [\App\Http\Controllers\AttendanceController::class,'history'])->name('history');
    Route::put('update', [\App\Http\Controllers\AttendanceController::class,'update'])->name('update');
    Route::delete('/destroy/{classes}', [\App\Http\Controllers\AttendanceController::class,'destroy'])->name('destroy');
    Route::post('/create', [\App\Http\Controllers\AttendanceController::class,'store'])->name('store');
});

Route::group(['prefix'=>'schedules', 'as' => 'schedule.'], function (){
    Route::get('/', [\App\Http\Controllers\SchedulesController::class,'index'])->name('index');
});

Route::group(['prefix'=>'classStudent', 'as' => 'classStudent.'], function (){
    Route::post('import/classStudent', [\App\Http\Controllers\ClassStudentController::class,'import'])->name('import');
    Route::delete('/destroy/{id}', [\App\Http\Controllers\ClassStudentController::class,'destroy'])->name('destroy');
});
