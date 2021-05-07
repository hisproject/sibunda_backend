<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKiaIdentitasAnakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kia_identitas_anak', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedSmallInteger('anak_ke');
            $table->string('no_akte_kelahiran')->nullable();
            $table->string('nik')->nullable();
            $table->enum('gol_darah', ['A', 'B', 'AB', 'O']);
            $table->unsignedBigInteger('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('no_jkn')->nullable();
            $table->date('tanggal_berlaku_jkn')->nullable();
            $table->string('no_kohort')->nullable();
            $table->string('no_catatan_medik')->nullable();
            $table->timestamps();
            $table->foreign('tempat_lahir')->references('id')->on('kota');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kia_identitas_anak');
    }
}
