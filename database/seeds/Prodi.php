<?php

use Illuminate\Database\Seeder;
use App\Prodim;

class Prodi extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $p = new Prodim();
        $p->fakultasm_id = '1';
        $p->kd_prodi = 0;
        $p->nama_prodi = 'Semua Prodi';
        $p->slug_prodi = 'Semua Prodi';
        $p->save();

        $p = new Prodim();
        $p->fakultasm_id = '2';
        $p->kd_prodi = 86201;
        $p->nama_prodi = 'Bimbingan Konseling';
        $p->slug_prodi = 'BK';
        $p->save();

        $p = new Prodim();
        $p->fakultasm_id = '2';
        $p->kd_prodi = 87205;
        $p->nama_prodi = 'Pendidikan Pancasila dan Kewarganegaraan';
        $p->slug_prodi = 'PPKN';
        $p->save();

        $p = new Prodim();
        $p->fakultasm_id = '2';
        $p->kd_prodi = 87201;
        $p->nama_prodi = 'Pendidikan Sejarah';
        $p->slug_prodi = 'Sejarah';
        $p->save();

        $p = new Prodim();
        $p->fakultasm_id = '2';
        $p->kd_prodi = 87202;
        $p->nama_prodi = 'Pendidikan Geografi';
        $p->slug_prodi = 'Geografi';
        $p->save();

        $p = new Prodim();
        $p->fakultasm_id = 3;
        $p->kd_prodi = 84202;
        $p->nama_prodi = 'Pendidikan Matematika';
        $p->slug_prodi = 'Matematika';
        $p->save();

        $p = new Prodim();
        $p->fakultasm_id = 3;
        $p->kd_prodi = 84203;
        $p->nama_prodi = 'Pendidikan Fisika';
        $p->slug_prodi = 'Fisika';
        $p->save();

        $p = new Prodim();
        $p->fakultasm_id = 3;
        $p->kd_prodi = 83207;
        $p->nama_prodi = 'Pendidikan Teknologi Informasi dan Komputer';
        $p->slug_prodi = 'PTIK';
        $p->save();

        $p = new Prodim();
        $p->fakultasm_id = 3;
        $p->kd_prodi = 84025;
        $p->nama_prodi = 'Pendidikan Biologi';
        $p->slug_prodi = 'Biologi';
        $p->save();

        $p = new Prodim();
        $p->fakultasm_id = 4;
        $p->kd_prodi = 88201;
        $p->nama_prodi = 'Pendidikan Bahasa dan Sastra Indonesia';
        $p->slug_prodi = 'BI';
        $p->save();

        $p = new Prodim();
        $p->fakultasm_id = 4;
        $p->kd_prodi = 88203;
        $p->nama_prodi = 'Pendidikan Bahasa Inggris';
        $p->slug_prodi = 'English';
        $p->save();

        $p = new Prodim();
        $p->fakultasm_id = 5;
        $p->kd_prodi = 85201;
        $p->nama_prodi = 'Pendidikan Jasmani, Kesehatan dan Rekreasi';
        $p->slug_prodi = 'PJKR';
        $p->save();
    }
}
