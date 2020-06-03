<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateF10msTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f10ms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mahasiswam_id');
            $table->integer('f101')->default(0);
            $table->string('f102')->nullable();
            $table->timestamps();
            $table->foreign('mahasiswam_id')->references('id')->on('mahasiswams')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('f10ms');
    }
}
