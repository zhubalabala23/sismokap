<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Progres Konstruksi - {{ $proyek->nama_proyek }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2d3748;
            margin: 0;
            padding: 0;
            font-size: 13px;
            line-height: 1.5;
        }

        /* Kop Surat */
        .header-container {
            border-bottom: 3px double #111c44;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }

        .header-title {
            color: #111c44;
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 1px;
        }

        .header-subtitle {
            color: #4a5568;
            font-size: 12px;
            text-align: center;
            margin: 4px 0 0 0;
            font-weight: 500;
        }

        /* Layout Grid 2 Kolom */
        .row {
            width: 100%;
            margin-bottom: 25px;
            clear: both;
        }

        .col-left {
            width: 60%;
            float: left;
        }

        .col-right {
            width: 35%;
            float: right;
            text-align: center;
        }

        .clear {
            clear: both;
        }

        /* Card / Table Info */
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #111c44;
            border-left: 4px solid #2d60ff;
            padding-left: 8px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px 4px;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            color: #718096;
            width: 150px;
        }

        .info-value {
            color: #2d3748;
        }

        /* Badge Status */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-weight: bold;
            border-radius: 4px;
            font-size: 11px;
            text-transform: uppercase;
        }

        .badge-berjalan {
            background-color: #ebf8ff;
            color: #2b6cb0;
            border: 1px solid #bee3f8;
        }

        .badge-selesai {
            background-color: #f0fff4;
            color: #2f855a;
            border: 1px solid #c6f6d5;
        }

        .badge-terlambat {
            background-color: #fff5f5;
            color: #c53030;
            border: 1px solid #fed7d7;
        }

        /* Capaian Progress Bars */
        .progress-box {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }

        .progress-item {
            margin-bottom: 10px;
        }

        .progress-label-row {
            overflow: hidden;
            margin-bottom: 4px;
        }

        .progress-label {
            float: left;
            font-weight: bold;
            color: #4a5568;
        }

        .progress-pct {
            float: right;
            font-weight: bold;
            color: #2d60ff;
        }

        .progress-bar-bg {
            background-color: #edf2f7;
            height: 10px;
            border-radius: 5px;
            width: 100%;
            clear: both;
        }

        .progress-bar-fill {
            background-color: #2d60ff;
            height: 10px;
            border-radius: 5px;
        }

        .deviasi-text {
            font-weight: bold;
            margin-top: 10px;
            font-size: 11px;
        }

        .deviasi-ahead {
            color: #2f855a;
        }

        .deviasi-behind {
            color: #c53030;
        }

        /* Dokumentasi Section */
        .dokumentasi-container {
            margin-top: 30px;
        }

        .foto-grid {
            width: 100%;
            margin-top: 10px;
        }

        .foto-cell {
            width: 48%;
            float: left;
            margin-right: 2%;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        .foto-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            background-color: #ffffff;
        }

        .foto-img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
        }

        .foto-info {
            padding: 8px 10px;
            background-color: #f7fafc;
            border-top: 1px solid #e2e8f0;
        }

        .foto-desc {
            font-size: 11px;
            color: #4a5568;
            margin: 0 0 4px 0;
            height: 32px;
            overflow: hidden;
        }

        .foto-date {
            font-size: 10px;
            color: #a0aec0;
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
                    <td style="width: 80px; border: none; vertical-align: middle; padding: 0;">
                        <img src="{{ $logoBase64 }}" style="height: 60px; max-width: 80px; object-fit: contain;">
                    </td>
                @endif
                <td style="border: none; text-align: center; vertical-align: middle; padding: 0 10px;">
                    <h2 style="margin: 0; font-size: 16px; color: #111c44; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px;">{{ $namaInstansi }}</h2>
                    @if($alamatInstansi)
                        <p style="margin: 4px 0 0 0; font-size: 10px; color: #4a5568; line-height: 1.3;">{{ $alamatInstansi }}</p>
                    @endif
                </td>
                @if($logoBase64)
                    <!-- Empty td to balance the table if logo is on left -->
                    <td style="width: 80px; border: none; padding: 0;"></td>
                @endif
            </tr>
        </table>
    </div>

    <!-- Judul Laporan -->
    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0; font-size: 13px; color: #111c44; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px;">LAPORAN PROGRES KONSTRUKSI</h3>
        <p style="margin: 2px 0 0 0; font-size: 11px; color: #4a5568; font-weight: 500;">Proyek: {{ $proyek->nama_proyek }} ({{ $proyek->kode_proyek }})</p>
    </div>

    <!-- Main Section -->
    <div class="row">
        <!-- Informasi Proyek (Kiri) -->
        <div class="col-left">
            <div class="section-title">Informasi Proyek</div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Nama Proyek</td>
                    <td class="info-value">: {{ $proyek->nama_proyek }}</td>
                </tr>
                <tr>
                    <td class="info-label">Lokasi</td>
                    <td class="info-value">: {{ $proyek->lokasi?->nama_lokasi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Kontraktor</td>
                    <td class="info-value">: {{ $proyek->kontraktor?->nama_kontraktor ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Periode</td>
                    <td class="info-value">: {{ $proyek->tanggal_mulai->format('d M Y') }} s/d {{ $proyek->tanggal_selesai->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td class="info-label">Status Proyek</td>
                    <td class="info-value">: 
                        @if($proyek->status === 'berjalan')
                            <span class="badge badge-berjalan">Berjalan</span>
                        @elseif($proyek->status === 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @else
                            <span class="badge badge-terlambat">Terlambat</span>
                        @endif
                    </td>
                </tr>
            </table>

            <!-- Ringkasan Capaian -->
            <div class="progress-box">
                <div class="progress-item">
                    <div class="progress-label-row">
                        <span class="progress-label">Rencana Kumulatif (Target)</span>
                        <span class="progress-pct">{{ number_format($proyek->target_progress, 2) }}%</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $proyek->target_progress }}%; background-color: #4a5568;"></div>
                    </div>
                </div>

                <div class="progress-item">
                    <div class="progress-label-row">
                        <span class="progress-label">Realisasi Kumulatif (Aktual)</span>
                        <span class="progress-pct">{{ number_format($proyek->actual_progress, 2) }}%</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $proyek->actual_progress }}%;"></div>
                    </div>
                </div>

                @php
                    $selisih = $proyek->target_progress - $proyek->actual_progress;
                @endphp
                <div class="deviasi-text">
                    Deviasi: 
                    @if($selisih <= 0)
                        <span class="deviasi-ahead">+{{ number_format(abs($selisih), 2) }}% (AHEAD OF SCHEDULE)</span>
                    @else
                        <span class="deviasi-behind">-{{ number_format($selisih, 2) }}% (BEHIND SCHEDULE)</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pie Chart / Progress Ring (Kanan) -->
        <div class="col-right">
            <div class="section-title" style="border-left: 0; padding-left: 0;">Progress Ring</div>
            <div style="margin-top: 15px;">
                <!-- SVG Progress Ring -->
                <svg width="150" height="150" viewBox="0 0 150 150">
                    <circle cx="75" cy="75" r="60" fill="none" stroke="#edf2f7" stroke-width="12" />
                    @php
                        $pct = $proyek->actual_progress;
                        if ($pct > 100) $pct = 100;
                        if ($pct < 0) $pct = 0;
                        $circumference = 2 * M_PI * 60; // 376.99
                        $offset = $circumference - ($circumference * $pct / 100);
                    @endphp
                    <circle cx="75" cy="75" r="60" fill="none" stroke="#2d60ff" stroke-width="12" 
                            stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}"
                            transform="rotate(-90 75 75)" />
                    <text x="75" y="75" text-anchor="middle" dy="8" font-family="Helvetica, Arial, sans-serif" font-weight="bold" font-size="22" fill="#111c44">
                        {{ number_format($proyek->actual_progress, 0) }}%
                    </text>
                    <text x="75" y="95" text-anchor="middle" font-family="Helvetica, Arial, sans-serif" font-size="10" fill="#a0aec0">
                        PROGRES
                    </text>
                </svg>
            </div>
        </div>
    </div>

    <div class="clear"></div>

    <!-- Dokumentasi Visual (Bawah) -->
    <div class="dokumentasi-container">
        <div class="section-title">Dokumentasi Visual</div>
        
        @if(count($dokumentasis) > 0)
            <div class="foto-grid">
                @foreach($dokumentasis as $foto)
                    <div class="foto-cell">
                        <div class="foto-card">
                            <img src="{{ $foto['base64_src'] }}" class="foto-img" alt="Dokumentasi">
                            <div class="foto-info">
                                <p class="foto-desc">{{ $foto['keterangan'] ?? 'Tidak ada keterangan.' }}</p>
                                <div class="foto-date">{{ $foto['tanggal'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: #a0aec0; font-style: italic; margin-top: 10px;">Belum ada dokumentasi foto yang diunggah untuk proyek ini.</p>
        @endif
    </div>

</body>
</html>
