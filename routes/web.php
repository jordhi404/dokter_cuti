<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\offDatesController;

Route::get('/data/doctors', [offDatesController::class, 'processDoctorOffDates'])->name('offDatesData');

Route::get('/', [offDatesController::class, 'showDoctorOffDates'])->name('offDates');