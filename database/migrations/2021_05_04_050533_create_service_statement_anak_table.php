<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStatementAnakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_statement_anak', function (Blueprint $table) {
            $table->id();$table->string('bb');
            $table->string('pb');
            $table->string('lk');
            $table->string('perkembangan');
            $table->string('kie');
            $table->unsignedSmallInteger('period');
            $table->unsignedBigInteger('kia_anak_id');
            $table->timestamps();
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
        Schema::dropIfExists('service_statement_anak');
    }
}
