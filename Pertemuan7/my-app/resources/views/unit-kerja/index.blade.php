<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>unit kerja</title>
</head>
<body>
    <h1>Unit Kerja</h1>
    <ul>
        @foreach ($unitkerja as $item)
        <li>{{$item->kode . " - " . $item->nama}}</li>
        @endforeach 
    </ul>
</body>
</html>
