<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Per Santri</title>
    <style>
        @page {
            margin: 14px 18px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
            line-height: 1.35;
        }

        .header-table,
        .info-table,
        .summary-table,
        .data-table,
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header {
            margin-bottom: 10px;
            padding-bottom: 4px;
        }

        .header-table td {
            vertical-align: top;
        }

        .logo-cell {
            width: 75px;
            text-align: center;
        }

        .logo-cell img {
            width: 58px;
            height: 58px;
            object-fit: contain;
        }

        .header-text {
            text-align: center;
        }

        .header-text h1 {
            margin: 0;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-text h2 {
            margin: 2px 0 0;
            font-size: 12px;
            font-weight: bold;
        }

        .header-text p {
            margin: 2px 0 0;
            font-size: 10px;
        }

        .header-title {
            margin-top: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        .kop-line-1 {
            border-top: 2px solid #000;
            margin-top: 8px;
        }

        .kop-line-2 {
            border-top: 1px solid #000;
            margin-top: 2px;
            margin-bottom: 10px;
        }

        .periode {
            margin: 8px 0 10px;
            font-size: 10px;
        }

        .section {
            margin-top: 12px;
        }

        .section h4 {
            margin: 0 0 6px;
            font-size: 11px;
            border-bottom: 1px solid #999;
            padding-bottom: 3px;
        }

        .info-table td {
            padding: 3px 5px;
            vertical-align: top;
        }

        .label {
            width: 70px;
            font-weight: bold;
        }

        .summary-wrapper {
            width: 100%;
            margin-bottom: 8px;
        }

        .summary-col {
            width: 48.5%;
            display: inline-block;
            vertical-align: top;
        }

        .summary-col.right {
            margin-left: 2%;
        }

        .summary-table td {
            border: 1px solid #666;
            padding: 4px 6px;
        }

        .summary-table td:first-child {
            width: 70%;
        }

        .summary-table td:last-child {
            width: 30%;
            text-align: center;
            font-weight: bold;
        }

        .data-table {
            margin-top: 6px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #555;
            padding: 4px 5px;
            text-align: left;
            vertical-align: top;
        }

        .data-table th {
            background: #e5e7eb;
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .empty {
            text-align: center;
            color: #666;
            font-style: italic;
        }

        .signature-section {
            margin-top: 24px;
        }

        .signature-table td {
            vertical-align: top;
        }

        .signature-box {
            width: 240px;
            text-align: center;
        }

        .signature-space {
            height: 55px;
        }
    </style>
</head>
<body>
    @php
        $logoPath = null;

        if (!empty($profilPesantren?->logo)) {
            $logoPath = public_path('storage/' . $profilPesantren->logo);
        }

        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $tanggalSekarang = now()->timezone(config('app.timezone'));
        $tanggalIndonesia = $tanggalSekarang->format('d') . ' ' . $bulanIndonesia[(int) $tanggalSekarang->format('m')] . ' ' . $tanggalSekarang->format('Y');
    @endphp

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if($logoPath && file_exists($logoPath))
                        <img src="{{ $logoPath }}" alt="Logo Pesantren">
                    @endif
                </td>
                <td class="header-text">
                    <h1>{{ $profilPesantren->nama_pesantren ?? 'Pondok Pesantren' }}</h1>
                    <h2>Sistem Absensi Pesantren</h2>
                    <p>{{ $profilPesantren->alamat ?? '-' }}</p>
                    <div class="header-title">LAPORAN REKAP ABSENSI PER SANTRI</div>
                </td>
                <td class="logo-cell"></td>
            </tr>
        </table>

        <div class="kop-line-1"></div>
        <div class="kop-line-2"></div>
    </div>

    <div class="periode">
        <strong>Periode:</strong> {{ $tanggalMulai }} s.d. {{ $tanggalSelesai }}
    </div>

    @if($santri)
        <div class="section">
            <h4>Identitas Santri</h4>
            <table class="info-table">
                <tr>
                    <td class="label">Nama</td>
                    <td>: {{ $santri->nama }}</td>
                    <td class="label">NIS</td>
                    <td>: {{ $santri->nis }}</td>
                </tr>
                <tr>
                    <td class="label">Kelas</td>
                    <td>: {{ $santri->kelas->nama ?? '-' }}</td>
                    <td class="label">Status</td>
                    <td>: {{ ucfirst($santri->status) }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h4>Ringkasan Absensi</h4>

            <div class="summary-wrapper">
                <div class="summary-col">
                    <table class="summary-table">
                        <tr>
                            <td colspan="2" style="font-weight: bold; background: #f3f4f6;">Absensi Shalat</td>
                        </tr>
                        <tr>
                            <td>Hadir</td>
                            <td>{{ $ringkasanShalat['hadir'] }}</td>
                        </tr>
                        <tr>
                            <td>Masbuk</td>
                            <td>{{ $ringkasanShalat['masbuk'] }}</td>
                        </tr>
                        <tr>
                            <td>Izin</td>
                            <td>{{ $ringkasanShalat['izin'] }}</td>
                        </tr>
                        <tr>
                            <td>Sakit</td>
                            <td>{{ $ringkasanShalat['sakit'] }}</td>
                        </tr>
                        <tr>
                            <td>Alpha</td>
                            <td>{{ $ringkasanShalat['alpha'] }}</td>
                        </tr>
                    </table>
                </div>

                <div class="summary-col right">
                    <table class="summary-table">
                        <tr>
                            <td colspan="2" style="font-weight: bold; background: #f3f4f6;">Absensi Kegiatan Tambahan</td>
                        </tr>
                        <tr>
                            <td>Hadir</td>
                            <td>{{ $ringkasanKegiatan['hadir'] }}</td>
                        </tr>
                        <tr>
                            <td>Izin</td>
                            <td>{{ $ringkasanKegiatan['izin'] }}</td>
                        </tr>
                        <tr>
                            <td>Sakit</td>
                            <td>{{ $ringkasanKegiatan['sakit'] }}</td>
                        </tr>
                        <tr>
                            <td>Alpha</td>
                            <td>{{ $ringkasanKegiatan['alpha'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="section">
            <h4>Riwayat Absensi Shalat</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 12%;">Tanggal</th>
                        <th style="width: 10%;">Waktu</th>
                        <th style="width: 12%;">Status</th>
                        <th>Keterangan</th>
                        <th style="width: 18%;">Input Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapShalat as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->tanggal->format('Y-m-d') }}</td>
                            <td>{{ ucfirst($item->waktu_shalat) }}</td>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td>{{ $item->keterangan ?: '-' }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">Belum ada data absensi shalat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <h4>Riwayat Absensi Kegiatan Tambahan</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 12%;">Tanggal</th>
                        <th style="width: 22%;">Kegiatan</th>
                        <th style="width: 12%;">Status</th>
                        <th>Keterangan</th>
                        <th style="width: 18%;">Input Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapKegiatan as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->tanggal->format('Y-m-d') }}</td>
                            <td>{{ $item->kegiatanTambahan->nama ?? '-' }}</td>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td>{{ $item->keterangan ?: '-' }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">Belum ada data absensi kegiatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td></td>
                    <td class="signature-box">
                        
                        <p><strong>Mengetahui,</strong></p>
                        <p><strong>Wali Santri</strong></p>
                        <div class="signature-space"></div>
                        <p><strong>(................................)</strong></p>
                    </td>
                </tr>
            </table>
        </div>
    @else
        <p>Data santri tidak ditemukan.</p>
    @endif
</body>
</html>