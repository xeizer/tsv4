<?php

namespace App\Http\Controllers;

use App\Stakeholderm;
use Illuminate\Http\Request;

class StakeholderController extends Controller
{
    public function index($prodi, $tahunangkatan, $tahunlulus)
    {
        $data = "where('prodim_id', 8)";
        $sh1 = Stakeholderm::where('sh1', 1)->whereHas('mahasiswa', function ($q) use ($data) {
            $q->$data;
        })->get();
        dd($sh1);


        return view('administrasi.stakeholder.index', [
            'data' => $data
        ]);
    }
}
