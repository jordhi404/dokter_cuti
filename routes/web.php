<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorStatusController;

Route::get('/', [DoctorStatusController::class, 'index'])->name('index');
