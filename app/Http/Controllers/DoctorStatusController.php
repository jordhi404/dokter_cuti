<?php

namespace App\Http\Controllers;

use App\Models\doctorStatus;

class DoctorStatusController extends Controller
{
    public function index() {
        $doctors = doctorStatus::whereDate('tanggal', '=', now())
                ->where('tipe_poli', '<>', 'EXECUTIVE')
                ->whereHas('doctor')
                ->with('doctor')
                ->orderBy('kddokter')
                ->get()
                ->map(function ($status) {
                    return [
                        'nama' => $status->doctor ? $status->doctor->nama : 'Tidak diketahui',
                        'tipe_poli' => $status->tipe_poli,
                        'status' => $status->qmax == 0 ? 'CUTI' : 'ON DUTY',
                    ];
                });
        // dd(now()->toDateString());
        return view('dashboard', ['doctors' => $doctors]);
    }
}
