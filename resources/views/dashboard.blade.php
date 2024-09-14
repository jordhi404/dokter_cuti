<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Ini dashboard >:(</h1>

    <!-- Page 1 -->
    <table class="page active" id="page1" border="1">
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
    
    <!-- Page 2 -->
    <table class="page" id="page2" border="1">
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

    <script src="script.js"></script>
</body>
</html>
