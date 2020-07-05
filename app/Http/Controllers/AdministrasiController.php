<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Role;
use App\Prodim;
use App\Adminm;
use App\Mahasiswam;
use Yajra\DataTables\DataTables;
use App\User;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AlumniImport;
use App\Exports\LaporanDikti;

class AdministrasiController extends Controller
{
    //
    /*status=[
        1 =>semua,
        2 =>sudahisi,
        3 =>sedangisi,
        4 =>belumisi,
        ]
    */


    public function api_alumni($id, $status)
    {
        if (Auth::user()->hasRole('odin|rektor|humas')) {
            if ($id == 1) {
                if ($status == 1) {
                    $data_alumni = Mahasiswam::select('id', 'user_id', 'prodim_id', 'semester_lulus', 'tahun_lulus', 'ipk', 'status', 'durasi_tahun', 'durasi_bulan', 'durasi_hari', 'angkatan')->get();
                } elseif ($status == 2) {
                    $data_alumni = Mahasiswam::select('id', 'user_id', 'prodim_id', 'semester_lulus', 'tahun_lulus', 'ipk', 'status', 'durasi_tahun', 'durasi_bulan', 'durasi_hari', 'angkatan')->where('status', '>=', '99')->get();
                } elseif ($status == 3) {
                    $data_alumni = Mahasiswam::select('id', 'user_id', 'prodim_id', 'semester_lulus', 'tahun_lulus', 'ipk', 'status', 'durasi_tahun', 'durasi_bulan', 'durasi_hari', 'angkatan')->where('status', '>', '1')->where('status', '<', '99')->get();
                } elseif ($status == 4) {
                    $data_alumni = Mahasiswam::select('id', 'user_id', 'prodim_id', 'semester_lulus', 'tahun_lulus', 'ipk', 'status', 'durasi_tahun', 'durasi_bulan', 'durasi_hari', 'angkatan')->where('status', '1')->get();
                } else {
                    abort(404);
                }
            } else {
                if ($status == 1) {
                    $data_alumni = Mahasiswam::select('id', 'user_id', 'prodim_id', 'semester_lulus', 'tahun_lulus', 'ipk', 'status', 'durasi_tahun', 'durasi_bulan', 'durasi_hari', 'angkatan')->where('prodim_id', $id)->get();
                } elseif ($status == 2) {
                    $data_alumni = Mahasiswam::select('id', 'user_id', 'prodim_id', 'semester_lulus', 'tahun_lulus', 'ipk', 'status', 'durasi_tahun', 'durasi_bulan', 'durasi_hari', 'angkatan')->where('status', '>=', '99')->where('prodim_id', $id)->get();
                } elseif ($status == 3) {
                    $data_alumni = Mahasiswam::select('id', 'user_id', 'prodim_id', 'semester_lulus', 'tahun_lulus', 'ipk', 'status', 'durasi_tahun', 'durasi_bulan', 'durasi_hari', 'angkatan')->where('status', '>', '1')->where('status', '<', '99')->where('prodim_id', $id)->get();
                } elseif ($status == 4) {
                    $data_alumni = Mahasiswam::select('id', 'user_id', 'prodim_id', 'semester_lulus', 'tahun_lulus', 'ipk', 'status', 'durasi_tahun', 'durasi_bulan', 'durasi_hari', 'angkatan')->where('status', '1')->where('prodim_id', $id)->get();
                } else {
                    abort(404);
                }
            }
        } elseif (Auth::user()->hasRole('dekan')) {
            if ($id == 1) {
                $data_alumni = Mahasiswam::select('mahasiswams.id', 'mahasiswams.user_id', 'mahasiswams.prodim_id', 'mahasiswams.semester_lulus', 'mahasiswams.tahun_lulus', 'mahasiswams.ipk', 'prodims.id')
                    ->join('prodims', 'mahasiswams.prodim_id', '=', 'prodims.id')
                    ->where('fakultasm_id', Auth::user()->admin->prodi->fakultas_id)->get();
            } else {
                $data_alumni = Mahasiswam::select('id', 'user_id', 'prodim_id', 'semester_lulus', 'tahun_lulus', 'ipk', 'status', 'durasi_tahun', 'durasi_bulan', 'durasi_hari', 'angkatan')->where('prodim_id', $id)->get();
            }
        } else {
            $data_alumni = Mahasiswam::select('id', 'user_id', 'prodim_id', 'semester_lulus', 'tahun_lulus', 'ipk', 'status', 'durasi_tahun', 'durasi_bulan', 'durasi_hari', 'angkatan')->where('prodim_id', $id)->get();
        }

        return DataTables::of($data_alumni)
            ->addColumn('nim', function ($data_alumni) {
                return $data_alumni->user->nim;
            })
            ->addColumn('nama', function ($data_alumni) {
                return $data_alumni->user->name;
            })
            ->addColumn('prodi', function ($data_alumni) {
                return $data_alumni->prodi->slug_prodi;
            })
            ->addColumn('aksi', function ($data_alumni) {
                return view('administrasi.alumni._aksialumni')->with([
                    'data' => $data_alumni
                ]);
            })
            ->editColumn('ipk', function ($data_alumni) {
                return number_format($data_alumni->ipk, 2);
            })
            ->addColumn('tahun_semester', '{{$tahun_lulus}}{{$semester_lulus}}')
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function api_akun()
    {
        $data_akun = Adminm::all();
        return DataTables::of($data_akun)
            ->addColumn('username', function ($data_akun) {
                return $data_akun->user->nim;
            })
            ->addColumn('nama', function ($data_akun) {
                return $data_akun->user->name;
            })
            ->addColumn('fakultas', function ($data_akun) {
                return $data_akun->prodi->fakultas->nama_fakultas;
            })
            ->addColumn('prodi', function ($data_akun) {
                return $data_akun->prodi->nama_prodi;
            })
            ->addColumn('peran', function ($data_akun) {
                $rolenya = User::where('id', $data_akun->user_id)->first();
                return $rolenya->roles()->value('display_name');
            })
            ->addColumn('aksi', function ($data_akun) {
                return view('administrasi.akun._aksiakun')->with([
                    'data' => $data_akun
                ]);
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function index()
    {
        //$jumlahmahasiswa = Mahasiswam::where('prodim_id', Auth::user()->admin->prodi->id)->count();
        //dd($jumlahmahasiswa);
        if (Auth::user()->hasRole('odin|rektor|humas')) {
            $subtitle = 'Semua Alumni';
            $jumlahmahasiswa = Mahasiswam::count();
        } elseif (Auth::user()->hasRole('dekan')) {
            $subtitle = "Hola";
        } elseif (Auth::user()->hasRole('admin')) {
            $subtitle = '';
        }
        return view('administrasi.index')->with([
            'active' => '1',
            'subactive' => '0',
            'title' => 'Dashboard',
            'subtitle' => 'Ringkasan Informasi Tracer ' . $subtitle,
        ]);
    }
    public function hal_alumni($prodi_id, $status)
    {

        if ((!isset($prodi_id)) || (!Prodim::find($prodi_id)) || (!isset($status)) || ($status < 1) || ($status > 4)) {
            abort(404);
        }
        if (Auth::user()->hasRole('odin|rektor|humas')) {
            $subtitle = Prodim::find($prodi_id);
            $ajax = $subtitle;
        } elseif (Auth::user()->hasRole('dekan')) {
            $subtitle = Prodim::where(function ($q) {
                $q->where('fakultasm_id', Auth::user()->admin->prodi->fakultasm_id);
            })->where('id', $prodi_id)->first();

            $ajax = $subtitle;
        } elseif (Auth::user()->hasRole('admin')) {
            $subtitle = Prodim::where('id', Auth::user()->admin->prodi->id)->first();
            $ajax = $subtitle;
        }

        if (!$subtitle) {
            abort(404);
        }


        return view('administrasi.alumni.index')->with([
            'active' => '2',
            'subactive' => '2' . $subtitle->id,
            'title' => 'Alumni',
            'subtitle' => 'Fakultas ' . Auth::user()->admin->prodi->fakultas->nama_fakultas . ', Prodi ' . $subtitle->nama_prodi,
            'ajax' => $ajax->id,
            'status' => $status,
        ]);
    }
    public function alumni_simpan(Request $req)
    {
        $this->validate($req, [
            'nim' => 'required|integer|unique:users,nim',
            'nama' => 'required',
            'password' => 'required|confirmed',
            'prodi' => 'required|digits_between:1,2',
            'tahunlulus' => 'required|digits:4',
            'semesterlulus' => 'required|between:1,2',
            'ipk' => 'required|numeric|between:1,4.00',
            'ajax' => 'required|exists:prodims,id'
        ], $messages = [
            'nim.required' => 'Nomor Induk Mahasiswa Harus Diisi',
            'nim.integer' => 'Nomor Induk Mahasiswa harus berupa Angka',
            'nim.unique' => 'NIM ' . $req->nim . ' Sudah ada didalam Sistem ',
            'nama.required' => 'Nama harus diisi',
            'password.required' => 'Bidang isian Password harus diisi',
            'password.confirmed' => 'Bidang isian Ulangi Password harus sama dengan Bidang isian Password',
            'tahunlulus.required' => 'Tahun lulus harus diisi. Minimal 1000 maksimal 9999',
            'tahunlulus.digits' => 'Tahun lulus harus diantara 1000 sampai 9999',
            'semesterlulus.required' => 'Semester Lulus harus diisi antara 1 atau 2',
            'semesterlulus.between' => 'Semester lulus harus diantara 1 (ganjil) atau 2 (genap)',
            'ipk.required' => 'IPK Alumni harus diisi',
            'ipk.numeric' => 'IPK harus angka, jika berkoma gunakan simbol titik (contoh: 3.24)',
            'ipk.between' => 'IPK harus diantara 1.00 sampai 4.00',
            'ajax.required' => ' Terdapat kesalahan. Mohon Ulangi',
            'ajax.exists' => ' Terdapat kesalahan. Mohon Ulangi',
        ]);
        $user = new User();
        $user->nim = $req->nim;
        $user->name = $req->nama;
        $user->email = $req->email;
        $user->password = bcrypt($req->password);
        $user->save();
        $user->attachRole('mahasiswa');
        $mahasiswa = Mahasiswam::create([
            'user_id' => User::select('id')->where('nim', $req->nim)->first()->id,
            'prodim_id' => $req->prodi,
            'ipk' => $req->ipk,
            'semester_lulus' => $req->semesterlulus,
            'tahun_lulus' => $req->tahunlulus,
            'angkatan' => $req->angkatan,
            'durasi_tahun' => $req->durasitahun,
            'durasi_bulan' => $req->durasibulan,
            'durasi_hari' => $req->durasihari,
            'status' => 1
        ]);
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menambahakan data"
        ]);
        return redirect()->route('administrasi.alumni', ['prodim_id' => $req->prodi, 'status' => 4]);
    }
    public function alumni_hapus(Request $req)
    {
        $user = User::find($req->id);
        $user->delete();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menghapus data"
        ]);
        return redirect()->back();
    }
    public function profil()
    {
        return view('administrasi.profil')->with([
            'active' => '1',
            'subactive' => '1',
            'title' => 'Profil',
            'subtitle' => '',

        ]);
    }
    public function alumni_edit(Request $req)
    {
        $this->validate($req, [
            'nim' => 'required|integer|unique:users,nim,' . $req->id,
            'nama' => 'required',
            'password' => 'confirmed',
            'prodi' => 'required|digits_between:1,2',
            'tahunlulus' => 'required|digits:4',
            'semesterlulus' => 'required|between:1,2',
            'ipk' => 'required|numeric|between:1,4.00',
            'ajax' => 'required|exists:Prodims,id'
        ], $messages = [
            'nim.required' => 'Nomor Induk Mahasiswa Harus Diisi',
            'nim.integer' => 'Nomor Induk Mahasiswa harus berupa Angka',
            'nim.unique' => 'NIM ' . $req->nim . ' Sudah ada didalam Sistem ',
            'nama.required' => 'Nama harus diisi',

            'password.confirmed' => 'Bidang isian Ulangi Password harus sama dengan Bidang isian Password',
            'tahunlulus.required' => 'Tahun lulus harus diisi. Minimal 1000 maksimal 9999',
            'tahunlulus.digits' => 'Tahun lulus harus diantara 1000 sampai 9999',
            'semesterlulus.required' => 'Semester Lulus harus diisi antara 1 atau 2',
            'semesterlulus.between' => 'Semester lulus harus diantara 1 (ganjil) atau 2 (genap)',
            'ipk.required' => 'IPK Alumni harus diisi',
            'ipk.numeric' => 'IPK harus angka, jika berkoma gunakan simbol titik (contoh: 3.24)',
            'ipk.between' => 'IPK harus diantara 1.00 sampai 4.00',
            'ajax.required' => ' Terdapat kesalahan. Mohon Ulangi',
            'ajax.exists' => ' Terdapat kesalahan. Mohon Ulangi',
        ]);
        $user = User::find($req->id);
        $user->nim = $req->nim;
        $user->name = $req->nama;
        $user->email = $req->email;
        if ($req->password != "") {
            $user->password = bcrypt($req->password);
        }
        $user->save();
        $mahasiswa = Mahasiswam::where('user_id', $req->id)->first();
        $mahasiswa->prodim_id = $req->prodi;
        $mahasiswa->ipk = $req->ipk;
        $mahasiswa->semester_lulus = $req->semesterlulus;
        $mahasiswa->tahun_lulus = $req->tahunlulus;
        $mahasiswa->angkatan = $req->angkatan;
        $mahasiswa->durasi_tahun = $req->durasitahun;
        $mahasiswa->durasi_bulan = $req->durasibulan;
        $mahasiswa->durasi_hari = $req->durasihari;
        $mahasiswa->save();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil Mengubah data"
        ]);
        return redirect()->back();
    }
    public function profil_simpan(Request $request)
    {
        $this->validate($request, [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nim' => 'required|exists:users,nim',
            'nama' => 'required',
            'password' => 'confirmed',
        ], $messages = [
            'nim.required' => 'Username Harus Diisi',
            'nim.unique' => 'Username ' . $request->nim . ' Sudah ada didalam Sistem ',
            'nama.required' => 'Nama harus diisi',
            'password.required' => 'Bidang isian Password harus diisi',
            'password.confirmed' => 'Bidang isian Ulangi Password harus sama dengan Bidang isian Password',
        ]);
        $admin = User::find(Auth::id());
        if ($request->image) {
            $imageName = Auth::id() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('foto'), $imageName);
            $admin->foto = $imageName;
        }
        if ($request->password) {
            $admin->password = bcrypt($request->password);
        }
        $admin->nim = $request->nim;
        $admin->name = $request->nama;
        $admin->email = $request->email;
        $admin->tlp = $request->tlp;
        $admin->update();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil Mengubah Profil"
        ]);

        return redirect()->route('administrasi.profil');
    }
    public function import_mahasiswa(Request $request)
    {
        Excel::Import(new AlumniImport, request()->file('file'));
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => session()->get('berhasil') . ' ' . session()->get('kegagalan')
        ]);
        return back();
    }
    public function akun()
    {
        return view('administrasi.akun.index')->with([
            'active' => '3',
            'subactive' => '0',
            'title' => 'Akun',
            'subtitle' => 'Daftar Akun Administrasi yang ada ',
        ]);
    }
    public function akun_simpan(Request $req)
    {
        $this->validate($req, [
            'nim' => 'required|unique:users,nim',
            'nama' => 'required',
            'password' => 'required|confirmed',
            'prodi' => 'required|digits_between:1,2',
        ], $messages = [
            'nim.required' => 'Nomor Induk Mahasiswa Harus Diisi',
            'nim.unique' => 'NIM ' . $req->nim . ' Sudah ada didalam Sistem ',
            'nama.required' => 'Nama harus diisi',
            'password.required' => 'Bidang isian Password harus diisi',
            'password.confirmed' => 'Bidang isian Ulangi Password harus sama dengan Bidang isian Password',

        ]);
        $user = new User();
        $user->nim = $req->nim;
        $user->name = $req->nama;
        $user->email = $req->email;
        $user->password = bcrypt($req->password);
        $user->save();
        if ($req->role == 'rektor|humas|odin') {
            $prodi = '1';
        } else {
            $prodi = $req->prodi;
        }
        $admin = Adminm::create([
            'user_id' => User::select('id')->where('nim', $req->nim)->first()->id,
            'prodim_id' => $prodi,

        ]);
        $user->attachRole($req->role);
        return back();
    }
    public function akun_hapus(Request $req)
    {
        $user = User::find($req->id);
        $user->delete();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menghapus data"
        ]);
        return back();
    }
    public function lapordikti($prodi_id)
    {
        if ((!isset($prodi_id)) || (!Prodim::find($prodi_id))) {
            abort(404);
        }
        if (Auth::user()->hasRole('odin|rektor|humas')) {
            $subtitle = Prodim::find($prodi_id);
            $ajax = $subtitle;
        } elseif (Auth::user()->hasRole('dekan')) {
            $subtitle = Prodim::where(function ($q) {
                $q->where('fakultasm_id', Auth::user()->admin->prodi->fakultas_id)->orWhere('fakultasm_id', 1);
            })->where('id', $prodi_id)->first();

            $ajax = $subtitle;
        } elseif (Auth::user()->hasRole('admin')) {
            $subtitle = Prodim::where('id', Auth::user()->admin->prodi->id)->first();
            $ajax = $subtitle;
        }

        if (!$subtitle) {
            abort(404);
        }
        $data = Mahasiswam::where('status', 99)->get();
        //dd($data);

        return Excel::download(new LaporanDikti($prodi_id), 'dikti.xlsx');
    }
}
