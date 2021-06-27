<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ServiceStatementAnakMonthlyCheckupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('service_statement_anak_monthly_checkup', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('year_id');
            $table->date('date');
            $table->string('location');
            $table->string('pemeriksa');
            $table->unsignedSmallInteger('age'); // dalam hari
            $table->double('bb'); // in kg
            $table->double('tb'); // in cm
            $table->double('lingkar_kepala'); // in cm
            $table->double('imt');
            $table->boolean('perkembangan_q1')->default(false);
            $table->boolean('perkembangan_q2')->default(false);
            $table->boolean('perkembangan_q3')->default(false);
            $table->boolean('perkembangan_q4')->default(false);
            $table->boolean('perkembangan_q5')->default(false);
            $table->boolean('perkembangan_q6')->default(false);
            $table->boolean('perkembangan_q7')->default(false);
            $table->boolean('perkembangan_q8')->default(false);
            $table->boolean('perkembangan_q9')->default(false);
            $table->boolean('perkembangan_q10')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('service_statement_anak_monthly_checkup');
    }
}
