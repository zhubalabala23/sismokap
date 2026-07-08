<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Progress Mingguan Proyek</title>
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
            vertical-align: top;
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
        <h3 style="margin: 0; font-size: 11px; color: #111c44; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px;">LAPORAN PROGRESS MINGGUAN PROYEK</h3>
        <p style="margin: 2px 0 0 0; font-size: 9px; color: #4a5568;">
            Periode Laporan: 
            @if($mingguKe && $tahun)
                Minggu ke-{{ $mingguKe }} Tahun {{ $tahun }}
            @elseif($mingguKe)
                Minggu ke-{{ $mingguKe }}
            @elseif($tahun)
                Tahun {{ $tahun }}
            @else
                Semua Periode
            @endif
        </p>
    </div>

    <!-- Table Data -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 80px;">MINGGU / TAHUN</th>
                <th style="width: 120px;">PROYEK</th>
                <th style="width: 60px;">SEBELUMNYA</th>
                <th style="width: 60px;">MINGGU INI</th>
                <th style="width: 60px;">KUMULATIF</th>
                <th style="width: 60px;">TARGET</th>
                <th style="width: 60px;">SELISIH</th>
                <th>KENDALA</th>
                <th>RENCANA DEPAN</th>
                <th style="width: 50px;">GAMBAR</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $entry)
                <tr>
                    <td style="white-space: nowrap;">Minggu ke-{{ $entry->minggu_ke }} ({{ $entry->tahun }})</td>
                    <td class="fw-bold">{{ $entry->proyek?->nama_proyek ?? 'Proyek Terhapus' }}</td>
                    <td>{{ number_format($entry->progress_sebelumnya, 2) }}%</td>
                    <td>{{ number_format($entry->progress_berjalan, 2) }}%</td>
                    <td class="fw-bold">{{ number_format($entry->persentase, 2) }}%</td>
                    <td>{{ number_format($entry->target_mingguan, 2) }}%</td>
                    <td class="fw-bold" style="color: {{ $entry->selisih_capaian >= 0 ? '#2f855a' : '#c53030' }}">
                        {{ $entry->selisih_capaian >= 0 ? '+' : '' }}{{ number_format($entry->selisih_capaian, 2) }}%
                    </td>
                    <td>{{ $entry->kendala ?? '-' }}</td>
                    <td>{{ $entry->rencana_berikutnya ?? '-' }}</td>
                    <td>
                        @if($entry->proyek?->gambar_proyek_base64)
                            <img src="{{ $entry->proyek->gambar_proyek_base64 }}" style="height: 30px; width: 45px; object-fit: cover; border: 0.5px solid #cbd5e0; border-radius: 2px;">
                        @else
                            <span style="color: #a0aec0; font-size: 7px;">-</span>
                        @endif
                    </td>
                    <td>{{ $entry->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center" style="color: #a0aec0; font-style: italic;">Tidak ada data progress mingguan ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
