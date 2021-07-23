<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipsDanInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tips_dan_info', function (Blueprint $table) {
            $table->id();$table->text('img_url')->nullable();
            $table->string('desc');
            $table->date('date');
            $table->longText('content');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedSmallInteger('tips_category_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('tips_category_id')->references('id')->on('tips_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tips_dan_info');
    }
}
