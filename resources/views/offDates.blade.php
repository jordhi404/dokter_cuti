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
        <img class="floatLeft" src="profile_icon/logoKARS.png" alt="KARS_icon">
        <img class="floatRight" src="profile_icon/logo_rs.png" alt="rs_icon">
        <h1 id="title-text">Info Cuti Dokter</h1>
        <h2 id="date-text">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</h2>
    </div>

    <!-- Seksi Dokter Sedang Cuti -->
    <div class="doctor-section">
        <div id="doctor-slider-container">
            <p class="no-data"></p>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
