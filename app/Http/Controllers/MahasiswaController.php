<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Mahasiswam;
use Auth;
use Session;

class MahasiswaController extends Controller
{
    //
    public function profil(){
        return view('mahasiswa.profil');
    }
    public function profil_simpan(Request $request)
    {
        $this->validate($request, [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nama' => 'required',
            'password' => 'confirmed',
        ], $messages = [
            'nim.required' => 'Username Harus Diisi',
            'nama.required' => 'Nama harus diisi',
            'password.required' => 'Bidang isian Password harus diisi',
            'password.confirmed' => 'Bidang isian Ulangi Password harus sama dengan Bidang isian Password',
        ]);
        $user = User::find(Auth::id());
        if ($request->image) {
            $imageName = Auth::id() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('foto'), $imageName);
            $user->foto = $imageName;
        }
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->name = $request->nama;
        $user->email = $request->email;
        $user->tlp = $request->tlp;
        $user->update();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil Mengubah Profil"
        ]);

        return back();
    }

}
