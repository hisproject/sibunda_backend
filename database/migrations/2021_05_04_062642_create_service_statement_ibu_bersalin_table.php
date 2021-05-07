<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStatementIbuBersalinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_statement_ibu_bersalin', function (Blueprint $table) {
            $table->id();
            $table->string('tp');
            $table->string('fasilitas_kesehatan');
            $table->string('rujukan');
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
        Schema::dropIfExists('service_statement_ibu_bersalin');
    }
}
