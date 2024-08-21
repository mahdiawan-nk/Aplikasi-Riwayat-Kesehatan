<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Karyawan;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID'); // Gunakan locale Indonesia

        for ($i = 0; $i < 100; $i++) {
            // Menghasilkan tanggal lahir dengan format Y-m-d
            $tgl_lahir = $faker->date('Y-m-d');

            // Format tanggal lahir menjadi dmy untuk password
            $password = \Carbon\Carbon::createFromFormat('Y-m-d', $tgl_lahir)->format('dmy');

            Karyawan::create([
                'no_badge' => $faker->numerify('B###'), // Menghasilkan nomor badge seperti B123
                'nama_karyawan' => $faker->name,
                'tempat_lahir' => $faker->city,
                'tgl_lahir' => $tgl_lahir,
                'no_hp_wa' => $faker->phoneNumber,
                'nama_istri_suami' => $faker->name,
                'no_hp_istri_suami' => $faker->phoneNumber,
                'password' => bcrypt($password), // Menggunakan tanggal lahir dengan format dmy sebagai password
            ]);
        }
    }
}
