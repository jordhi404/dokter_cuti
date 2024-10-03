<?php

namespace App\Http\Controllers;

use App\Models\doctorStatus;

class RegulerDoctorStatusController extends Controller
{
    public function index() {
        $doctors = doctorStatus::whereDate('tanggal', '=', now())
                ->where('tipe_poli', '=', 'REGULER')
                ->whereHas('doctor', function($query) {
                    $query->whereNotIn('keterangan', [
                                'UMUM', 'DOKTER UMUM', 'DOKTER PCR', 'AHLI GIZI', 'PETUGAS MEDIS', 'BIDAN', 'DIETIZIEN', 'FISIOTERAPI', 'KIA', 'PLRS'
                    ]);
                })
                ->with(['doctor' => function($query) {
                    $query->select('kode', 'nama', 'keterangan', 'ket1');
                }])
                ->orderBy('kddokter')
                ->get()
                ->groupBy('kddokter')
                ->map(function ($status) {
                    return [
                        'kode' => $status->first()->kddokter,
                        'nama' => $status->first()->doctor ? $status->first()->doctor->nama : 'Tidak diketahui',
                        'spesialisasi' => $status->first()->doctor ?  $status->first()->doctor->ket1 : 'Tidak diketahui',
                        'praktik' => $status->map(function($statuses) {
                            return [                        
                                'status' => $statuses->qmax == 0 ? 'CUTI' : 'ON DUTY',
                            ];
                        }),
                        'keterangan' => $status->first()->doctor ? $status->first()->doctor->keterangan : 'Tidak diketahui',
                    ];
                });
        // dd(now()->toDateString());
        // dd($doctors);
        return view('reguler', ['doctors' => $doctors]);
    }
}
