<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\AppointmentController::class, 'new'])->name('appointments.new');
Route::post('/nueva-cita', [\App\Http\Controllers\AppointmentController::class, 'create'])->name('appointments.create');
Route::get('/ajax-check-id-card', [\App\Http\Controllers\AppointmentController::class, 'ajaxCheckIdCard'])->name('ajax-check-id-card');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/auth.php';
