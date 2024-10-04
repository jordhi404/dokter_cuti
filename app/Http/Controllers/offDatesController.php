<?php

namespace App\Http\Controllers;

use App\Models\doctorStatus;

class offDatesController extends Controller
{
    public function offDatesIndex() {
        $doctors = doctorStatus::where('qmax', 0)
                ->where('tanggal', '>=', now()->subMonth())  // Tanggal dari sebulan yang lalu
                ->where('tanggal', '>=', today())              // Hanya menampilkan cuti hari ini sampai setelahnya
                ->where('tipe_poli', '<>', 'EXECUTIVE')      // Mengecualikan tipe_poli EXECUTIVE
                ->whereHas('doctor', function ($query) {
                    $query->whereNotIn('keterangan', [
                        'UMUM', 'DOKTER UMUM', 'DOKTER PCR', 'AHLI GIZI', 'PETUGAS MEDIS', 'BIDAN',
                        'DIETIZIEN', 'FISIOTERAPI', 'KIA', 'PLRS'
                    ]);
                })
                ->with(['doctor' => function ($query) {
                    $query->select('kode', 'nama', 'keterangan');
                }])
                ->selectRaw('kddokter, MIN(tanggal) as cuti_start, MAX(tanggal) as cuti_end')  // Mengambil range cuti
                ->groupBy('kddokter')
                ->orderBy('kddokter')
                ->get()
                ->map(function ($status) {
                    return [
                        'kode' => $status->kddokter,
                        'nama' => $status->doctor ? $status->doctor->nama : 'Tidak diketahui',
                        'keterangan' => $status->doctor ? $status->doctor->keterangan : 'Tidak diketahui',
                        'cuti_start' => $status->cuti_start,  // Tanggal awal cuti
                        'cuti_end' => $status->cuti_end,      // Tanggal akhir cuti
                    ];
                });
        // dd(now()->toDateString());
        // dd($doctors);
        return view('offDates', ['doctors' => $doctors]);
    }
}
