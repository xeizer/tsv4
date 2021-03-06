<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateF13msTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f13ms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mahasiswam_id');
            $table->integer('f131')->default(0);
            $table->integer('f132')->default(0);
            $table->integer('f133')->default(0);
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
        Schema::dropIfExists('f13ms');
    }
}
