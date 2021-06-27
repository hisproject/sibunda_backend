<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStatementAnakNeonatusKn1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_statement_anak_neonatus_kn1', function (Blueprint $table) {
            $table->id();
            $table->boolean('q_menyusu')->default(false);
            $table->boolean('q_tali_pusat')->default(false);
            $table->boolean('q_vit_k1')->default(false);
            $table->boolean('q_salep')->default(false);
            $table->boolean('q_imunisasi_hb')->default(false);// cm
            $table->date('date');
            $table->time('time');
            $table->string('no_batch');
            $table->double('bb'); // gr
            $table->double('tb'); // cm
            $table->double('lingkar_kepala');
            $table->boolean('q_skrining_hipotiroid_kongenital')->default(false);
            $table->string('masalah');
            $table->string('dirujuk_ke');
            $table->string('petugas');
            $table->string('catatan_penting');
            $table->unsignedBigInteger('monthly_checkup_id');
            $table->timestamps();
            $table->foreign('monthly_checkup_id')->references('id')->on('service_statement_anak_monthly_checkup');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_statement_anak_neonatus_kn1');
    }
}
