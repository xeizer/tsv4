<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prodims', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fakultasm_id');
            $table->unsignedInteger('kd_prodi')->unique();
            $table->string('nama_prodi');
            $table->string('slug_prodi');
            $table->timestamps();
            $table->foreign('fakultasm_id')
                ->references('id')
                ->on('fakultasms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prodims');
    }
}
