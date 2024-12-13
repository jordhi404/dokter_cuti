<?php

namespace App\Http\Controllers;

use App\Models\doctorStatus;
use Carbon\Carbon;

class offDatesController extends Controller
{
    private function getDoctorOffDates() {
        // Menyiapkan variabel untuk menyimpan data cuti
        $doctors = doctorStatus::where('qmax', 0)
            ->whereNotIn('tipe_poli', ['EXECUTIVE', 'NON_REGULER'])
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->whereHas('doctor', function ($query) {
                $query->whereNotIn('keterangan', [
                    'UMUM', 'DOKTER UMUM', 'DOKTER PCR', 'AHLI GIZI', 'PETUGAS MEDIS', 'BIDAN',
                    'DIETIZIEN', 'FISIOTERAPI', 'KIA', 'PLRS', 'PSIKOLOG', 'DOKTER SP RADIOLOGI'
                ]);
            })
            ->join('dokter_tmp', 'dokter_slot.kddokter', '=', 'dokter_tmp.kode') // Join ke tabel dokter_tmp
            ->orderBy('dokter_tmp.nama')  // Urutkan berdasarkan nama di tabel dokter_tmp
            ->orderBy('tanggal')  // Urutkan juga berdasarkan tanggal
            ->select('dokter_slot.*')  // Pastikan memilih kolom dari tabel utama (doctorStatus)
            ->with('doctor')  // Eager load relasi dengan model doctor
            ->get();

        return $doctors;
    }
    
    public function processDoctorOffDates()
    {
        $doctors = $this->getDoctorOffDates();
        $today = Carbon::today();

        // Proses data untuk mengelompokkan periode cuti berdasarkan dokter
        $processedDoctors = $doctors->groupBy('kddokter')->map(function ($doctorGroup) use ($today) {
            $groupedPeriods = [];
            $previousDate = null;
            $groupStartDate = null;

            foreach ($doctorGroup as $status) {
                $currentDate = Carbon::parse($status->tanggal);

                // Mulai grup baru jika tanggal tidak berurutan
                if (!$previousDate || $previousDate->diffInDays($currentDate) > 1) {
                    if ($groupStartDate) {
                        $groupedPeriods[] = [
                            'cuti_start' => $groupStartDate->toDateString(),
                            'cuti_end' => $previousDate->toDateString(),
                        ];
                    }
                    $groupStartDate = $currentDate;
                }

                $previousDate = $currentDate;
            }

            // Simpan grup terakhir
            if ($groupStartDate) {
                $groupedPeriods[] = [
                    'cuti_start' => $groupStartDate->toDateString(),
                    'cuti_end' => $previousDate->toDateString(),
                ];
            }

            return [
                'kode' => $doctorGroup->first()->kddokter,
                'nama' => $doctorGroup->first()->doctor->nama ?? 'Tidak Diketahui',
                'keterangan' => $doctorGroup->first()->doctor->keterangan ?? 'Tidak Diketahui',
                'cuti' => $groupedPeriods,
            ];
        });

        // Pisahkan dokter yang sedang cuti hari ini dan yang akan cuti
        $cutiHariIni = [];
        $cutiAkanDatang = [];

        // Pisahkan cuti hari ini dan yang akan datang
        $processedDoctors->each(function ($doctor) use (&$cutiHariIni, &$cutiAkanDatang, $today) {
            // Filter untuk cuti hari ini
            $cutiHariIniDoctor = collect($doctor['cuti'])->filter(function ($period) use ($today) {
                $start = Carbon::parse($period['cuti_start']);
                $end = Carbon::parse($period['cuti_end']);
                return $start->lte($today) && $end->gte($today);
            })->values();

            // Filter untuk cuti yang akan datang
            $cutiAkanDatangDoctor = collect($doctor['cuti'])->filter(function ($period) use ($today) {
                $start = Carbon::parse($period['cuti_start']);
                return $start->isAfter($today);
            })->values();

            // Tambahkan dokter dengan cuti hari ini
            if ($cutiHariIniDoctor->isNotEmpty()) {
                $cutiHariIni[] = [
                    'kode' => $doctor['kode'],
                    'nama' => $doctor['nama'],
                    'keterangan' => $doctor['keterangan'],
                    'cuti' => $cutiHariIniDoctor
                ];
            }

            // Tambahkan dokter dengan cuti yang akan datang
            if ($cutiAkanDatangDoctor->isNotEmpty()) {
                $cutiAkanDatang[] = [
                    'kode' => $doctor['kode'],
                    'nama' => $doctor['nama'],
                    'keterangan' => $doctor['keterangan'],
                    'cuti' => $cutiAkanDatangDoctor
                ];
            }
        });

        // Struktur data yang akan dikembalikan
        $result = [
            'cuti_hari_ini' => $cutiHariIni,
            'cuti_akan_datang' => $cutiAkanDatang
        ];

        // Kembalikan data sebagai JSON
        return response()->json($result);
    }


    public function showDoctorOffDates() {
        // Ambil data dokter cuti yang sudah diproses
        $doctors = $this->processDoctorOffDates(); // Memanggil langsung proses data
    
        // Kirim data ke View
        return view('offDates', compact('doctors'));
    }
}
