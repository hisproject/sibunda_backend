<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovidFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid_form', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_ibu');
            $table->date('date');
            $table->unsignedBigInteger('user_id');
            $table->boolean('result_is_normal')->nullable();
            $table->string('result_desc')->nullable();
            $table->string('result_long_desc')->nullable();
            $table->boolean('result_is_normal')->nullable();
            $table->unsignedBigInteger('kia_anak_id')->nullable();
            $table->text('img_url')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('covid_form');
    }
}
