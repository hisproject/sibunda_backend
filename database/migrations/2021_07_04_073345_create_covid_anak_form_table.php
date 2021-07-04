<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovidAnakFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid_anak_form', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('q_id');
            $table->unsignedBigInteger('kia_anak_id');
            $table->boolean('ans');
            $table->timestamps();
            $table->foreign('q_id')->references('id')->on('covid_questionnaire');
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
        Schema::dropIfExists('covid_anak_form');
    }
}
