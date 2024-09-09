<?php

namespace App\Imports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KaryawanImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (!isset($row['no_badge'])) {
            return null;
        }
        $existingKaryawan = Karyawan::where('no_badge', $row['no_badge'])->first();

        if ($existingKaryawan) {
            // Data sudah ada, tidak perlu diinsert
            return null;
        }
        // Convert Excel serial date to Carbon date
        $tglLahir = $this->excelSerialToDate($row['tanggal_lahir_yyyy_mm_dd'])->format('dmY');

        return new Karyawan([
            'no_badge' => $row['no_badge'],
            'nama_karyawan' => $row['nama_karyawan'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tgl_lahir' => $this->excelSerialToDate($row['tanggal_lahir_yyyy_mm_dd'])->format('Y-m-d'),
            'no_hp_wa' => $row['no_hpwa'],
            'email' => $row['email'],
            'nama_istri_suami' => $row['nama_istrisuami'],
            'no_hp_istri_suami' => $row['no_hp_istrisuami'],
            'password' => Hash::make($tglLahir),  // Hashing the formatted birth date
        ]);
    }

    /**
     * Convert Excel serial date to Carbon date.
     *
     * @param int $serial
     * @return \Carbon\Carbon
     */
    private function excelSerialToDate($serial)
    {
        // Adjust for Excel's date system which starts from 1900-01-01
        $baseDate = \Carbon\Carbon::createFromFormat('Y-m-d', '1899-12-30');
        return $baseDate->addDays($serial);
    }
}
