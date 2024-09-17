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

    <!-- Page 1 -->
    <div class="page active" id="page1">
        <table class="table-animated" border="1">
            <thead>
                <tr>
                    <th>Nama Dokter</th>
                    <th>Unit Praktik</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doctors1 as $doctor)
                    <tr>
                        <td>{{ $doctor->doctorName }}</td>
                        <td>{{ $doctor->unit }}</td>
                        <td>{{ $doctor->absentStatus }}</td>
                    </tr> 
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Page 2 -->
    <div class="page" id="page2">
        <table class="table-animated" border="1">
            <thead>
                <tr>
                    <th>Nama Dokter</th>
                    <th>Unit Praktik</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doctors2 as $doctor)
                    <tr>
                        <td>{{ $doctor->doctorName }}</td>
                        <td>{{ $doctor->unit }}</td>
                        <td>{{ $doctor->absentStatus }}</td>
                    </tr> 
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="script.js"></script>
</body>
</html>
