<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStatementAnakNeonatusSixHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_statement_anak_neonatus_six_hours', function (Blueprint $table) {
            $table->id();
            $table->double('bb'); // gr
            $table->double('tb'); // cm
            $table->double('lingkar_kepala'); // cm
            $table->boolean('q_imd')->default(false);
            $table->boolean('q_vit_k1')->default(false);
            $table->boolean('q_salep')->default(false);
            $table->boolean('q_imunisasi_hb')->default(false);
            $table->date('date');
            $table->time('time');
            $table->string('no_batch');
            $table->string('dirujuk_ke');
            $table->string('petugas');
            $table->string('catatan_penting');
            $table->unsignedBigInteger('monthly_checkup_id')->unique();
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
        Schema::dropIfExists('service_statement_anak_neonatus_six_hours');
    }
}
