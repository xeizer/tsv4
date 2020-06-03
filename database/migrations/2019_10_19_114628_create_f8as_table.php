<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateF8asTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f8as', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mahasiswam_id');
            $table->integer('f8a')->nullable();
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
        Schema::dropIfExists('f8as');
    }
}
