<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStatementBayiBaruLahirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_statement_bayi_baru_lahir', function (Blueprint $table) {
            $table->id();
            $table->string('perawatan_tali_pusat');
            $table->string('imd')->nullable();
            $table->string('vitamin_k1')->nullable();
            $table->string('imunisasi_hepatitis_b')->nullable();
            $table->string('saleb_mata_antibiotik')->nullable();
            $table->string('skrinning_bbl_shk')->nullable();
            $table->string('kie');
            $table->string('ppia');
            $table->unsignedSmallInteger('period');
            $table->unsignedBigInteger('kia_anak_id');
            $table->timestamps();
            $table->foreign('kia_anak_id')->references('id')->on('kia_identitas_anak');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_statement_bayi_baru_lahir');
    }
}
