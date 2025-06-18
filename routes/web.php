<?php

use App\Http\Controllers\TestEmailController;

use Illuminate\Support\Facades\Route;

Route::get('/admin/test-email', [TestEmailController::class, 'sendTestEmail']);

Route::get('/', function () {
    return redirect('/admin');
});