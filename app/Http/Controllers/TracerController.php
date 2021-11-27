<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;
use App\User;
use App\Mahasiswam;
use App\F2m;
use App\F3m;
use App\F4m;
use App\F5m;
use App\F6m;
use App\F7m;
use App\F7am;
use App\F8m;
use App\F8a;
use App\F9m;
use App\F10m;
use App\F11m;
use App\F12m;
use App\F13m;
use App\F14m;
use App\F15m;
use App\F16m;
use App\F17am;
use App\F17bm;
use App\Listf17m;
use App\Stakeholderm;

class TracerController extends Controller
{
    //
    public function resetTracer()
    {
        $mahasiswa_id = Auth::user()->mahasiswa->id;
        F2m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F3m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F4m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F5m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F6m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F7m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F7am::where('mahasiswam_id', $mahasiswa_id)->delete();
        F8m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F8a::where('mahasiswam_id', $mahasiswa_id)->delete();
        F9m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F10m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F11m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F12m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F13m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F14m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F15m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F16m::where('mahasiswam_id', $mahasiswa_id)->delete();
        F17am::where('mahasiswam_id', $mahasiswa_id)->delete();
        F17bm::where('mahasiswam_id', $mahasiswa_id)->delete();
        $hapussh = Stakeholderm::where('mahasiswam_id', $mahasiswa_id)->first;
        $usersh = $hapussh->user_id;
        $hapussh->delete();
        User::where('id', $usersh)->delete();
        Mahasiswam::find($mahasiswa_id)->update(['status' => 2]);
        return view('depan.tracer.f2')->with([
            'aktif' => 'tracer',
        ]);
    }
    public function tampilf1()
    {
        if (Auth::user()->mahasiswa->status != 1) {
            $next = Auth::user()->mahasiswa->status;
            return redirect()->route('tracer.f' . $next);
        } else {
            return view('depan.tracer.f1')->with([
                'aktif' => 'tracer',
            ]);
        }
    }
    public function simpanf1(Request $req)
    {
        $f1 = User::find(Auth::id());
        $f1->tlp = $req->tlp;
        $f1->email = $req->email;
        $f1->update();
        $mahasiswa = Mahasiswam::find(Auth::user()->mahasiswa->id);
        $mahasiswa->status = 2;
        $mahasiswa->update();
        return redirect()->route('tracer.f2');
    }
    public function tampilf2()
    {
        if (Auth::user()->mahasiswa->status != 2) {
            $next = Auth::user()->mahasiswa->status;
            return redirect()->route('tracer.f' . $next);
        } else {
            return view('depan.tracer.f2')->with([
                'aktif' => 'tracer',
            ]);
        }
    }
    public function simpanf2(Request $req)
    {
        $this->validate($req, [
            'f21' => 'required',
            'f22' => 'required',
            'f23' => 'required',
            'f24' => 'required',
            'f25' => 'required',
            'f26' => 'required',
            'f27' => 'required',
        ], $message = [
            'f21.required' => 'Anda harus memilih salah satu pilihan pada pertanyaan Perkuliahan ',
            'f22.required' => 'Anda harus memilih salah satu pilihan pada pertanyaan Demonstrasi',
            'f23.required' => 'Anda harus memilih salah satu pilihan pada pertanyaan Partisipasi dalam Riset',
            'f24.required' => 'Anda harus memilih salah satu pilihan pada pertanyaan Magang',
            'f25.required' => 'Anda harus memilih salah satu pilihan pada pertanyaan Praktikum',
            'f26.required' => 'Anda harus memilih salah satu pilihan pada pertanyaan Kerja Lapangan',
            'f27.required' => 'Anda harus memilih salah satu pilihan pada pertanyaan Diskusi',

        ]);
        $f2 = F2m::updateOrCreate(
            ['mahasiswam_id' => Auth::user()->mahasiswa->id],
            [
                'f21' => $req->f21,
                'f22' => $req->f22,
                'f23' => $req->f23,
                'f24' => $req->f24,
                'f25' => $req->f25,
                'f26' => $req->f26,
                'f27' => $req->f27,
            ]
        );

        $mahasiswa = Mahasiswam::find(Auth::user()->mahasiswa->id);
        $mahasiswa->status = 3;
        $mahasiswa->update();
        return redirect()->route('tracer.f3');
    }
    public function tampilf3()
    {
        if (Auth::user()->mahasiswa->status != 3) {
            $next = Auth::user()->mahasiswa->status;
            return redirect()->route('tracer.f' . $next);
        } else {
            return view('depan.tracer.f3')->with([
                'aktif' => 'tracer',
            ]);
        }
    }
    public function simpanf3(Request $req)
    {
        $this->validate($req, [
            'f301' => 'required',
            'f302' => 'required',
        ], $message = [
            'f301.required' => 'Anda harus memilih salah satu pilihan ',
            'f302.required' => 'Anda harus mengisi jumlah bulan',

        ]);
        if (F3m::where('mahasiswam_id', Auth::user()->mahasiswa->id)->first()) {
            $f3 = F3m::where('mahasiswam_id', Auth::user()->mahasiswa->id)->first();
            $f3->mahasiswam_id = Auth::user()->mahasiswa->id;
            $f3->f301 = $req->f301;
            if ($req->f301 == 1) {
                $f3->f302 = $req->f302;
            } elseif ($req->f301 == 2) {
                $f3->f303 = $req->f302;
            }
            $f3->save();
        } else {
            $f3 = new F3m();
            $f3->mahasiswam_id = Auth::user()->mahasiswa->id;
            $f3->f301 = $req->f301;
            if ($req->f301 == 1) {
                $f3->f302 = $req->f302;
            } elseif ($req->f301 == 2) {
                $f3->f303 = $req->f302;
            }
            $f3->save();
        }

        $mahasiswa = Mahasiswam::find(Auth::user()->mahasiswa->id);
        if ($req->f301 == 3) {
            $mahasiswa->status = 8;
            $mahasiswa->update();
            $f4 = new F4m();
            $f4->mahasiswam_id = Auth::user()->mahasiswa->id;
            $f4->save();
            $f5 = new F5m();
            $f5->mahasiswam_id = Auth::user()->mahasiswa->id;
            $f5->save();
            //////////////////////////////////
            $f6 = new F6m();
            $f6->mahasiswam_id = Auth::user()->mahasiswa->id;
            $f6->save();
            /////////////////////////////////
            $f7 = new F7m();
            $f7->mahasiswam_id = Auth::user()->mahasiswa->id;
            $f7->save();
            /////////////////////////////////
            $f7a = new F7am();
            $f7a->mahasiswam_id = Auth::user()->mahasiswa->id;
            $f7a->save();

            return redirect()->route('tracer.f8');
        } else {
            $mahasiswa->status = 4;
            $mahasiswa->update();
            return redirect()->route('tracer.f4');
        }
    }
    public function tampilf4()
    {
        if (Auth::user()->mahasiswa->status != 4) {
            $next = Auth::user()->mahasiswa->status;
            return redirect()->route('tracer.f' . $next);
        } else {
            return view('depan.tracer.f4')->with([
                'aktif' => 'tracer',
            ]);
        }
    }
    public function simpanf4(Request $req)
    {

        if (count(Input::all()) <= 2) {
            return back()->withErrors(['f4' => 'Mohon untuk memilih salah satu']);
        }
        for ($i = 1; $i <= 15; $i++) {
            $data = 'f4' . $i;
            if (isset($req->$data)) {
                $s[$i] = $req->$data;
            } else {
                $s[$i] = 0;
            }
        }
        $f4 = F4m::updateOrCreate(
            ['mahasiswam_id' => Auth::user()->mahasiswa->id],
            [
                'f41' => $s[1],
                'f42' => $s[2],
                'f43' => $s[3],
                'f44' => $s[4],
                'f45' => $s[5],
                'f46' => $s[6],
                'f47' => $s[7],
                'f48' => $s[8],
                'f49' => $s[9],
                'f410' => $s[10],
                'f411' => $s[11],
                'f412' => $s[12],
                'f413' => $s[13],
                'f414' => $s[14],
                'f415' => $s[15],
                'f416' => $req->f416,
            ]
        );

        $mahasiswa = Mahasiswam::find(Auth::user()->mahasiswa->id);
        $mahasiswa->status = 5;
        $mahasiswa->update();
        return redirect()->route('tracer.f5');
    }
    public function tampilf5()
    {
        //f5,f6,f7,f7a
        if (Auth::user()->mahasiswa->status != 5) {
            $next = Auth::user()->mahasiswa->status;
            return redirect()->route('tracer.f' . $next);
        } else {
            return view('depan.tracer.f5')->with([
                'aktif' => 'tracer',
            ]);
        }
    }
    public function simpanf5(Request $req)
    {
        $this->validate($req, [
            'f502' => 'required|integer',
            'f501' => 'required',
            'f6' => 'required|integer',
            'f7' => 'required|integer',
            'f7a' => 'required|integer',

        ], $message = [
            'f502.required' => 'Mohon untuk mengisi waktu mendapat keperjaan pertama',
            'f502.integer' => 'Isian waktu memperoleh pekerjaan pertama hanya boleh angka bulat',
            'f501.required' => 'Mohon untuk memilih antara Sebelum Lulus atau Setelah Lulus',
            'f6.required' => 'Isian perusahaan yang dilamar dibutuhkan',
            'f7.required' => 'Isian perusahaan yang merespon dibutuhkan',
            'f7a.required' => 'Isian perusahaan yang mewawancarai Anda dibutuhkan',
            'f6.integer' => 'Isian perusahaan yang dilamar, hanya boleh angka bulat',
            'f7.integer' => 'Isian perusahaan yang merespon , hanya boleh angka bulat',
            'f7a.integer' => 'Isian perusahaan yang mewawancarai Anda , hanya boleh angka bulat',


        ]);
        if (F5m::where('mahasiswam_id', Auth::user()->mahasiswa->id)->first()) {
            $f5 = F5m::where('mahasiswam_id', Auth::user()->mahasiswa->id)->first();
        } else {
            $f5 = new F5m();
        }
        $f5->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f5->f501 = $req->f501;
        if ($req->f501 == 1) {
            $f5->f502 = $req->f502;
        } elseif ($req->f501 == 2) {
            $f5->f503 = $req->f502;
        }
        $f5->save();
        //////////////////////////////////
        if (F6m::where('mahasiswam_id', Auth::user()->mahasiswa->id)->first()) {
            $f6 = F6m::where('mahasiswam_id', Auth::user()->mahasiswa->id)->first();
        } else {
            $f6 = new F6m();
        }
        $f6->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f6->f6 = $req->f6;
        $f6->save();
        /////////////////////////////////
        if (F7m::where('mahasiswam_id', Auth::user()->mahasiswa->id)->first()) {
            $f7 = F7m::where('mahasiswam_id', Auth::user()->mahasiswa->id)->first();
        } else {
            $f7 = new F7m();
        }
        $f7->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f7->f7 = $req->f7;
        $f7->save();
        /////////////////////////////////
        if (F7am::where('mahasiswam_id', Auth::user()->mahasiswa->id)->first()) {
            $f7a = F7am::where('mahasiswam_id', Auth::user()->mahasiswa->id)->first();
        } else {
            $f7a = new F7am();
        }
        $f7a->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f7a->f7a = $req->f7a;
        $f7a->save();
        /////////////////////////////////
        $mahasiswa = Mahasiswam::find(Auth::user()->mahasiswa->id);
        $mahasiswa->status = 8;
        $mahasiswa->update();
        return redirect()->route('tracer.f8');
    }
    public function tampilf8()
    {
        if (Auth::user()->mahasiswa->status != 8) {
            $next = Auth::user()->mahasiswa->status;
            return redirect()->route('tracer.f' . $next);
        } else {
            return view('depan.tracer.f8')->with([
                'aktif' => 'tracer',
            ]);
        }
    }
    public function simpanf8(Request $req)
    {
        $this->validate($req, [
            'f8' => 'required|integer',

        ], $message = [
            'f8.required' => 'Mohon untuk memilih salah satu pilihan',
            'f8.integer' => 'Terdapat kesalahan. mohon ulangi',
        ]);
        //////////////////////////////////
        $f8 = F8m::updateOrCreate(
            ['mahasiswam_id' => Auth::user()->mahasiswa->id],
            ['f8' => $req->f8]
        );
        if ($req->f8 == 1) {
            $user = User::updateOrCreate(
                ['nim' => 'SH' . Auth::user()->nim, 'name' => 'Stakeholder'],
                ['password' => bcrypt('1234')]
            );
            $user->detachRole('stakeholder');
            $user->attachRole('stakeholder');
            $stake = Stakeholderm::updateOrCreate(
                ['user_id' => User::where('nim', 'SH' . Auth::user()->nim)->first()->id],
                ['mahasiswam_id' => Auth::user()->mahasiswa->id]
            );
            //dd($req);
            $f8a = F8a::updateOrCreate(
                ['mahasiswam_id' => Auth::user()->mahasiswa->id],
                ['f8a' => $req->f8A]
            );
        }
        /////////////////////////////////
        $mahasiswa = Mahasiswam::find(Auth::user()->mahasiswa->id);
        $mahasiswa->status = 9;
        $mahasiswa->update();
        return redirect()->route('tracer.f9');
    }
    public function tampilf9()
    {
        if (Auth::user()->mahasiswa->status != 9) {
            $next = Auth::user()->mahasiswa->status;
            return redirect()->route('tracer.f' . $next);
        } else {
            return view('depan.tracer.f9')->with([
                'aktif' => 'tracer',
            ]);
        }
    }
    public function simpanf9(Request $req)
    {

        if (count(Input::all()) <= 2) {
            return back()->withErrors(['f9' => 'Mohon untuk memilih salah satu']);
        }
        for ($i = 1; $i <= 5; $i++) {
            $data = 'f9' . $i;
            if (isset($req->$data)) {
                $s[$i] = $req->$data;
            } else {
                $s[$i] = 0;
            }
        }

        //////////////////////////////////
        F9m::updateOrCreate(
            ['mahasiswam_id' => Auth::user()->mahasiswa->id],
            [
                'f91' => $s[1],
                'f92' => $s[2],
                'f93' => $s[3],
                'f94' => $s[4],
                'f95' => $s[5],
                'f96' => $req->f96,
            ]
        );
        /////////////////////////////////
        $mahasiswa = Mahasiswam::find(Auth::user()->mahasiswa->id);
        $mahasiswa->status = 10;
        $mahasiswa->update();
        return redirect()->route('tracer.f10');
    }
    public function tampilf10()
    {
        if (Auth::user()->mahasiswa->status != 10) {
            $next = Auth::user()->mahasiswa->status;
            return redirect()->route('tracer.f' . $next);
        } else {
            return view('depan.tracer.f10')->with([
                'aktif' => 'tracer',
            ]);
        }
    }
    public function simpanf10(Request $req)
    {
        $this->validate($req, [
            'f101' => 'required|integer',

        ], $message = [
            'f101.required' => 'Mohon untuk memilih salah satu pilihan',
            'f101.integer' => 'Terdapat kesalahan. mohon ulangi',
        ]);


        //////////////////////////////////
        F10m::updateOrCreate(
            ['mahasiswam_id' => Auth::user()->mahasiswa->id],
            [
                'f101' => $req->f101,
                'f102' => $req->f102,
            ]
        );
        /////////////////////////////////
        $mahasiswa = Mahasiswam::find(Auth::user()->mahasiswa->id);
        $mahasiswa->status = 11;
        $mahasiswa->update();
        return redirect()->route('tracer.f11');
    }
    //f11,f12,f13,f14,f15
    public function tampilf11()
    {
        if (Auth::user()->mahasiswa->status != 11) {
            $next = Auth::user()->mahasiswa->status;
            return redirect()->route('tracer.f' . $next);
        } else {
            return view('depan.tracer.f11')->with([
                'aktif' => 'tracer',
            ]);
        }
    }
    public function simpanf11(Request $req)
    {
        $this->validate($req, [
            'f111' => 'required|integer',
            'f121' => 'required|integer',
            'f131' => 'required|integer',
            'f132' => 'required|integer',
            'f133' => 'required|integer',
            'f14' => 'required|integer',
            'f15' => 'required|integer',

        ], $message = [
            'f111.required' => 'Mohon untuk memilih salah satu pilihan',
            'f111.integer' => 'Terdapat kesalahan. mohon ulangi',
            'f121.required' => 'Mohon untuk memilih salah satu pilihan',
            'f121.integer' => 'Terdapat kesalahan. mohon ulangi',
            'f131.required' => 'Mohon untuk memilih salah satu pilihan',
            'f131.integer' => 'Terdapat kesalahan. mohon ulangi',
            'f132.required' => 'Mohon untuk memilih salah satu pilihan',
            'f132.integer' => 'Terdapat kesalahan. mohon ulangi',
            'f133.required' => 'Mohon untuk memilih salah satu pilihan',
            'f133.integer' => 'Terdapat kesalahan. mohon ulangi',
            'f14.required' => 'Mohon untuk memilih salah satu pilihan',
            'f14.integer' => 'Terdapat kesalahan. mohon ulangi',
            'f15.required' => 'Mohon untuk memilih salah satu pilihan',
            'f15.integer' => 'Terdapat kesalahan. mohon ulangi',
        ]);


        //////////////////////////////////
        $f11 = F11m::updateOrCreate(
            ['mahasiswam_id' => Auth::user()->mahasiswa->id],
            [
                'f111' => $req->f111,
                'f112' => $req->f112,
            ]
        );

        //////////////////////////////////
        $f12 = F12m::updateOrCreate(
            ['mahasiswam_id' => Auth::user()->mahasiswa->id],
            ['f121' => $req->f121, 'f122' => $req->f122,]
        );
        //////////////////////////////////
        $f13 = F13m::updateOrCreate(
            ['mahasiswam_id' => Auth::user()->mahasiswa->id],
            [
                'f131' => $req->f131,
                'f132' => $req->f132,
                'f133' => $req->f133,
            ]
        );

        //////////////////////////////////
        $f14 = F14m::updateOrCreate(
            ['mahasiswam_id' => Auth::user()->mahasiswa->id],
            ['f14' => $req->f14,]
        );

        //////////////////////////////////
        $f15 = F15m::updateOrCreate(
            ['mahasiswam_id' => Auth::user()->mahasiswa->id],
            ['f15' => $req->f15,]
        );

        /////////////////////////////////
        $mahasiswa = Mahasiswam::find(Auth::user()->mahasiswa->id);
        $mahasiswa->status = 16;
        $mahasiswa->update();
        return redirect()->route('tracer.f16');
    }
    public function tampilf16()
    {
        if (Auth::user()->mahasiswa->status != 16) {
            $next = Auth::user()->mahasiswa->status;
            return redirect()->route('tracer.f' . $next);
        } else {
            return view('depan.tracer.f16')->with([
                'aktif' => 'tracer',
            ]);
        }
    }
    public function simpanf16(Request $req)
    {

        if (count(Input::all()) <= 2) {
            return back()->withErrors(['f16' => 'Mohon untuk memilih salah satu']);
        }
        for ($i = 1; $i <= 13; $i++) {
            $data = 'f16' . $i;
            if (isset($req->$data)) {
                $s[$i] = $req->$data;
            } else {
                $s[$i] = 0;
            }
        }


        //////////////////////////////////
        $f16 = F16m::updateOrCreate(
            ['mahasiswam_id' => Auth::user()->mahasiswa->id],
            [
                'f161' => $s[1],
                'f162' => $s[2],
                'f163' => $s[3],
                'f164' => $s[4],
                'f165' => $s[5],
                'f166' => $s[6],
                'f167' => $s[7],
                'f168' => $s[8],
                'f169' => $s[9],
                'f1610' => $s[10],
                'f1611' => $s[11],
                'f1612' => $s[12],
                'f1613' => $s[13],
                'f1614' => $req->f1614
            ]
        );

        /////////////////////////////////
        $mahasiswa = Mahasiswam::find(Auth::user()->mahasiswa->id);
        $mahasiswa->status = 17;
        $mahasiswa->update();
        return redirect()->route('tracer.f17');
    }
    public function tampilf17()
    {
        if (Auth::user()->mahasiswa->status != 17) {
            $next = Auth::user()->mahasiswa->status;
            return redirect()->route('tracer.f' . $next);
        } else {
            return view('depan.tracer.f17')->with([
                'aktif' => 'tracer',
                'keterangan' => Listf17m::select('keterangan')->get(),
            ]);
        }
    }
    public function simpanf17(Request $req)
    {
        if (count(Input::all()) < 58) {
            return back()->withErrors(['f17' => 'Mohon untuk melengkapi isian']);
        }
        //////////////////////////////////
        $f17a = F17am::updateOrCreate(
            ['mahasiswam_id' => Auth::user()->mahasiswa->id],
            [
                'f171'    =>    $req->f17a1,
                'f173'    =>    $req->f17a2,
                'f175'    =>    $req->f17a3,
                'f175a'    =>    $req->f17a4,
                'f177'    =>    $req->f17a5,
                'f179'    =>    $req->f17a6,
                'f1711'    =>    $req->f17a7,
                'f1713'    =>    $req->f17a8,
                'f1715'    =>    $req->f17a9,
                'f1717'    =>    $req->f17a10,
                'f1719'    =>    $req->f17a11,
                'f1721'    =>    $req->f17a12,
                'f1723'    =>    $req->f17a13,
                'f1725'    =>    $req->f17a14,
                'f1727'    =>    $req->f17a15,
                'f1729'    =>    $req->f17a16,
                'f1731'    =>    $req->f17a17,
                'f1733'    =>    $req->f17a18,
                'f1735'    =>    $req->f17a19,
                'f1737'    =>    $req->f17a20,
                'f1737a'    =>    $req->f17a21,
                'f1739'    =>    $req->f17a22,
                'f1741'    =>    $req->f17a23,
                'f1743'    =>    $req->f17a24,
                'f1745'    =>    $req->f17a25,
                'f1747'    =>    $req->f17a26,
                'f1749'    =>    $req->f17a27,
                'f1751'    =>    $req->f17a28,
                'f1753'    =>    $req->f17a29,
            ]
        );

        /////////////////////////////////
        $f17b = F17bm::updateOrCreate(
            ['mahasiswam_id' => Auth::user()->mahasiswa->id],
            [
                'f172b'   =>    $req->f17b1,
                'f174b'   =>    $req->f17b2,
                'f176b'   =>    $req->f17b3,
                'f176ba'   =>    $req->f17b4,
                'f178b'   =>    $req->f17b5,
                'f1710b'   =>    $req->f17b6,
                'f1712b'   =>    $req->f17b7,
                'f1714b'   =>    $req->f17b8,
                'f1716b'   =>    $req->f17b9,
                'f1718b'   =>    $req->f17b10,
                'f1720b'   =>    $req->f17b11,
                'f1722b'   =>    $req->f17b12,
                'f1724b'   =>    $req->f17b13,
                'f1726b'   =>    $req->f17b14,
                'f1728b'   =>    $req->f17b15,
                'f1730b'   =>    $req->f17b16,
                'f1732b'   =>    $req->f17b17,
                'f1734b'   =>    $req->f17b18,
                'f1736b'   =>    $req->f17b19,
                'f1738b'   =>    $req->f17b20,
                'f1738ba'   =>    $req->f17b21,
                'f1740b'   =>    $req->f17b22,
                'f1742b'   =>    $req->f17b23,
                'f1744b'   =>    $req->f17b24,
                'f1746b'   =>    $req->f17b25,
                'f1748b'   =>    $req->f17b26,
                'f1750b'   =>    $req->f17b27,
                'f1752b'   =>    $req->f17b28,
                'f1754b'   =>    $req->f17b29,
            ]
        );
        $f17b->save();
        /////////////////////////////////
        $mahasiswa = Mahasiswam::find(Auth::user()->mahasiswa->id);
        $mahasiswa->status = 99;
        $mahasiswa->update();
        return redirect()->route('tracer.f99');
    }
    public function tampilf99()
    {
        if (Auth::user()->mahasiswa->status != 99) {
            $next = Auth::user()->mahasiswa->status;
            return redirect()->route('tracer.f' . $next);
        } else {
            return view('depan.tracer.kuisioner_selesai')->with([
                'aktif' => 'tracer',
            ]);
        }
    }
    public function tampilstakeholder()
    {
        if (Stakeholderm::where('user_id', Auth::id())->first()->status == 1) {
            return redirect()->route('tracer.stakeholder.selesai');
        }
        return view('depan.tracer.feedback1')->with([
            'aktif' => 'stakeholder',
        ]);
    }
    public function simpanstakeholder(Request $req)
    {
        $this->validate($req, [
            'nama' => 'required',
            'jabatan' => 'required',
            'hp' => 'required',
            'pekerjaan' => 'required',
            'instansi' => 'required',
            'alamat' => 'required',
            'jabatanalumni' => 'required',
            'sh1' => 'required',
            'sh2' => 'required',
            'sh3' => 'required',
            'sh4' => 'required',
            'sh5' => 'required',
            'sh6' => 'required',
            'sh7' => 'required',
        ], $message = [
            'sh1.required' => 'Mohon untuk memilih salah satu pada Etika',
            'sh2.required' => 'Mohon untuk memilih salah satu pada Kehlian Bidang Ilmu',
            'sh3.required' => 'Mohon untuk memilih salah satu pada Kemamouan Bahasa Asing',
            'sh4.required' => 'Mohon untuk memilih salah satu pada Penggunaan Teknologi Informasi',
            'sh5.required' => 'Mohon untuk memilih salah satu pada Kemampuan Berkomunikasi',
            'sh6.required' => 'Mohon untuk memilih salah satu pada Kerjasama',
            'sh7.required' => 'Mohon untuk memilih salah satu pada Pengembangan Diri',
        ]);
        $stake = Stakeholderm::where('user_id', Auth::id())->first();
        $stake->jabatan = $req->jabatan;
        $stake->pekerjaan = $req->pekerjaan;
        $stake->instansi = $req->instansi;
        $stake->alamat = $req->alamat;
        $stake->fax = $req->fax;
        $stake->email = $req->email;
        $stake->jabatanalumni = $req->jabatanalumni;
        $stake->sh1 = $req->sh1;
        $stake->sh2 = $req->sh2;
        $stake->sh3 = $req->sh3;
        $stake->sh4 = $req->sh4;
        $stake->sh5 = $req->sh5;
        $stake->sh6 = $req->sh6;
        $stake->sh7 = $req->sh7;
        $stake->status = 1;
        $stake->update();
        $user = User::find(Auth::id());
        $user->name = $req->nama;
        $user->email = $req->email;
        $user->tlp = $req->hp;
        $user->update();
        return redirect()->route('tracer.stakeholder.selesai');
    }
    public function selesaistakeholder(Request $req)
    {
        if (Stakeholderm::where('user_id', Auth::id())->first()->status == 0) {
            return redirect()->route('tracer.stakeholder');
        }
        return view('depan.tracer.kuisioner_selesai')->with([
            'aktif' => 'stakeholder',
        ]);
    }
}
