<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Dokter</title>
    <link rel="icon" href="{{ asset('profile_icon/logo_rs.jpg') }}" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="text-center" id="title">
        <img class="logo floatLeft" src="profile_icon/doctor_male.png" alt="doctor_male_icon">
        <img class="logo floatRight" src="profile_icon/doctor_female.png" alt="doctor_female_icon">
        <h1>INFO CUTI DOKTER</h1>
    </div>

    <!-- Seksi Dokter Sedang Cuti -->
    <div class="doctor-section">
        @if($processedDoctors->isEmpty())
            <p class="no-data">Tidak ada dokter yang sedang cuti bulan ini.</p>
        @else
            <!-- Cuti Hari Ini -->
            <div class="leave-category">
                <h3>Cuti Hari Ini</h3>
                @php
                    $cutiHariIni = $processedDoctors->filter(function($doctor) {
                        return !empty($doctor['cuti_hari_ini']);
                    });
                @endphp

                @if($cutiHariIni->isEmpty())
                    <p class="no-data">Tidak ada dokter yang sedang cuti hari ini.</p>
                @else
                    <div class="slider" id="doctor-slider-cuti-hari-ini">
                        @foreach ($cutiHariIni->chunk(2) as $chunk)
                            <div class="slide">
                                <div class="doctor-cards">
                                    @foreach ($chunk as $doctor)
                                        <div class="card on-leave-today">
                                            <div class="row">
                                                <div class="card-img">
                                                    @if (file_exists(public_path('profile_picture/' . $doctor['kode'] . '.jpg')))
                                                        <img src="{{ asset('profile_picture/' .  $doctor['kode'] . '.jpg') }}" alt="{{ $doctor['nama'] }}">
                                                    @else
                                                        <img src="{{ asset('profile_icon/profile_pict.png') }}" alt="{{ $doctor['nama'] }}">
                                                    @endif
                                                </div>
                                                <div class="card-body">
                                                    <h4 class="card-title">{{ $doctor['nama'] }}</h4>
                                                    @if(!empty($doctor['formattedCutiHariIni']))
                                                        <p class="card-text">
                                                            {!! $doctor['formattedCutiHariIni'] !!}
                                                        </p>
                                                    @endif
                                                    <span class="badge on-leave-badge">Cuti Hari Ini</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Cuti yang Akan Datang -->
            <div class="leave-category">
                <h3>Cuti yang Akan Datang</h3>
                @php
                    $cutiAkanDatang = $processedDoctors->filter(function($doctor) {
                        return !empty($doctor['cuti_akan_datang']);
                    });
                @endphp

                @if($cutiAkanDatang->isEmpty())
                    <p class="no-data">Tidak ada dokter yang akan cuti bulan ini.</p>
                @else
                    <div class="slider" id="doctor-slider-cuti-akan-datang">
                        @foreach ($cutiAkanDatang->chunk(4) as $chunk)
                            <div class="slide">
                                <div class="doctor-cards">
                                    @foreach ($chunk as $doctor)
                                        <div class="card will-on-leave">
                                            <div class="row">
                                                <div class="card-img">
                                                    @if (file_exists(public_path('profile_picture/' . $doctor['kode'] . '.jpg')))
                                                        <img src="{{ asset('profile_picture/' .  $doctor['kode'] . '.jpg') }}" alt="{{ $doctor['nama'] }}">
                                                    @else
                                                        <img src="{{ asset('profile_icon/profile_pict.png') }}" alt="{{ $doctor['nama'] }}">
                                                    @endif
                                                </div>
                                                <div class="card-body">
                                                    <h4 class="card-title">{{ $doctor['nama'] }}</h4>
                                                    @if(!empty($doctor['formattedCutiAkanDatang']))
                                                        <p class="card-text">
                                                            {!! $doctor['formattedCutiAkanDatang'] !!}
                                                        </p>
                                                    @endif
                                                    <span class="badge not-on-leave-badge">Cuti Akan Datang</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="video-container">
        <video autoplay loop muted width="100%" height="650" src="video/MCU_Gizi.mp4" type="video/mp4"></video>
    </div>

    <script src="script.js"></script>
</body>
</html>
