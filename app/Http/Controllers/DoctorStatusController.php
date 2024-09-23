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
                ->groupBy('kddokter')
                ->map(function ($status) {
                    return [
                        'nama' => $status->first()->doctor ? $status->first()->doctor->nama : 'Tidak diketahui',
                        'praktik' => $status->map(function($statuses) {
                            return [
                                'tipe_poli' => $statuses->tipe_poli,
                                'status' => $statuses->qmax == 0 ? 'CUTI' : 'ON DUTY',
                            ];
                        })
                    ];
                });
        // dd(now()->toDateString());
        return view('dashboard', ['doctors' => $doctors]);
    }
}
