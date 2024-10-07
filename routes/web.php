<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegulerDoctorStatusController;
use App\Http\Controllers\Non_regulerDoctorStatusController;
use App\Http\Controllers\theDoctorStatusController;
use App\Http\Controllers\offDatesController;

Route::get('/reguler', [RegulerDoctorStatusController::class, 'regulerIndex'])->name('regulerIndex');
Route::get('/non-reguler', [Non_regulerDoctorStatusController::class, 'nonRegulerIndex'])->name('nonRegulerIndex');

Route::get('/', [theDoctorStatusController::class, 'index'])->name('index');

Route::get('/offdates', [offDatesController::class, 'processDoctorOffDates'])->name('offDatesIndex');