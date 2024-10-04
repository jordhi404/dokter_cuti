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
        <h1>Info Dokter Hari Ini</h1>
    </div>

    <div class="slider" id="doctor-slider">
        @foreach ($doctors->chunk(8) as $chunk)
            <div class="slide">
                <div class="doctor-cards">
                    @foreach ($chunk as $doctor)
                        <div class="card">
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
                                    <p>{{ $doctor['spesialisasi'] }}</p>

                                    @foreach ($doctor['praktik'] as $praktik)
                                        <p class="card-text">
                                            @if ($praktik['status'] === 'CUTI')
                                                <span class="status-stamp status-cuti">CUTI</span>
                                            @else
                                                <span class="status-stamp status-on-duty">ON DUTY</span>
                                            @endif
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="video-container">
        <video autoplay loop muted width="100%" height="700" src="video/MCU_Gizi.mp4" type="video/mp4"></video>
    </div>

    <script src="script.js"></script>
</body>
</html>
