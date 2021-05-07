<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStatementIbuHamilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_statement_ibu_hamil', function (Blueprint $table) {
            $table->id();
            $table->string('bb');
            $table->string('tb');
            $table->string('imt');
            $table->unsignedSmallInteger('trisemester');
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
        Schema::dropIfExists('service_statement_ibu_hamil');
    }
}
