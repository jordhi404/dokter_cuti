<?php

namespace App\Http\Controllers;

use App\Models\doctorStatus;

class DoctorStatusController extends Controller
{
    public function index() {
        $doctors = doctorStatus::whereDate('tanggal', '=', now())
                ->where('tipe_poli', '<>', 'EXECUTIVE')
                ->whereNotIn('kddokter', [
                        '02015', 'DT01', 'DUAG1', 'DUAG2', 'DUAG3', 'DUKIM', 'DUKOS', 
                        'DUPC2', 'DUPS1', 'DUPS3', 'PS001', 'DUPU1', 'DUPU2', 'DUKIA'
                    ])
                ->whereHas('doctor')
                ->with('doctor')
                ->orderBy('kddokter')
                ->get()
                ->groupBy('kddokter')
                ->map(function ($status) {
                    return [
                        'kode' => $status->first()->kddokter,
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
        //dd($doctors);
        return view('dashboard', ['doctors' => $doctors]);
    }
}
