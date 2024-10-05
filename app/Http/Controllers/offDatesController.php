<?php

namespace App\Http\Controllers;

use App\Models\doctorStatus;
use Carbon\Carbon;
class offDatesController extends Controller
{

public function offDatesIndex() {
    // Menyiapkan variabel untuk menyimpan data cuti
    $doctors = doctorStatus::where('qmax', 0)
        ->whereMonth('tanggal', '=', now()->month)  // Tampilkan hanya data cuti dari bulan saat ini
        ->whereYear('tanggal', '=', now()->year)
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
        ->select('kddokter', 'tanggal')
        ->orderBy('kddokter')
        ->orderBy('tanggal') // Urutkan berdasarkan dokter dan tanggal
        ->get()
        ->groupBy('kddokter') // Mengelompokkan berdasarkan dokter
        ->map(function ($statuses, $doctorId) {
            $groupedPeriods = [];
            $prevDate = null;

            foreach ($statuses as $status) {
                $currentDate = Carbon::parse($status->tanggal); // Mengubah tanggal menjadi instance Carbon

                if ($prevDate && $prevDate->diffInDays($currentDate) > 1) {
                    // Jika ada jeda lebih dari 1 hari, simpan group sebelumnya
                    $groupedPeriods[] = [
                        'cuti_start' => $startDate->toDateString(),
                        'cuti_end' => $prevDate->toDateString(),
                    ];
                    $startDate = $currentDate;
                } elseif (!$prevDate) {
                    // Inisialisasi tanggal awal cuti
                    $startDate = $currentDate;
                }

                $prevDate = $currentDate;
            }

            // Simpan group terakhir
            if (isset($startDate) && isset($prevDate)) {
                $groupedPeriods[] = [
                    'cuti_start' => $startDate->toDateString(),
                    'cuti_end' => $prevDate->toDateString(),
                ];
            }

            return [
                'kode' => $doctorId,
                'nama' => $statuses->first()->doctor->nama ?? 'Tidak diketahui',
                'keterangan' => $statuses->first()->doctor->keterangan ?? 'Tidak diketahui',
                'periods' => $groupedPeriods,
            ];
        })
        ->filter(function ($doctor) {
            // Filter periode cuti yang belum selesai (cuti_end >= hari ini)
            return collect($doctor['periods'])->contains(function ($period) {
                return $period['cuti_end'] >= today()->toDateString();
            });
        });

    // Mengirim data ke view
    return view('offDates', ['doctors' => $doctors]);
} 
}
