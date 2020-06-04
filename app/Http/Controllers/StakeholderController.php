<?php

namespace App\Http\Controllers;

use App\Prodim;
use App\Stakeholderm;
use Illuminate\Http\Request;

class StakeholderController extends Controller
{
    public function index($prodi, $tahunangkatan, $tahunlulus)
    {
        for ($i = 1; $i <= 7; $i++) {
            for ($r = 1; $r <= 4; $r++) {
                $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($prodi) {
                    if ($prodi > 1) {
                        $q->where('prodim_id', $prodi);
                    }
                })->count();
            }
        }
        if ($prodi > 1) {
            $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($prodi) {
                $q->where('prodim_id', $prodi);
            })->count();
        } else {
            $jumlah = Stakeholderm::count();
        };

        return view('administrasi.stakeholder.index', [
            'data' => $data,
            'jumlah' => $jumlah,
            'prodi' => $prodi,
            'title' => Prodim::find($prodi)->nama_prodi,
        ]);
    }
}
