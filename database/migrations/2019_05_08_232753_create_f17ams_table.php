<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateF17amsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f17ams', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mahasiswam_id');
            for($i=1;$i<=6;$i=$i+2){
                $table->integer('f17'.$i)->nullable();
            }
            $table->integer('f175a')->nullable();
            for ($i = 7; $i <= 38; $i = $i + 2) {
                $table->integer('f17' . $i)->nullable();
            }
            $table->integer('f1737a')->nullable();
            for ($i = 39; $i <= 54; $i = $i + 2) {
                $table->integer('f17' . $i)->nullable();
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
        Schema::dropIfExists('f17ams');
    }
}
