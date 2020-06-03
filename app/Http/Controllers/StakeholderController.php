<?php

namespace App\Http\Controllers;

use App\Stakeholderm;
use Illuminate\Http\Request;

class StakeholderController extends Controller
{
    public function index($prodi, $tahunangkatan, $tahunlulus)
    {

        $sh1 = Stakeholderm::where('sh1', 1)->whereHas('mahasiswa', function ($q) {
        });
        dd($data);


        return view('administrasi.stakeholder.index', [
            'data' => $data
        ]);
    }
}
