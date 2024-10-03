<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegulerDoctorStatusController;
use App\Http\Controllers\Non_regulerDoctorStatusController;

Route::get('/reguler', [RegulerDoctorStatusController::class, 'index'])->name('index');
Route::get('/non-reguler', [Non_regulerDoctorStatusController::class, 'index'])->name('index');