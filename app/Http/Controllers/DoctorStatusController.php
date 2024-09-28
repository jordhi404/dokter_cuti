<?php

namespace App\Http\Controllers;

use App\Models\doctorStatus;

class DoctorStatusController extends Controller
{
    public function index() {
        $doctors = doctorStatus::whereDate('tanggal', '=', now())
                ->where('tipe_poli', '<>', 'EXECUTIVE')
                ->whereHas('doctor', function($query) {
                    $query->whereNotIn('keterangan', [
                                'UMUM', 'DOKTER UMUM', 'DOKTER PCR', 'AHLI GIZI', 'PETUGAS MEDIS', 'BIDAN', 'DIETIZIEN', 'FISIOTERAPI', 'KIA', 'PLRS'
                    ]);
                })
                ->with(['doctor' => function($query) {
                    $query->select('kode', 'nama', 'keterangan');
                }])
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
                        }),
                        'keterangan' => $status->first()->doctor ? $status->first()->doctor->keterangan : 'Tidak diketahui',
                    ];
                });
        // dd(now()->toDateString());
        // dd($doctors);
        return view('dashboard', ['doctors' => $doctors]);
    }
}
