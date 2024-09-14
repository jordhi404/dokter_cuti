<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoctorStatusController extends Controller
{
    public function index() {
        return view('dashboard');
    }
}
