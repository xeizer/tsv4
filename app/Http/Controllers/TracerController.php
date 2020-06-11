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
        F2m::destroy($mahasiswa_id);
        F3m::destroy($mahasiswa_id);
        F4m::destroy($mahasiswa_id);
        F5m::destroy($mahasiswa_id);
        F6m::destroy($mahasiswa_id);
        F7m::destroy($mahasiswa_id);
        F7am::destroy($mahasiswa_id);
        F8m::destroy($mahasiswa_id);
        F8a::destroy($mahasiswa_id);
        F9m::destroy($mahasiswa_id);
        F10m::destroy($mahasiswa_id);
        F11m::destroy($mahasiswa_id);
        F12m::destroy($mahasiswa_id);
        F13m::destroy($mahasiswa_id);
        F14m::destroy($mahasiswa_id);
        F15m::destroy($mahasiswa_id);
        F16m::destroy($mahasiswa_id);
        F17am::destroy($mahasiswa_id);
        F17bm::destroy($mahasiswa_id);
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
        $f2 = new F2m();
        $f2->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f2->f21 = $req->f21;
        $f2->f22 = $req->f22;
        $f2->f23 = $req->f23;
        $f2->f24 = $req->f24;
        $f2->f25 = $req->f25;
        $f2->f26 = $req->f26;
        $f2->f27 = $req->f27;
        $f2->save();
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
        $f3 = new F3m();
        $f3->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f3->f301 = $req->f301;
        if ($req->f301 == 1) {
            $f3->f302 = $req->f302;
        } elseif ($req->f301 == 2) {
            $f3->f303 = $req->f302;
        }
        $f3->save();
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
        if (F4m::where('mahasiswam_id', Auth::user()->mahasiswa->id)->first()) {
            $f4 = F4m::where('mahasiswam_id', Auth::user()->mahasiswa->id);
        } else {
            $f4 = new F4m();
        }
        $f4->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f4->f41 = $s[1];
        $f4->f42 = $s[2];
        $f4->f43 = $s[3];
        $f4->f44 = $s[4];
        $f4->f45 = $s[5];
        $f4->f46 = $s[6];
        $f4->f47 = $s[7];
        $f4->f48 = $s[8];
        $f4->f49 = $s[9];
        $f4->f410 = $s[10];
        $f4->f411 = $s[11];
        $f4->f412 = $s[12];
        $f4->f413 = $s[13];
        $f4->f414 = $s[14];
        $f4->f415 = $s[15];
        $f4->f416 = $req->f416;
        $f4->save();
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
            $f5 = F5m::where('mahasiswam_id', Auth::user()->mahasiswa->id);
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
            $f6 = F6m::where('mahasiswam_id', Auth::user()->mahasiswa->id);
        } else {
            $f6 = new F6m();
        }
        $f6->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f6->f6 = $req->f6;
        $f6->save();
        /////////////////////////////////
        if (F7m::where('mahasiswam_id', Auth::user()->mahasiswa->id)->first()) {
            $f7 = F7m::where('mahasiswam_id', Auth::user()->mahasiswa->id);
        } else {
            $f7 = new F7m();
        }
        $f7->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f7->f7 = $req->f7;
        $f7->save();
        /////////////////////////////////
        if (F7am::where('mahasiswam_id', Auth::user()->mahasiswa->id)->first()) {
            $f7a = F7am::where('mahasiswam_id', Auth::user()->mahasiswa->id);
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
        $f9 = new F9m();
        $f9->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f9->f91 = $s[1];
        $f9->f92 = $s[2];
        $f9->f93 = $s[3];
        $f9->f94 = $s[4];
        $f9->f95 = $s[5];
        $f9->f96 = $req->f96;
        $f9->save();
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
        $f10 = new F10m();
        $f10->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f10->f101 = $req->f101;
        $f10->f102 = $req->f102;
        $f10->save();
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
        $f11 = new F11m();
        $f11->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f11->f111 = $req->f111;
        $f11->f112 = $req->f112;
        $f11->save();
        //////////////////////////////////
        $f12 = new F12m();
        $f12->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f12->f121 = $req->f121;
        $f12->f122 = $req->f122;
        $f12->save();
        //////////////////////////////////
        $f13 = new F13m();
        $f13->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f13->f131 = $req->f131;
        $f13->f132 = $req->f132;
        $f13->f133 = $req->f133;
        $f13->save();
        //////////////////////////////////
        $f14 = new F14m();
        $f14->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f14->f14 = $req->f14;
        $f14->save();
        //////////////////////////////////
        $f15 = new F15m();
        $f15->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f15->f15 = $req->f15;
        $f15->save();
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
        $f16 = new F16m();
        $f16->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f16->f161 = $s[1];
        $f16->f162 = $s[2];
        $f16->f163 = $s[3];
        $f16->f164 = $s[4];
        $f16->f165 = $s[5];
        $f16->f166 = $s[6];
        $f16->f167 = $s[7];
        $f16->f168 = $s[8];
        $f16->f169 = $s[9];
        $f16->f1610 = $s[10];
        $f16->f1611 = $s[11];
        $f16->f1612 = $s[12];
        $f16->f1613 = $s[13];
        $f16->f1614 = $req->f1614;
        $f16->save();
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
        $f17a = new F17am();
        $f17a->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f17a->f171    =    $req->f17a1;
        $f17a->f173    =    $req->f17a2;
        $f17a->f175    =    $req->f17a3;
        $f17a->f175a    =    $req->f17a4;
        $f17a->f177    =    $req->f17a5;
        $f17a->f179    =    $req->f17a6;
        $f17a->f1711    =    $req->f17a7;
        $f17a->f1713    =    $req->f17a8;
        $f17a->f1715    =    $req->f17a9;
        $f17a->f1717    =    $req->f17a10;
        $f17a->f1719    =    $req->f17a11;
        $f17a->f1721    =    $req->f17a12;
        $f17a->f1723    =    $req->f17a13;
        $f17a->f1725    =    $req->f17a14;
        $f17a->f1727    =    $req->f17a15;
        $f17a->f1729    =    $req->f17a16;
        $f17a->f1731    =    $req->f17a17;
        $f17a->f1733    =    $req->f17a18;
        $f17a->f1735    =    $req->f17a19;
        $f17a->f1737    =    $req->f17a20;
        $f17a->f1737a    =    $req->f17a21;
        $f17a->f1739    =    $req->f17a22;
        $f17a->f1741    =    $req->f17a23;
        $f17a->f1743    =    $req->f17a24;
        $f17a->f1745    =    $req->f17a25;
        $f17a->f1747    =    $req->f17a26;
        $f17a->f1749    =    $req->f17a27;
        $f17a->f1751    =    $req->f17a28;
        $f17a->f1753    =    $req->f17a29;
        $f17a->save();
        /////////////////////////////////
        $f17b = new F17bm();
        $f17b->mahasiswam_id = Auth::user()->mahasiswa->id;
        $f17b->f172b    =    $req->f17b1;
        $f17b->f174b    =    $req->f17b2;
        $f17b->f176b    =    $req->f17b3;
        $f17b->f176ba    =    $req->f17b4;
        $f17b->f178b    =    $req->f17b5;
        $f17b->f1710b   =    $req->f17b6;
        $f17b->f1712b   =    $req->f17b7;
        $f17b->f1714b   =    $req->f17b8;
        $f17b->f1716b   =    $req->f17b9;
        $f17b->f1718b   =    $req->f17b10;
        $f17b->f1720b   =    $req->f17b11;
        $f17b->f1722b   =    $req->f17b12;
        $f17b->f1724b   =    $req->f17b13;
        $f17b->f1726b   =    $req->f17b14;
        $f17b->f1728b   =    $req->f17b15;
        $f17b->f1730b   =    $req->f17b16;
        $f17b->f1732b   =    $req->f17b17;
        $f17b->f1734b   =    $req->f17b18;
        $f17b->f1736b   =    $req->f17b19;
        $f17b->f1738b   =    $req->f17b20;
        $f17b->f1738ba    =    $req->f17b21;
        $f17b->f1740b   =    $req->f17b22;
        $f17b->f1742b   =    $req->f17b23;
        $f17b->f1744b   =    $req->f17b24;
        $f17b->f1746b   =    $req->f17b25;
        $f17b->f1748b   =    $req->f17b26;
        $f17b->f1750b   =    $req->f17b27;
        $f17b->f1752b   =    $req->f17b28;
        $f17b->f1754b   =    $req->f17b29;
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
