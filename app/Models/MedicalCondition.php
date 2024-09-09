<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class MedicalCondition extends Model
{
    use HasFactory;

    protected $fillabel =['name'];

    public function karyawanMcu()
    {
        return $this->belongsToMany(KaryawanMcu::class, 'karyawan_medical_condition', 'id_medical_condition', 'id_karyawan_mcu');
    }

    
}
