<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('kia_anak_id')->nullable();
            $table->unsignedBigInteger('kia_ibu_id')->nullable();
            $table->unsignedBigInteger('kia_ayah_id')->nullable();
            $table->timestamps();
            $table->foreign('kia_anak_id')->references('id')->on('kia_identitas_anak');
            $table->foreign('kia_ibu_id')->references('id')->on('kia_identitas_ibu');
            $table->foreign('kia_ayah_id')->references('id')->on('kia_identitas_ayah');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kia');
    }
}
