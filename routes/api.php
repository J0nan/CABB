<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AuthController;

Route::match(['get', 'post'], 'unauthorized', function () {
    return response()->json([
        'error' => 'Unauthorized.'
    ], 401);
})->name('auth.unauthorized');

// Authentication
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    /* ------------------------ For Personal Access Token ----------------------- */
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    /* -------------------------------------------------------------------------- */
    /* ------------------------ For Password Grant Token ------------------------ */
        Route::post('login_grant', [AuthController::class, 'loginGrant'])->name('auth.loginGrant');
        Route::post('refresh', [AuthController::class, 'refreshToken'])->name('auth.refreshToken');
    /* -------------------------------------------------------------------------- */
    /* ------------------------------ Authenticated ----------------------------- */ 
    Route::group(['middleware' => 'auth:api'], function () {
        // User
            Route::get('user', [AuthController::class, 'getUser'])->name('auth.user');
            Route::get('user/files', [FileController::class, 'getUserFiles'])->name('file.getUserFiles');
            //LogOut
            Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
            //File
            Route::post('file', [FileController::class, 'uploadFile'])->name('file.uploadFile');
            Route::post('file/estimateGas', [FileController::class, 'estimateGasUpload'])->name('file.estimateGasUpload');
            Route::post('file/coOwner', [FileController::class, 'setCoOwner'])->name('file.setCoOwner');
            Route::post('file/owner', [FileController::class, 'setNewOwner'])->name('file.setNewOwner');
    });
// File
    Route::post('file/verify', [FileController::class, 'verifyFile'])->name('file.verifyFile');
    Route::post('file/verify/currentOwners', [FileController::class, 'getCurrentOwners'])->name('file.getCurrentOwners');
    Route::post('file/verify/oldOwners', [FileController::class, 'getOldOwners'])->name('file.getOldOwners');
    Route::get('file/{file}', [FileController::class, 'getFile'])->name('file.getFile');

//Fallback
Route::any('{segment}', function () {
    return response()->json([
        'error' => 'Invalid url.'
    ], 404);
})->where('segment', '.*');
