<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanMcu extends Model
{
    use HasFactory;
    protected $fillable = ['id_karyawan','riwayat_kesehatan', 'riwayat_konsumsi_obat', 'score_kardiovaskular_jakarta', 'file_mcu', 'tahun_mcu'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class,'id_karyawan');
    }
}
