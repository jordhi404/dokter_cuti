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
        <h1>On Duty Hari Ini</h1>
    </div>

    <div class="slider" id="doctor-slider">
        @foreach ($doctors->chunk(30) as $chunk)
            <div class="slide">
                <table>
                    <thead>
                        <tr>
                            <th>Kode Dokter</th>
                            <th>Nama Dokter</th>
                            <th>Tipe Poli</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chunk as $doctor)
                        <tr>
                            <td>{{ $doctor['kddokter'] }}</td>
                            <td>{{ $doctor['nama'] }}</td>
                            <td>{{ $doctor['tipe_poli'] }}</td>
                            <td>{{ $doctor['status'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

    <script src="script.js"></script>
</body>
</html>
