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
            $table->smallInteger('week');
            $table->date('tanggal_periksa');
            $table->string('tempat_periksa');
            $table->string('nama_pemeriksa');
            $table->string('keluhan_bunda');
            $table->enum('jenis_kelamin', ['L', 'P', 'N'])->nullable();
            $table->date('tanggal_periksa_kembali');
            $table->date('hpht')->nullable();
            $table->date('hpl')->nullable();
            $table->double('bb');
            $table->double('kenaikan_bb');
            $table->double('tb');
            $table->string('tfu');
            $table->string('djj');
            $table->string('sistolik');
            $table->string('diastolik');
            $table->string('map');
            $table->string('gerakan_bayi');
            $table->string('resep_obat');
            $table->string('alergi_obat');
            $table->string('riwayat_penyakit');
            $table->text('catatan_khusus');
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
