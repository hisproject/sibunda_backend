<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStatementIbuNifasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_statement_ibu_nifas', function (Blueprint $table) {
            $table->id();
            $table->string('periksa_payudara');
            $table->string('periksa_pendaharan');
            $table->string('periksa_jalan_lahir');
            $table->string('vit_a');
            $table->string('kb_pasca_persalinan');
            $table->string('konseling');
            $table->string('tata_laksana_kasus');
            $table->unsignedSmallInteger('kf');
            $table->unsignedBigInteger('kia_ibu_id');
            $table->timestamps();
            $table->foreign('kia_ibu_id')->references('id')->on('kia_identitas_ibu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_statement_ibu_nifas');
    }
}
