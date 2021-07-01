<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStatementAnakMonthlyCheckupTable extends Migration
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
