<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi Kegiatan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2, .header p {
            margin: 0;
        }
        .info {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #444;
        }
        th, td {
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $profilPesantren->nama_pesantren ?? 'Pondok Pesantren' }}</h2>
        <p>{{ $profilPesantren->alamat ?? '-' }}</p>
        <p><strong>Rekap Absensi Kegiatan Tambahan</strong></p>
    </div>

    <div class="info">
        <p>Periode: {{ $tanggalMulai }} s.d. {{ $tanggalSelesai }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kegiatan</th>
                <th>Nama Santri</th>
                <th>Kelas</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Input Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekap as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->tanggal->format('Y-m-d') }}</td>
                    <td>{{ $item->kegiatanTambahan->nama ?? '-' }}</td>
                    <td>{{ $item->santri->nama ?? '-' }}</td>
                    <td>{{ $item->santri->kelas->nama ?? '-' }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td>{{ $item->keterangan ?: '-' }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Belum ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>