<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create(
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin'),
            ]
        );

        $MenuPermission = ['Data Karyawan', 'Karyawan MCU', 'Managemen User', 'Pengaturan Aplikasi'];

        // Create the permissions
        foreach ($MenuPermission as $key => $value) {
            Permission::create(['name' => $value]);
        }

        $roleAdmin = Role::create(['name' => 'Admin']);
        // $roleOperator = Role::create(['name' => 'Operator']);

        // // Give all permissions to the Admin role
        $roleAdmin->givePermissionTo(Permission::all());
        $user->assignRole('Admin');

        // Seed the table with the list of medical conditions
        DB::table('medical_conditions')->insert([
            ['name' => 'Pre Diabetes/Diabetes'],
            ['name' => 'Dislipidemia'],
            ['name' => 'Fatty Liver'],
            ['name' => 'Obesitas'],
            ['name' => 'Hiperuricemia'],
            ['name' => 'Buta Warna Parsial / Total'],
            ['name' => 'Spiro Obstruktif / Restriktif'],
            ['name' => 'Tuli Sensorineural / NIHL'],
            ['name' => 'Autoimun'],
            ['name' => 'Susp. Gangguan Mental Emosional'],
            ['name' => 'Gangguan Renal'],
            ['name' => 'Gangguan Gallblader'],
            ['name' => 'Massa Mammae'],
        ]);

        DB::table('status_fit_works')->insert([
            ['name_status' => 'Fit To Work'],
            ['name_status' => 'Fit With Medical Notes'],
            ['name_status' => 'Temporary Unfit'],
            ['name_status' => 'Unfit To Work'],
        ]);
    }
}
