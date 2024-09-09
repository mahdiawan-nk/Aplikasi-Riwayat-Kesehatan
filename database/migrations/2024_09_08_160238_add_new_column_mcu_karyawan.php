<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnMcuKaryawan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('karyawan_mcus', function (Blueprint $table) {
            $table->unsignedInteger('status_fit_to_work')->nullable()->after('tahun_mcu');
            $table->string('hasil_mcu')->nullable();
            $table->foreign('status_fit_to_work')->references('id')->on('status_fit_works');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('karyawan_mcus', function (Blueprint $table) {
            
        });
    }
}
