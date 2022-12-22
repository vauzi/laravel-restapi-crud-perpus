<!DOCTYPE html>
<html lang="en">
<head>
    <title>App | Home</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                {{-- <th>Email</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($pengguna as $p)
                <tr>
                    <td>{{ $p['name'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>