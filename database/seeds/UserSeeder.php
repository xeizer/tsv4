<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use App\Adminm;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $odinRole = new Role();
        $odinRole->name = "odin";
        $odinRole->display_name = "The Boss of Administrator";
        $odinRole->save();

        $dekanRole = new Role();
        $dekanRole->name = "dekan";
        $dekanRole->display_name = "Dekan";
        $dekanRole->save();

        $rektorRole = new Role();
        $rektorRole->name = "rektor";
        $rektorRole->display_name = "Rektor";
        $rektorRole->save();

        $rektorRole = new Role();
        $rektorRole->name = "humas";
        $rektorRole->display_name = "Hubungan Masyarakat";
        $rektorRole->save();

        $adminRole = new Role();
        $adminRole->name = "admin";
        $adminRole->display_name = "Admin";
        $adminRole->save();
        // Membuat role member
        $stakeRole = new Role();
        $stakeRole->name = "stakeholder";
        $stakeRole->display_name = "Stakeholder";
        $stakeRole->save();

        $mahasisRole = new Role();
        $mahasisRole->name = "mahasiswa";
        $mahasisRole->display_name = "Mahasiswa";
        $mahasisRole->save();
        // Membuat role member

        // Membuat sample admin
        $odin = new User();
        $odin->name = 'ODIN';
        $odin->nim = 'ODIN@valhalla';
        $odin->email = 'odin@valhalla.com';
        $odin->password = bcrypt('rahasia');
        $odin->save();
        $odin->attachRole($odinRole);
        $odin = new Adminm();
        $odin->user_id='1';
        $odin->prodim_id='1';
        $odin->save();

        $dekan = new User();
        $dekan->name = 'dekan';
        $dekan->nim = 'dekan@valhalla';
        $dekan->email = 'dekan@valhalla.com';
        $dekan->password = bcrypt('rahasia');
        $dekan->save();
        $dekan->attachRole($dekanRole);
        $dekan = new Adminm();
        $dekan->user_id = '2';
        $dekan->prodim_id = '2';
        $dekan->save();

        $prodi = new User();
        $prodi->name = 'prodi';
        $prodi->nim = 'prodi@valhalla';
        $prodi->email = 'prodi@valhalla.com';
        $prodi->password = bcrypt('rahasia');
        $prodi->save();
        $prodi->attachRole($adminRole);
        $prodi = new Adminm();
        $prodi->user_id = '3';
        $prodi->prodim_id = '8';
        $prodi->save();
        // Membuat sample member



        ///seed untuk list f17
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Pengetahuan dibidang atau disiplin ilmu Anda'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Pengetahuan diluar bidang atau disiplin ilmu Anda'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Pengetahuan Umum'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Bahasa Inggris'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Keterampilan Internet'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Keterampilan Komputer'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Berfikir Kritis'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Keterampilan Riset'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Kemampuan Belajar'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Kemampuan Berkomunikasi'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Bekerja dibawah Tekanan'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Manajemen Waktu'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Bekerja secara mandiri'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Bekerja dalam Tim/bekerja sama denga orang lain'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Kemampuan dalam menyelesaikan masalah'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Negoisasi'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Kemampuan Analisis'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Toleransi'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Kemampuan Adaptasi'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Loyalitas'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Integritas'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Bekerja dengan orang yang berbeda budaya maupun latar belakang'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Kepemimpinan'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Kemampuan dalam memegang tanggung jawab'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Inisiatif'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Manajemen proyek/program'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Kemamuan untuk mempresentasikan ide/produk/laporan'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Kemampuan dalam menulis laporan, memo, dan dokumen'
        ]);
        DB::table('listf17ms')->insert([
            'keterangan'    =>    'Kemampuan untuk terus belajar sepanjang hayat'
        ]);
    }
}
