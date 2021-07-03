<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnakParamPbUsiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anak_param_pb_usia', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_laki');
            $table->smallInteger('month');
            $table->double('minus_3_sd');
            $table->double('minus_2_sd');
            $table->double('minus_1_sd');
            $table->double('median');
            $table->double('plus_1_sd');
            $table->double('plus_2_sd');
            $table->double('plus_3_sd');
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
        Schema::dropIfExists('anak_param_pb_usia');
    }
}
