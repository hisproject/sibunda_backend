<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKiaIdentitasAyahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kia_identitas_ayah', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('img_url')->nullable();
            $table->string('nik');
            $table->string('pembiayaan')->nullable();
            $table->string('no_jkn')->nullable();
            $table->string('faskes_tk1')->nullable();
            $table->string('faskes_rujukan')->nullable();
            $table->enum('gol_darah', ['A', 'B', 'AB', 'O'])->nullable();
            $table->unsignedBigInteger('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('alamat_rumah')->nullable();
            $table->string('telp')->nullable();
            $table->unsignedBigInteger('kia_ibu_id');
            $table->timestamps();
            $table->foreign('tempat_lahir')->references('id')->on('kota');
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
        Schema::dropIfExists('kia_identitas_ayah');
    }
}
