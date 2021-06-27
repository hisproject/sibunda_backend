<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStatementAnakImmunizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_statement_anak_immunization', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('immunization_id');
            $table->date('date')->nullable();
            $table->string('location')->nullable();
            $table->string('pic')->nullable(); // dokter/bidang/perawat
            $table->string('no_batch')->nullable();
            $table->unsignedBigInteger('kia_anak_id');
            $table->unsignedSmallInteger('month_type'); // tipe bulan e.g 0 - 4, 5 ++ bulan
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
        Schema::dropIfExists('service_statement_anak_immunization');
    }
}
