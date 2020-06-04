<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Beritainformasim as Beritadaninformasi;
use Session;
use Auth;

class BeritadaninformasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (Auth::user()->roles()->value('name') == 'odin') {
            $semua = Beritadaninformasi::all();
        } else {
            $semua = Beritadaninformasi::where('user_id', Auth::user()->id)->get();
        }


        return view('administrasi.beritadaninformasi.index')->with([
            'sidebaraktif' => 'beritadaninformasi',
            'ket1' => 'Administrasi',
            'ket2' => 'Berita dan Informasi',
            'active' => 'berita',
            'isi' => $semua,
        ]);
    }

    public function detil($id)
    {
        //
        $semua = Beritadaninformasi::find($id);
        return view('depan.detail_berita')->with([
            'sidebaraktif' => 'beritadaninformasi',
            'ket1' => 'Administrasi',
            'ket2' => 'Berita dan Informasi',
            'isi' => $semua,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request['image']) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('gambarberita'), $imageName);
        } else {
            $imageName = 'nogambar.png';
        }
        $isidata = new Beritadaninformasi();
        $isidata->kategori = $request['kategori'];
        $isidata->user_id = Auth::user()->id;
        $isidata->judul = $request['judul'];
        $isidata->gambar = $imageName;
        $isidata->konten = $request['konten'];
        $isidata->save();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil Menyimpan Data"
        ]);
        return redirect()->route('beritadaninformasi.index');
    }

    public function tampil_untuk_edit($id)
    {
        $beritaedit = Beritadaninformasi::find($id);

        return view('administrasi.beritadaninformasi.edit')->with([
            'active1' => 'berita',
            'active2' => 'berita',
            'ket1' => 'berita',
            'ket2' => 'Ubah berita',
            'berita' => $beritaedit,
        ]);
    }

    public function proses_edit(Request $request, $id)
    {
        $this->validate($request, [
            'kategori' => 'required',
            'judul' => 'required',
            'konten' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $berita = Beritadaninformasi::find($id);
        $berita->user_id = Auth::user()->id;
        $berita->judul = $request['judul'];
        $berita->kategori = $request['kategori'];
        if ($request['image']) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('gambarberita'), $imageName);
            $berita->gambar = $imageName;
        }
        $berita->konten = $request['konten'];
        $berita->update();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil Mengubah Berita"
        ]);

        return redirect()->route('beritadaninformasi.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $idnya = Beritadaninformasi::find($id);
        $idnya->delete();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil Menghapus Data"
        ]);
        return redirect()->route('beritadaninformasi.index');
    }
}
