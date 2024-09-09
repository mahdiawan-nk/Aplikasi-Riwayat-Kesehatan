<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanMcu extends Model
{
    use HasFactory;
    protected $fillable = ['id_karyawan','riwayat_kesehatan', 'riwayat_konsumsi_obat', 'score_kardiovaskular_jakarta', 'file_mcu', 'tahun_mcu','status_fit_to_work','hasil_mcu'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class,'id_karyawan');
    }

    public function medicalCondition()
    {
        return $this->belongsToMany(MedicalCondition::class, 'karyawan_medical_conditions', 'id_karyawan_mcu', 'id_medical_condition');
    }

    public function statusFitToWork(){
        return $this->hasOne(StatusFitWork::class, 'id','status_fit_to_work');
    }
}
