<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMahasiswamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswams', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique();
            $table->unsignedInteger('prodim_id');
            $table->unsignedInteger('angkatan')->nullable();
            $table->double('ipk', 3, 2)->unsigned()->default(0);
            $table->string('semester_lulus')->default('')->nullable();
            $table->string('tahun_lulus')->nullable();
            $table->integer('durasi_tahun')->unsigned()->nullable()->default(0);
            $table->integer('durasi_bulan')->unsigned()->nullable()->default(0);
            $table->integer('durasi_hari')->unsigned()->nullable()->default(0);
            $table->string('status', 3)->default(0)->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('prodim_id')->references('id')->on('prodims')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mahasiswams');
    }
}
