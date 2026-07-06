<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekapitulasi Progress Proyek</title>
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

        .text-right {
            text-align: right !important;
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
        <h3 style="margin: 0; font-size: 11px; color: #111c44; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px;">LAPORAN REKAPITULASI PROGRESS PROYEK KESELURUHAN</h3>
        @if(($startDate ?? '') || ($endDate ?? ''))
            <p style="margin: 2px 0 0 0; font-size: 9px; color: #4a5568;">
                Periode: {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Awal' }} s.d {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Akhir' }}
            </p>
        @else
            <p style="margin: 2px 0 0 0; font-size: 9px; color: #4a5568;">
                Tanggal Unduh: {{ date('d F Y') }}
            </p>
        @endif
    </div>

    <!-- Table Data -->
    <table class="report-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 25px;">NO</th>
                <th style="width: 140px;">KODE & NAMA PROYEK</th>
                <th>LOKASI</th>
                <th>PELAKSANA</th>
                <th class="text-center" style="width: 60px;">TARGET PROGRESS</th>
                <th class="text-center" style="width: 60px;">REALISASI PROGRESS</th>
                <th class="text-center" style="width: 65px;">BOBOT PEKERJAAN (AKUMULATIF)</th>
                <th class="text-center" style="width: 65px;">PERSENTASE PENYELESAIAN</th>
                <th class="text-center" style="width: 50px;">SELISIH TARGET</th>
                <th class="text-center" style="width: 60px;">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="fw-bold" style="color: #1a365d;">{{ $item['kode_proyek'] }}</div>
                        <div class="fw-bold">{{ $item['nama_proyek'] }}</div>
                    </td>
                    <td>{{ $item['lokasi'] }}</td>
                    <td>{{ $item['kontraktor'] }}</td>
                    <td class="text-center">{{ number_format($item['target'], 2) }}%</td>
                    <td class="text-center fw-bold">{{ number_format($item['actual'], 2) }}%</td>
                    <td class="text-center fw-bold" style="background-color: #f7fafc;">{{ number_format($item['actual'], 2) }}%</td>
                    <td class="text-center fw-bold" style="color: #2b6cb0;">{{ number_format($item['actual'], 2) }}%</td>
                    <td class="text-center fw-bold" style="color: {{ $item['selisih'] >= 0 ? '#2f855a' : '#c53030' }}">
                        {{ $item['selisih'] >= 0 ? '+' : '' }}{{ number_format($item['selisih'], 2) }}%
                    </td>
                    <td class="text-center">
                        {{ strtoupper($item['status']) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="color: #a0aec0; font-style: italic;">Tidak ada data progress proyek ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
