<?php

namespace App\Exports;

use App\Models\Karyawan;
use App\Models\KaryawanMCU;
use App\Models\MedicalCondition;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KaryawanMCUExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * @return array
     */
    public function array(): array
    {
        $karyawanData = Karyawan::all()->toArray();
        $mcuData = KaryawanMCU::all()->keyBy('karyawan_id')->toArray();
        $conditions = MedicalCondition::all()->pluck('name')->toArray();

        $exportData = [];

        foreach ($karyawanData as $karyawan) {
            $row = [
                $karyawan['id'],
                $karyawan['name'],
                $mcuData[$karyawan['id']]['hasil_mcu'] ?? 'N/A',
                $mcuData[$karyawan['id']]['kardiovascular'] ?? 'N/A',
            ];

            foreach ($conditions as $condition) {
                $row[] = $mcuData[$karyawan['id']][$condition] ?? 'N/A';
            }

            $exportData[] = $row;
        }

        return $exportData;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $conditions = MedicalCondition::all()->pluck('name')->toArray();
        $years = $this->getYears(); // Get years dynamically

        $mainHeaders = [
            'No Badge',
            'Nama',
            'Hasil MCU',
            'Kardiovascular',
        ];

        $yearSubheadings = array_merge(
            array_fill(0, count($mainHeaders), ''), // Empty cells for main headers
            array_map(fn($year) => "Tahun $year", $years), // Create year labels
            array_fill(0, count($conditions) - count($years), '') // Fill remaining with empty cells
        );

        $headings = array_merge($mainHeaders, $conditions);
        $subheadings = array_merge($yearSubheadings, array_fill(0, count($conditions) - count($yearSubheadings), ''));

        return [
            $headings,
            $subheadings,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $columnCount = count($this->headings()[0]);

        // Assume columns 3 and 4 are for 'Hasil MCU' and 'Kardiovascular'
        $mcuStartColumn = 3; // Starting column for 'Hasil MCU'
        $kardiovascularColumn = 4; // Starting column for 'Kardiovascular'

        // Assume columns after 'Hasil MCU' and 'Kardiovascular' are for conditions
        $conditionStartColumn = $kardiovascularColumn + 1;

        $years = $this->getYears();
        $conditionCount = count(MedicalCondition::all());
        $yearsCount = count($years);

        // Merge cells for 'Hasil MCU' and years
        $sheet->mergeCells("C1:C2"); // 'Hasil MCU'
        $sheet->mergeCells("D1:D2"); // 'Kardiovascular'

        $mcuYearRange = "C1:" . chr(64 + $mcuStartColumn + $yearsCount - 1) . "2";
        $sheet->mergeCells($mcuYearRange);

        $kardiovascularYearRange = "D1:" . chr(64 + $kardiovascularColumn + $yearsCount - 1) . "2";
        $sheet->mergeCells($kardiovascularYearRange);

        // Merge cells for conditions based on years
        $conditionYearRange = "E1:" . chr(64 + $conditionStartColumn + $conditionCount - 1) . "2";
        $sheet->mergeCells($conditionYearRange);

        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Get list of years from KaryawanMCU
     *
     * @return array
     */
    private function getYears()
    {
        return KaryawanMCU::groupBy('tahun_mcu')->pluck('tahun_mcu')->toArray();
    }
}
