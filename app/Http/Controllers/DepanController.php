<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Beritainformasim as Beritadaninformasi;

class DepanController extends Controller
{
    //
    public function index()
    {
        $bil = Beritadaninformasi::latest()->paginate(2);
        return view('depan.index')->with([
            'aktif' => 'beranda',
            'bil' => $bil,
            'title' => 'Berita, Informasi dan Lowongan'
        ]);
    }
    public function bil($kategori)
    {
        return view('depan.index')->with([
            'bil' => Beritadaninformasi::where('kategori', $kategori)->paginate(3),
            'aktif' => $kategori,
            'title' => $kategori,
        ]);
    }
    public function detilBil($id)
    {
        return view('depan.detil_bil', [
            'data' => Beritadaninformasi::find($id),
        ]);
    }
    public function alumni()
    {
        return view('depan.alumni');
    }
    public function informasi_detil($id)
    {
        $data = Beritainformasim::find($id);
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
