<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Progress Bulanan Proyek</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2d3748;
            margin: 0;
            padding: 0;
            font-size: 8px;
            line-height: 1.3;
        }

        /* Kop Surat */
        .header-container {
            border-bottom: 2px solid #111c44;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        /* Table Laporan */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .report-table th, .report-table td {
            border: 1px solid #cbd5e0;
            padding: 4px;
            text-align: left;
            vertical-align: middle;
        }

        .report-table th {
            background-color: #f7fafc;
            font-weight: bold;
            color: #4a5568;
            font-size: 7.5px;
            text-transform: uppercase;
        }

        .report-table td {
            font-size: 7.5px;
        }

        .text-center {
            text-align: center !important;
        }

        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="header-container">
        @php
            $logoSetting = \App\Models\Setting::getValue('logo');
            $namaInstansi = \App\Models\Setting::getValue('nama_instansi', 'SISMOKAP');
            $alamatInstansi = \App\Models\Setting::getValue('alamat');
            
            $logoBase64 = '';
            if ($logoSetting) {
                $logoPath = storage_path('app/public/' . $logoSetting);
                if (file_exists($logoPath)) {
                    $logoData = file_get_contents($logoPath);
                    $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($logoData);
                }
            }
        @endphp
        
        <table style="width: 100%; border-collapse: collapse; border: none; margin-bottom: 5px;">
            <tr>
                @if($logoBase64)
                    <td style="width: 50px; border: none; vertical-align: middle; padding: 0;">
                        <img src="{{ $logoBase64 }}" style="height: 40px; max-width: 50px; object-fit: contain;">
                    </td>
                @endif
                <td style="border: none; text-align: center; vertical-align: middle; padding: 0 10px;">
                    <h2 style="margin: 0; font-size: 12px; color: #111c44; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px;">{{ $namaInstansi }}</h2>
                    @if($alamatInstansi)
                        <p style="margin: 2px 0 0 0; font-size: 8px; color: #4a5568; line-height: 1.1;">{{ $alamatInstansi }}</p>
                    @endif
                </td>
                @if($logoBase64)
                    <td style="width: 50px; border: none; padding: 0;"></td>
                @endif
            </tr>
        </table>
    </div>

    <!-- Judul Laporan -->
    <div style="text-align: center; margin-bottom: 12px;">
        <h3 style="margin: 0; font-size: 11px; color: #111c44; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px;">LAPORAN PROGRESS BULANAN PROYEK</h3>
        <p style="margin: 2px 0 0 0; font-size: 9px; color: #4a5568;">
            Bulan: {{ $namaBulan[$bulan] }} {{ $tahun }}
        </p>
    </div>

    <!-- Table Data -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 70px;">KODE PROYEK</th>
                <th style="width: 110px;">NAMA PROYEK</th>
                <th>LOKASI</th>
                <th>KONTRAKTOR</th>
                <th class="text-center" style="width: 65px;">HARIAN (BULAN INI)</th>
                <th class="text-center" style="width: 65px;">MINGGUAN (BULAN INI)</th>
                <th class="text-center" style="width: 65px;">AKUMULASI PROGRES</th>
                <th class="text-center" style="width: 45px;">TARGET</th>
                <th class="text-center" style="width: 45px;">SELISIH</th>
                <th class="text-center" style="width: 55px;">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $item)
                <tr>
                    <td class="fw-bold">{{ $item['kode_proyek'] }}</td>
                    <td class="fw-bold">{{ $item['nama_proyek'] }}</td>
                    <td>{{ $item['lokasi'] }}</td>
                    <td>{{ $item['kontraktor'] }}</td>
                    <td class="text-center">{{ number_format($item['daily_gain'], 2) }}%</td>
                    <td class="text-center">{{ number_format($item['weekly_gain'], 2) }}%</td>
                    <td class="text-center fw-bold" style="color: #2b6cb0;">{{ number_format($item['actual'], 2) }}%</td>
                    <td class="text-center">{{ number_format($item['target'], 2) }}%</td>
                    <td class="text-center fw-bold" style="color: {{ $item['selisih'] >= 0 ? '#2f855a' : '#c53030' }}">
                        {{ $item['selisih'] >= 0 ? '+' : '' }}{{ number_format($item['selisih'], 2) }}%
                    </td>
                    <td class="text-center">
                        {{ strtoupper($item['status']) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="color: #a0aec0; font-style: italic;">Tidak ada data progress proyek pada bulan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
