<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStatementMonthlyPerkembanganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_statement_monthly_perkembangan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monthly_report_id');
            $table->unsignedBigInteger('questionnaire_id');
            $table->boolean('ans');
            $table->unique(['monthly_report_id', 'questionnaire_id']);
            $table->timestamps();
            $table->foreign('monthly_report_id')->references('id')->on('service_statement_anak_monthly_checkup');
            $table->foreign('questionnaire_id')->references('id')->on('perkembangan_questionnaire');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_statement_monthly_perkembangan');
    }
}
