<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStatementBayiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_statement_bayi', function (Blueprint $table) {
            $table->id();
            $table->string('bb');
            $table->string('pb');
            $table->string('lk');
            $table->string('perkembangan');
            $table->string('kie');
            $table->string('imunisasi');
            $table->string('vit_a');
            $table->string('ppia');
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
        Schema::dropIfExists('service_statement_bayi');
    }
}
