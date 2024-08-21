<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
class Karyawan extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $fillable = ['no_badge', 'nama_karyawan', 'tempat_lahir', 'tgl_lahir', 'no_hp_wa', 'nama_istri_suami', 'no_hp_istri_suami','foto'];

    // Mutator untuk menyimpan tanggal dalam format tglbulanTahun
    public function setTglLahirAttribute($value)
    {
        $this->attributes['tgl_lahir'] = Carbon::createFromFormat('Y-m-d', $value)->format('dmY');
    }

    // Accessor untuk mengubah format 'tglbulanTahun' ke 'Y-m-d' saat mengakses
    public function getTglLahirAttribute($value)
    {
        return Carbon::createFromFormat('dmY', $value)->format('Y-m-d');
    }

    // Accessor untuk menghitung usia berdasarkan tanggal lahir
    public function getUsiaAttribute()
    {
        // Konversi format tglbulanTahun menjadi objek Carbon
        $tanggal_lahir = Carbon::createFromFormat('dmY', $this->attributes['tgl_lahir']);
        // Hitung usia dari tanggal lahir hingga saat ini
        return $tanggal_lahir->age;
    }

    public function dataMcu(){
        return $this->hasMany(KaryawanMcu::class, 'id_karyawan');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($karyawan) {
            $errorMessages = [];

            // Cek apakah ada adat istiadat terkait
            if ($karyawan->dataMcu()->count() > 0) {
                $errorMessages[] = 'Data MCU terkait tidak dapat dihapus';
            }


            // Lempar pengecualian jika terdapat pesan kesalahan
            if (!empty($errorMessages)) {
                throw new Exception(implode(' ', $errorMessages));
            }
        });
    }
}
