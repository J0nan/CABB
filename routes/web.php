<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

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

//Upload
Route::get('/upload', function () {
    return view('uploadFile');
})->name('uploadFile');

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/coowner', function () {
    return view('addCoOwner');
})->name('coowner');

Route::get('/newowner', function () {
    return view('newOwner');
})->name('newowner');

Route::get('/user/files', function () {
    return view('myFiles');
})->name('myFiles');

Route::get('/upload/{file}/successful', [fileController::class, 'uploadFileSuccessful'])->name('uploadFileSuccessful');

Route::get('/verify', function () {
    return view("verifyFile");
})->name('verifyFile');