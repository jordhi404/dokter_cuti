<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\doctorStatus;
use App\Models\doctor;

class DoctorStatusController extends Controller
{
    public function index() {
        $doctors = doctorStatus::whereDate('tanggal', '=', now())
                ->whereHas('doctor')
                ->with('doctor')
                ->orderBy('kddokter')
                ->get()
                ->map(function ($status) {
                    return [
                        'kddokter' => $status->kddokter,
                        'nama' => $status->doctor ? $status->doctor->nama : 'Tidak diketahui',
                        'tipe_poli' => $status->tipe_poli,
                        'status' => $status->qmax == 0 ? 'Cuti' : 'On Duty',
                    ];
                });
                
        return view('dashboard', ['doctors' => $doctors]);
    }
}
