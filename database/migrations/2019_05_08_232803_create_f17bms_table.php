<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateF17bmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f17bms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mahasiswam_id');
            for ($i = 2; $i <= 6; $i = $i + 2) {
                $table->integer('f17' . $i.'b')->nullable();
            }
            $table->integer('f176ba')->nullable();
            for ($i = 8; $i <= 38; $i = $i + 2) {
                $table->integer('f17' . $i . 'b')->nullable();
            }
            $table->integer('f1738ba')->nullable();
            for ($i = 40; $i <= 54; $i = $i + 2) {
                $table->integer('f17' . $i . 'b')->nullable();
            }
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
        Schema::dropIfExists('f17bms');
    }
}
