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

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix'=>'users', 'as' => 'user.'], function (){
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
    Route::get('edit/{subject}', [\App\Http\Controllers\ClassesController::class,'edit'])->name('edit');
    Route::put('update/{subject}', [\App\Http\Controllers\ClassesController::class,'update'])->name('update');
    Route::delete('/destroy/{subject}', [\App\Http\Controllers\ClassesController::class,'destroy'])->name('destroy');
    // Route::post('/create', [\App\Http\Controllers\ClassesController::class,'store'])->name('store');
});

