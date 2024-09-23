<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="text-center" id="title">
        <h1>Info Dokter</h1>
    </div>

    <div class="slider" id="doctor-slider">
        @foreach ($doctors->chunk(10) as $chunk)
            <div class="slide">
                <div class="doctor-cards">
                    @foreach ($chunk as $doctor)
                        <div class="card">
                            <div class="row">
                                <div class="card-img">
                                    <img src="picture/profile_pict.png" alt="profile">
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">{{ $doctor['nama'] }}</h4>

                                    @foreach ($doctor['praktik'] as $praktik)
                                        <p class="card-text">{{ $praktik['tipe_poli'] }} : 
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
