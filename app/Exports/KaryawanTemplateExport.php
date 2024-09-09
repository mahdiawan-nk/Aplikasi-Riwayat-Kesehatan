<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KaryawanTemplateExport implements WithHeadings, WithColumnWidths, WithStyles
{
    /**
     * Define the column headings.
     */
    public function headings(): array
    {
        return [
            'No Badge',            // Column A
            'Nama Karyawan',        // Column B
            'Tempat Lahir',         // Column C
            'Tanggal Lahir (YYYY-MM-DD)',  // Column D
            'No HP/WA',            // Column E
            'Email',               // Column F
            'Nama Istri/Suami',     // Column G
            'No HP Istri/Suami'     // Column H
        ];
    }

    /**
     * Set the width of columns
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 30,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 30,
            'G' => 30,
            'H' => 20,
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        
        return [
            // Set bold text for heading row
            1 => ['font' => ['bold' => true]],
        ];
    }
}
