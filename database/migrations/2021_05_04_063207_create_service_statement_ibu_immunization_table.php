<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStatementIbuImmunizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_statement_ibu_immunization', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('immunization_id');
            $table->date('date')->nullable();
            $table->string('location')->nullable();
            $table->string('pic')->nullable(); // dokter/bidang/perawat
            $table->unsignedBigInteger('kia_ibu_id');
            $table->smallInteger('trisemester');
            $table->timestamps();
            $table->foreign('kia_ibu_id')->references('id')->on('kia_identitas_ibu');
            $table->foreign('immunization_id')->references('id')->on('immunization');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_statement_ibu_immunization');
    }
}
