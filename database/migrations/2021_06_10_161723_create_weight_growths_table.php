<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeightGrowthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weight_growths', function (Blueprint $table) {
            $table->smallInteger('week')->primary();
            $table->double('bottom_obesity_threshold');
            $table->double('bottom_over_threshold');
            $table->double('bottom_normal_threshold');
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
        Schema::dropIfExists('weight_growths');
    }
}
