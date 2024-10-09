<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\offDatesController;

Route::get('/', [offDatesController::class, 'processDoctorOffDates'])->name('offDatesIndex');