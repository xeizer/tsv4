<?php

namespace App\Http\Controllers;

use App\Stakeholderm;
use Illuminate\Http\Request;

class StakeholderController extends Controller
{
    public function index($prodi, $tahunangkatan, $tahunlulus)
    {
        for ($i = 1; $i >= 7; $i++) {
            $sh . $i = Stakeholderm::where('sh1',)->whereHas('mahasiswa', function ($q) use ($prodi) {
                if ($prodi > 0) {
                    $q->where('prodim_id', $prodi);
                }
            })->get();
        }

        dd($sh1);


        return view('administrasi.stakeholder.index', [
            'data' => $data
        ]);
    }
}
