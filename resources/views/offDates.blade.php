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
    <div class="text-center">
        <img class="floatLeft" src="asset_cuti_dokter/Logo KARS.png" alt="KARS_icon">
        <img class="floatRight" src="asset_cuti_dokter/Logo RS.png" alt="rs_icon">
        <h1 id="title-text">Dokter Cuti Hari Ini</h1>
        <h2 id="date-text">{{ strtoupper(\Carbon\Carbon::now()->translatedFormat('D, d F Y')) }}</h2>
    </div>

    <!-- Seksi Dokter Sedang Cuti -->
    <div class="doctor-section">
        <div id="doctor-slider-container">
            <p class="no-data"></p>
        </div>
    </div>

    <div class="footer"></div>

    <script src="script.js"></script>
</body>

</html>
