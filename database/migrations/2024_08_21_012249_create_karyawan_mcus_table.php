<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanMcusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan_mcus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_karyawan');
            $table->string('riwayat_kesehatan');
            $table->string('riwayat_konsumsi_obat');
            $table->string('score_kardiovaskular_jakarta');
            $table->string('file_mcu');
            $table->year('tahun_mcu');
            $table->foreign('id_karyawan')->references('id')->on('karyawans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan_mcus');
    }
}
