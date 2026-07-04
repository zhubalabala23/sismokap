<?php

namespace App\Exports;

use App\Models\Proyek;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProyekRekapExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $rowNumber = 0;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Proyek::with(['lokasi', 'kontraktor'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NO',
            'KODE PROYEK',
            'NAMA PROYEK',
            'LOKASI',
            'KONTRAKTOR',
            'TANGGAL MULAI',
            'TANGGAL SELESAI',
            'TARGET PROGRESS (%)',
            'PROGRESS AKTUAL (%)',
            'SELISIH (%)',
            'STATUS'
        ];
    }

    /**
     * @param mixed $proyek
     * @return array
     */
    public function map($proyek): array
    {
        $this->rowNumber++;
        
        $actual = $proyek->actual_progress;
        $target = $proyek->target_progress;
        $selisih = $target - $actual;

        return [
            $this->rowNumber,
            $proyek->kode_proyek,
            $proyek->nama_proyek,
            $proyek->lokasi->nama_lokasi ?? '-',
            $proyek->kontraktor->nama_kontraktor ?? '-',
            $proyek->tanggal_mulai->format('d-m-Y'),
            $proyek->tanggal_selesai->format('d-m-Y'),
            number_format($target, 2) . '%',
            number_format($actual, 2) . '%',
            ($selisih > 0 ? '+' : '') . number_format($selisih, 2) . '%',
            strtoupper($proyek->status)
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Headings style (Navy background, white bold text)
            1 => [
                'font' => [
                    'bold' => true, 
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '111C44']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                ]
            ],
        ];
    }
}
