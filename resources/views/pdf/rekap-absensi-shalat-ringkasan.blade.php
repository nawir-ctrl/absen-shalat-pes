<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Ringkasan Absensi Shalat</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 18px;
        }
        .header h2,
        .header p {
            margin: 0;
        }
        .info {
            margin-bottom: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #444;
        }
        th, td {
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #e5e7eb;
        }
        .number {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $profilPesantren->nama_pesantren ?? 'Pondok Pesantren' }}</h2>
        <p>{{ $profilPesantren->alamat ?? '-' }}</p>
        <p><strong>Rekap Ringkasan Absensi Shalat Per Santri</strong></p>
    </div>

    <div class="info">
        <p>Periode: {{ $tanggalMulai }} s.d. {{ $tanggalSelesai }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Santri</th>
                <th>Kelas</th>
                <th>Hadir</th>
                <th>Masbuk</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Alpa</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ringkasanSantri as $item)
                <tr>
                    <td class="number">{{ $loop->iteration }}</td>
                    <td>{{ $item->nis }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->kelas_nama ?? '-' }}</td>
                    <td class="number">{{ (int) $item->jumlah_hadir }}</td>
                    <td class="number">{{ (int) $item->jumlah_masbuk }}</td>
                    <td class="number">{{ (int) $item->jumlah_izin }}</td>
                    <td class="number">{{ (int) $item->jumlah_sakit }}</td>
                    <td class="number">{{ (int) $item->jumlah_alpha }}</td>
                    <td class="number">{{ (int) $item->total_absensi }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">Belum ada data santri aktif untuk filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
