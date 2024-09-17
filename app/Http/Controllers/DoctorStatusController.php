<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\doctorStatus;

class DoctorStatusController extends Controller
{
    public function index() {
        $doctors = doctorStatus::all();

        $doctors1 = $doctors->slice(0, 5);
        $doctors2 = $doctors->slice(5, 5);

        return view('dashboard', compact('doctors1', 'doctors2'));
    }
}
