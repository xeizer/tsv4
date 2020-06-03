<?php

use Illuminate\Database\Seeder;
use App\Fakultasm;

class FakultasSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $fakultas = new Fakultasm();
        $fakultas->kd_fakultas = "0";
        $fakultas->nama_fakultas = "Semua Fakultas";
        $fakultas->save();

        $fakultas = new Fakultasm();
        $fakultas->kd_fakultas = "1";
        $fakultas->nama_fakultas = "Fakultas Ilmu Pendidikan dan Pengetahuan Sosial";
        $fakultas->save();

        $fakultas = new Fakultasm();
        $fakultas->kd_fakultas = "2";
        $fakultas->nama_fakultas = "Fakultas Pendidikan MIPA dan Teknologi";
        $fakultas->save();

        $fakultas = new Fakultasm();
        $fakultas->kd_fakultas = "3";
        $fakultas->nama_fakultas = "Fakultas Pendidikan Bahasa dan Seni";
        $fakultas->save();

        $fakultas = new Fakultasm();
        $fakultas->kd_fakultas = "4";
        $fakultas->nama_fakultas = "Fakultas Pendidikan Olah Raga dan Kesehatan";
        $fakultas->save();
    }
}
