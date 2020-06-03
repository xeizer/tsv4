<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Beritainformasim;
use App\Lowonganm;

class DepanController extends Controller
{
    //
    public function index(){
        return view('depan.index')->with([
            'aktif'=>'beranda',
        ]);
    }
    public function informasi(){
        return view('depan.informasi')->with([
            'data'=>Beritainformasim::paginate(3),
            'aktif'=>'informasi',
        ]);
    }
    public function informasi_detil($id)
    {
        $data=Beritainformasim::find($id);
        return view('depan.detil_informasi')->with([
            'data' => $data,
            'aktif' => 'informasi',
        ]);
    }
    public function lowongan()
    {
        return view('depan.lowongan')->with([
            'data' => Lowonganm::paginate(3),
            'aktif' => 'lowongan',
        ]);
    }
    public function lowongan_detil($id)
    {
        $data = Lowonganm::find($id);
        return view('depan.detil_lowongan')->with([
            'data' => $data,
            'aktif' => 'lowongan',
        ]);
    }
}
