<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanMedicalConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan_medical_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_karyawan_mcu');
            $table->unsignedInteger('id_medical_condition');
            $table->timestamps();

            $table->foreign('id_karyawan_mcu')->references('id')->on('karyawan_mcus')->onDelete('cascade');
            $table->foreign('id_medical_condition')->references('id')->on('medical_conditions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan_medical_conditions');
    }
}
