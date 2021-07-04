<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovidIbuFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid_ibu_form', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('q_id');
            $table->unsignedBigInteger('kia_ibu_id');
            $table->boolean('ans');
            $table->timestamps();
            $table->foreign('q_id')->references('id')->on('covid_questionnaire');
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
        Schema::dropIfExists('covid_ibu_form');
    }
}
