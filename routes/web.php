<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DocumentFileController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->intended('/admin');
    }

    return redirect('/admin/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/documents/{document}/file', [DocumentFileController::class, 'show'])
        ->name('documents.files.show');
    Route::get('/documents/{document}/download', [DocumentFileController::class, 'download'])
        ->name('documents.files.download');
});
