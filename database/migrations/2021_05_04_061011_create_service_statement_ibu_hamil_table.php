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
            $table->text('img_url')->nullable();
            $table->unsignedSmallInteger('trisemester');
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
        Schema::dropIfExists('service_statement_ibu_hamil');
    }
}
