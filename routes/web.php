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

Auth::routes();

Route::get('/admin', [App\Http\Controllers\HomeController::class, 'index'])->name('admin');


Route::get('welcome-admin', function() {
    return view('admin');
})->middleware('role:admin')->name('admin.page');

Route::get('user-page', function() {
    return view('user');
})->middleware('role:user')->name('user.page');

Route::post('/register', [VerificationController::class, 'register']);
Route::get('/verify/{id}', [VerificationController::class, 'verify']);
Route::get('/home', [VerificationController::class, 'checkVerification'])->middleware('auth');