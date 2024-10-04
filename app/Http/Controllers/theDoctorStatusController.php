<?php

namespace App\Http\Controllers;

use App\Models\doctorStatus;
use Carbon\Carbon;

class theDoctorStatusController extends Controller
{
    public function index() {
        $currentTime = Carbon::now()->format('H:i');

        // Menentukan tipe_poli berdasarkan waktu saat ini.
        if($currentTime >= '08:00' && $currentTime < '14:00') {
            $currentTipePoli = 'REGULER';
        } elseif ($currentTime >= '14:00' && $currentTime <= '21:00') {
            $currentTipePoli = 'NON_REGULER';
        } else {
            $currentTipePoli = null;
        }

        // Mulai query.
        $query = doctorStatus::whereDate('tanggal', '=', now());

        // Filter tipe_poli.
        if($currentTipePoli) {
            $query->where('tipe_poli', $currentTipePoli);
        }

        $doctors = $query->whereHas('doctor', function($query) {
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
        return view('doctors', ['doctors' => $doctors]);
    }
}
