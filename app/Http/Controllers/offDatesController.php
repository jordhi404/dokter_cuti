<?php

namespace App\Http\Controllers;

use App\Models\doctorStatus;
use Carbon\Carbon;

class offDatesController extends Controller
{
    public function getDoctorOffDates() {
        // Menyiapkan variabel untuk menyimpan data cuti
        $doctors = doctorStatus::where('qmax', 0)
            ->where('tipe_poli', '<>', 'EXECUTIVE')
            ->whereMonth('tanggal', '=', now()->month)
            ->whereYear('tanggal', '=', now()->year)
            ->whereHas('doctor', function ($query) {
                $query->whereNotIn('keterangan', [
                    'UMUM', 'DOKTER UMUM', 'DOKTER PCR', 'AHLI GIZI', 'PETUGAS MEDIS', 'BIDAN',
                    'DIETIZIEN', 'FISIOTERAPI', 'KIA', 'PLRS'
                ]);
            })
            ->with('doctor')
            ->orderBy('kddokter')
            ->orderBy('tanggal')
            ->get();

        return $doctors;
    }
    
    public function processDoctorOffDates() {
        $doctors = $this->getDoctorOffDates();

        $processedDoctors = $doctors->groupBy('kddokter')->map(function ($doctorGroup) {
            $groupedPeriods = [];
            $previousDate = null;
            $groupStartDate = null;

            foreach ($doctorGroup as $status) {
                $currentDate = Carbon::parse($status->tanggal);

                // Mulai grup baru jika tidak ada tanggal sebelumnya atau jika tanggal sekarang tidak berurutan dengan yang sebelumnya.
                if (!$previousDate || $previousDate->diffInDays($currentDate) > 1) {
                    if ($groupStartDate) {
                        // Simpan grup sebelumnya sebelum memulai yang baru.
                        $groupedPeriods[] = [
                            'cuti_start' => $groupStartDate->toDateString(),
                            'cuti_end' => $previousDate->toDateString(),
                        ];
                    }

                    // Inisialisasi grup baru.
                    $groupStartDate = $currentDate;
                }

                // Simpan tanggal sebelumnya.
                $previousDate = $currentDate;
            }

            // Simpan grup terakhir.
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

        // Menyaring periode cuti yang belum selesai.
        $processedDoctors = $processedDoctors->map(function ($doctor) {
            // Filter untuk menghilangkan periode yang sudah lewat
            $filteredPeriods = collect($doctor['cuti'])->filter(function ($period) {
                return Carbon::parse($period['cuti_end'])->isToday() || Carbon::parse($period['cuti_end'])->isFuture();
            })->values()->all(); // Mengatur ulang indeks

            // Jika tidak ada periode valid, dokter ini tidak akan ditampilkan
            if (count($filteredPeriods) > 0) {
                // Format periode cuti menggunakan method formatCutiPeriods
                $formattedCuti = $this->formatCutiPeriods($filteredPeriods);
                $doctor['formattedCuti'] = $formattedCuti;
                return $doctor;
            }

            return null; // Kembalikan null jika tidak ada periode yang valid
        })->filter()->values(); // Filter untuk membuang null entries

        // dd($processedDoctors);

        // Mengirim data ke view
        return view('offDates', compact('processedDoctors'));
    }

    /**
     * Format periode cuti sesuai dengan keinginan.
     *
     * @param array $periods
     * @return string
     */
    private function formatCutiPeriods($periods)
    {
        $formattedCutiParts = [];
        $monthYear = null;

        foreach ($periods as $period) {
            $start = Carbon::parse($period['cuti_start']);
            $end = Carbon::parse($period['cuti_end']);

            if (!$monthYear) {
                $monthYear = $start->translatedFormat('F Y');
            }

            // Cek apakah tanggal mulai dan akhir sama
            if ($start->eq($end)) {
                $formattedCutiParts[] = $start->translatedFormat('d');
            } else {
                $formattedCutiParts[] = $start->translatedFormat('d') . ' - ' . $end->translatedFormat('d');
            }
        }

        // Menggabungkan bagian-bagian yang sudah diformat
        $formattedCuti = [];
        $count = count($formattedCutiParts);

        foreach ($formattedCutiParts as $index => $part) {
            // Jika ini adalah bagian terakhir dan lebih dari satu bagian
            if ($count > 1 && $index == $count - 1) {
                $formattedCuti[] = 'dan ' . $part; // Menambahkan 'dan' sebelum elemen terakhir
            } else {
                $formattedCuti[] = $part; // Menambahkan bagian yang diformat
            }
        }

        // Menggabungkan dengan koma
        $output = implode(', ', $formattedCuti);
        
        // Menambahkan prefiks 'CUTI PADA TANGGAL' pada hasil akhir
        return 'CUTI PADA TANGGAL ' . $output . ' ' . $monthYear;
    }
}
