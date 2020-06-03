<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StakeholderController extends Controller
{
    public function index($prodi, $tahunangkatan, $tahunlulus)
    {
        dd($query);
        if ($prodi > 0) {
            if ($tahunangkatan > 0) {
                if ($tahunlulus > 0) {
                    $query = "where('angkatan', $tahunangkatan)->where('tahun_lulus', $tahunlulus)";
                };
                $query = "";
            };
            $query = "";
        };

        return view('administrasi.stakeholder.index');
    }
}
