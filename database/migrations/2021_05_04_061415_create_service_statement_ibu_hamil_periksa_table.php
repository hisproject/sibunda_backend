<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStatementIbuHamilPeriksaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_statement_ibu_hamil_periksa', function (Blueprint $table) {
            $table->id();
            $table->string('timbang');
            $table->string('ukur_lingkar_lengan_atas');
            $table->string('tekanan_darah');
            $table->string('periksa_tinggi_rahim');
            $table->string('periksa_letak_dan_denyut_jantung_janin');
            $table->string('status_dan_imunisasi_titanus');
            $table->string('konseling');
            $table->string('skrinning_dokter');
            $table->string('tablet_tambah_darah');
            $table->string('test_lab_hb');
            $table->string('test_golongan_darah');
            $table->string('test_lab_protein_urine');
            $table->string('test_lab_gula_darah');
            $table->string('ppia');
            $table->string('tata_laksana_kasus');
            $table->unsignedBigInteger('trisemester_id');
            $table->timestamps();
            $table->foreign('trisemester_id')->references('id')->on('service_statement_ibu_hamil');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_statement_ibu_hamil_periksa');
    }
}
