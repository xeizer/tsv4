<?php

namespace App\Http\Controllers;

use App\F12m;
use App\F13m;
use Illuminate\Http\Request;
use App\Prodim;
use Auth;
use App\Mahasiswam;
use App\F8m;
use App\F5m;
use App\F8a;
use App\F14m;
use App\F15m;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    //
    public function index($prodi_id)
    {
        if ((!isset($prodi_id)) || (!Prodim::find($prodi_id))) {
            abort(404);
        }
        if (Auth::user()->hasRole('odin|rektor|humas')) {
            if ($prodi_id == 1) {
                $subtitle = Prodim::find($prodi_id);
                $semuamahasiswa = Mahasiswam::count();
                $t_belumisi = Mahasiswam::where('status', '1')->count();
                $t_sedangisi = Mahasiswam::where('status', '>', '1')->where('status', '<', '99')->count();
                $t_sudahisi = Mahasiswam::where('status', '>=', '99')->count();
                $ipkmax = Mahasiswam::orderBy('ipk', 'DESC')->first();
                $ipkmin = Mahasiswam::orderBy('ipk', 'ASC')->first();
                $ipkavg = Mahasiswam::select('ipk')->avg('ipk');
                $f8_job = F8m::where('f8', 1)->count();
                $f8_neet = F8m::where('f8', 2)->count();
            } else {
                $subtitle = Prodim::find($prodi_id);
                $semuamahasiswa = Mahasiswam::where('prodim_id', $prodi_id)->count();
                $t_belumisi = Mahasiswam::where('prodim_id', $prodi_id)->where('status', '1')->count();
                $t_sedangisi = Mahasiswam::where('prodim_id', $prodi_id)->where('status', '>', '1')->where('status', '<', '99')->count();
                $t_sudahisi = Mahasiswam::where('prodim_id', $prodi_id)->where('status', '>=', '99')->count();
                $ipkmax = Mahasiswam::where('prodim_id', $prodi_id)->orderBy('ipk', 'DESC')->first();
                $ipkmin = Mahasiswam::where('prodim_id', $prodi_id)->orderBy('ipk', 'ASC')->first();
                $ipkavg = Mahasiswam::where('prodim_id', $prodi_id)->select('ipk')->avg('ipk');
                $f8_job = F8m::where('f8', 1)->whereHas('mahasiswa', function ($q) use ($prodi_id) {
                    $q->where('prodim_id', $prodi_id);
                })->count();
                $f8_neet = F8m::where('f8', 2)->whereHas('mahasiswa', function ($q) use ($prodi_id) {
                    $q->where('prodim_id', $prodi_id);
                })->count();
            }
        } elseif (Auth::user()->hasRole('dekan')) {
            $subtitle = Prodim::where(function ($q) {
                $q->where('fakultasm_id', Auth::user()->admin->prodi->fakultas_id)->orWhere('fakultasm_id', 1);
            })->where('id', $prodi_id)->first();
        } elseif (Auth::user()->hasRole('admin')) {
            $prodiauth = Auth::user()->admin->prodi->id;
            $subtitle = Prodim::find($prodiauth);
            $semuamahasiswa = Mahasiswam::where('prodim_id', $prodiauth)->count();
            $t_belumisi = Mahasiswam::where('prodim_id', $prodiauth)->where('status', '1')->count();
            $t_sedangisi = Mahasiswam::where('prodim_id', $prodiauth)->where('status', '>', '1')->where('status', '<', '99')->count();
            $t_sudahisi = Mahasiswam::where('prodim_id', $prodiauth)->where('status', '>=', '99')->count();
            $ipkmax = Mahasiswam::where('prodim_id', $prodiauth)->orderBy('ipk', 'DESC')->first();
            $ipkmin = Mahasiswam::where('prodim_id', $prodiauth)->orderBy('ipk', 'ASC')->first();
            $ipkavg = Mahasiswam::where('prodim_id', $prodiauth)->select('ipk')->avg('ipk');
            $f8_job = F8m::where('f8', 1)->whereHas('mahasiswa', function ($q) use ($prodiauth) {
                $q->where('prodim_id', $prodiauth);
            })->count();
            $f8_neet = F8m::where('f8', 2)->whereHas('mahasiswa', function ($q) use ($prodiauth) {
                $q->where('prodim_id', $prodiauth);
            })->count();
        }
        if (!$subtitle) {
            abort(404);
        }
        return view('administrasi.tracer.index')->with([
            'active' => '4',
            'subactive' => '4' . $subtitle->id,
            'title' => 'Alumni',
            'subtitle' => 'Fakultas ' . Auth::user()->admin->prodi->fakultas->nama_fakultas . ' Prodi ' . $subtitle->nama_prodi,
            'ipk' => [
                'avg' => $ipkavg,
                'max' => $ipkmax,
                'min' => $ipkmin
            ],
            'mahasiswa' => [
                'prodi' => $prodi_id,
                'semua' => $semuamahasiswa,
                'belumisi' => $t_belumisi,
                'sedangisi' => $t_sedangisi,
                'sudahisi' => $t_sudahisi,
            ],
            'f8' => [
                'job' => $f8_job,
                'neet' => $f8_neet,
            ]
        ]);
    }
    public function rekaptracer($prodi, $tahunangkatan, $tahunlulus)
    {
        if (Auth::user()->hasRole('odin|rektor|humas')) {
            if ($prodi == 1) {
                if ($tahunangkatan == 0) {
                    if ($tahunlulus == 0) {
                        //odin, prodi all,  tahun angkatan all, tahun lulus all OK
                        $data = [
                            'semuamahasiswa' => Mahasiswam::count(),
                            'mahasiswabelumisi' => Mahasiswam::where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::avg('ipk'),
                            'biayasendiri' => F12m::where('f121', 1)->count(),
                            'adik' => F12m::where('f121', 2)->count(),
                            'bidikmisi' => F12m::where('f121', 3)->count(),
                            'ppa' => F12m::where('f121', 4)->count(),
                            'afirmasi' => F12m::where('f121', 5)->count(),
                            'perusahaan' => F12m::where('f121', 6)->count(),
                            'lainnya' => F12m::where('f121', 7)->count(),
                            'f14-1' => F14m::where('f14', 1)->count(),
                            'f14-2' => F14m::where('f14', 2)->count(),
                            'f14-3' => F14m::where('f14', 3)->count(),
                            'f14-4' => F14m::where('f14', 4)->count(),
                            'f14-5' => F14m::where('f14', 5)->count(),
                            'f15-1' => F15m::where('f15', 1)->count(),
                            'f15-2' => F15m::where('f15', 2)->count(),
                            'f15-3' => F15m::where('f15', 3)->count(),
                            'f15-4' => F15m::where('f15', 4)->count(),
                            'kerja' => F8m::where('f8', 1)->count(),
                            'belumkerja' => F8m::where('f8', 2)->count(),
                            'lebih18' => F5m::select(DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select(DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select(DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::where('f8a', 1)->count(),
                            'nasional' => F8a::where('f8a', 2)->count(),
                            'internasional' => F8a::where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->get()->avg('total'),

                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi all,  tahun angkatan all, tahun lulus pilih OK
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                } else {
                    if ($tahunlulus == 0) {
                        //odin, prodi all,  tahun angkatan pilih, tahun lulus all
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('angkatan', $tahunangkatan)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('angkatan', $tahunangkatan)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('angkatan', $tahunangkatan)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('angkatan', $tahunangkatan)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('angkatan', $tahunangkatan)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('angkatan', $tahunangkatan)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('angkatan', $tahunangkatan)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('angkatan', $tahunangkatan)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('angkatan', $tahunangkatan)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('angkatan', $tahunangkatan)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('angkatan', $tahunangkatan)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('angkatan', $tahunangkatan)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('angkatan', $tahunangkatan)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('angkatan', $tahunangkatan)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('angkatan', $tahunangkatan)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('angkatan', $tahunangkatan)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('angkatan', $tahunangkatan)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('angkatan', $tahunangkatan)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('angkatan', $tahunangkatan)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('angkatan', $tahunangkatan)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('angkatan', $tahunangkatan)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('angkatan', $tahunangkatan)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('angkatan', $tahunangkatan)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('angkatan', $tahunangkatan)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('angkatan', $tahunangkatan)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi all,  tahun angkatan pilih, tahun lulus pilih
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                }
            } else {
                //odin, prodi pilih,  tahun angkatan all, tahun lulus all
                if ($tahunangkatan == 0) {
                    if ($tahunlulus == 0) {
                        //odin, prodi pilih,  tahun angkatan all, tahun lulus all
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('prodim_id', $prodi)->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('prodim_id', $prodi)->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('prodim_id', $prodi)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('prodim_id', $prodi)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('prodim_id', $prodi)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('prodim_id', $prodi)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('prodim_id', $prodi)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('prodim_id', $prodi)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('prodim_id', $prodi)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('prodim_id', $prodi)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('prodim_id', $prodi)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f121', 1)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 2)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 3)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 4)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 5)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 6)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 7)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f8', 1)->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f8', 2)->count(),
                            'lebih18' => F5m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*', DB::raw('f502+f503 as total'))
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*', DB::raw('f502+f503 as total'))
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*', DB::raw('f502+f503 as total'))
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)->where('prodim_id', $prodi)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 1)->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 3)->count(),
                            'gajimax' => F13m::select('mahasiswams.*', DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('prodim_id', $prodi)
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select('mahasiswams.*', DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('prodim_id', $prodi)
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select('mahasiswams.*', DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('prodim_id', $prodi)
                                ->where('f8ms.f8', 1)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi pilih,  tahun angkatan all, tahun lulus pilih
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus', 'angkatan')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                } else {
                    //odin, prodi pilih,  tahun angkatan pilih, tahun lulus all
                    if ($tahunlulus == 0) {
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 99)->count(),
                            'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi all,  tahun angkatan pilih, tahun lulus pilih
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'ipkmax' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                }
            }
        } elseif (Auth::user()->hasRole('dekan')) {
            if ($prodi == 1) {
                if ($tahunangkatan == 0) {
                    if ($tahunlulus == 0) {
                        //odin, prodi all,  tahun angkatan all, tahun lulus all OK
                        $data = [
                            'semuamahasiswa' => Mahasiswam::count(),
                            'mahasiswabelumisi' => Mahasiswam::where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::avg('ipk'),
                            'biayasendiri' => F12m::where('f121', 1)->count(),
                            'adik' => F12m::where('f121', 2)->count(),
                            'bidikmisi' => F12m::where('f121', 3)->count(),
                            'ppa' => F12m::where('f121', 4)->count(),
                            'afirmasi' => F12m::where('f121', 5)->count(),
                            'perusahaan' => F12m::where('f121', 6)->count(),
                            'lainnya' => F12m::where('f121', 7)->count(),
                            'f14-1' => F14m::where('f14', 1)->count(),
                            'f14-2' => F14m::where('f14', 2)->count(),
                            'f14-3' => F14m::where('f14', 3)->count(),
                            'f14-4' => F14m::where('f14', 4)->count(),
                            'f14-5' => F14m::where('f14', 5)->count(),
                            'f15-1' => F15m::where('f15', 1)->count(),
                            'f15-2' => F15m::where('f15', 2)->count(),
                            'f15-3' => F15m::where('f15', 3)->count(),
                            'f15-4' => F15m::where('f15', 4)->count(),
                            'kerja' => F8m::where('f8', 1)->count(),
                            'belumkerja' => F8m::where('f8', 2)->count(),
                            'lebih18' => F5m::select(DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select(DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select(DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::where('f8a', 1)->count(),
                            'nasional' => F8a::where('f8a', 2)->count(),
                            'internasional' => F8a::where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->get()->avg('total'),

                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi all,  tahun angkatan all, tahun lulus pilih OK
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                } else {
                    if ($tahunlulus == 0) {
                        //odin, prodi all,  tahun angkatan pilih, tahun lulus all
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('angkatan', $tahunangkatan)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('angkatan', $tahunangkatan)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('angkatan', $tahunangkatan)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('angkatan', $tahunangkatan)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('angkatan', $tahunangkatan)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('angkatan', $tahunangkatan)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('angkatan', $tahunangkatan)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('angkatan', $tahunangkatan)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('angkatan', $tahunangkatan)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('angkatan', $tahunangkatan)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('angkatan', $tahunangkatan)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('angkatan', $tahunangkatan)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('angkatan', $tahunangkatan)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('angkatan', $tahunangkatan)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('angkatan', $tahunangkatan)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('angkatan', $tahunangkatan)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('angkatan', $tahunangkatan)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('angkatan', $tahunangkatan)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('angkatan', $tahunangkatan)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('angkatan', $tahunangkatan)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('angkatan', $tahunangkatan)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('angkatan', $tahunangkatan)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('angkatan', $tahunangkatan)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('angkatan', $tahunangkatan)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('angkatan', $tahunangkatan)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi all,  tahun angkatan pilih, tahun lulus pilih
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                }
            } else {
                //odin, prodi pilih,  tahun angkatan all, tahun lulus all
                if ($tahunangkatan == 0) {
                    if ($tahunlulus == 0) {
                        //odin, prodi pilih,  tahun angkatan all, tahun lulus all
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('prodim_id', $prodi)->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('prodim_id', $prodi)->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('prodim_id', $prodi)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('prodim_id', $prodi)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('prodim_id', $prodi)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('prodim_id', $prodi)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('prodim_id', $prodi)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('prodim_id', $prodi)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('prodim_id', $prodi)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('prodim_id', $prodi)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('prodim_id', $prodi)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f121', 1)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 2)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 3)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 4)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 5)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 6)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 7)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f8', 1)->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f8', 2)->count(),
                            'lebih18' => F5m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*', DB::raw('f502+f503 as total'))
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*', DB::raw('f502+f503 as total'))
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*', DB::raw('f502+f503 as total'))
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)->where('prodim_id', $prodi)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 1)->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 3)->count(),
                            'gajimax' => F13m::select('mahasiswams.*', DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('prodim_id', $prodi)
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select('mahasiswams.*', DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('prodim_id', $prodi)
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select('mahasiswams.*', DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('prodim_id', $prodi)
                                ->where('f8ms.f8', 1)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi pilih,  tahun angkatan all, tahun lulus pilih
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus', 'angkatan')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                } else {
                    //odin, prodi pilih,  tahun angkatan pilih, tahun lulus all
                    if ($tahunlulus == 0) {
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 99)->count(),
                            'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi all,  tahun angkatan pilih, tahun lulus pilih
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'ipkmax' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                }
            }
        } elseif (Auth::user()->hasRole('admin')) {
            $prodi = Auth::user()->admin->prodim_id;

            if ($tahunangkatan == 0) {
                if ($tahunlulus == 0) {
                    //odin, prodi pilih,  tahun angkatan all, tahun lulus all
                    $data = [
                        'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->count(),
                        'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', 1)->count(),
                        'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', '>', '1')->where('status', '<', '99')->count(),
                        'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', 99)->count(),
                        'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('prodim_id', $prodi)->where('status', 99)->get(),
                        'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('prodim_id', $prodi)->where('status', 99)->get(),
                        'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->orderBy('ipk', 'desc')->first(),
                        'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->orderBy('ipk', 'asc')->first(),
                        'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->avg('ipk'),
                        'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 1)->where('prodim_id', $prodi)->count(),
                        'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('prodim_id', $prodi)->count(),
                        'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('prodim_id', $prodi)->count(),
                        'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('prodim_id', $prodi)->count(),
                        'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('prodim_id', $prodi)->count(),
                        'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('prodim_id', $prodi)->count(),
                        'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 7)
                            ->where('prodim_id', $prodi)->count(),
                        'f14-1' => F14m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f14', 1)->count(),
                        'f14-2' => F14m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f14', 2)->count(),
                        'f14-3' => F14m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f14', 3)->count(),
                        'f14-4' => F14m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f14', 4)->count(),
                        'f14-5' => F14m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f14', 5)->count(),
                        'f15-1' => F15m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f15', 1)->count(),
                        'f15-2' => F15m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f15', 2)->count(),
                        'f15-3' => F15m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f15', 3)->count(),
                        'f15-4' => F15m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f15', 4)->count(),
                        'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 1)
                            ->where('prodim_id', $prodi)
                            ->count(),
                        'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 2)
                            ->where('prodim_id', $prodi)
                            ->count(),
                        'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)

                            ->whereRaw('f502+f503 >= 18')
                            ->count(),
                        'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)

                            ->whereRaw('f502+f503 > 6')
                            ->whereRaw('f502+f503 < 18')
                            ->count(),
                        'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)

                            ->whereRaw('f502+f503 <= 6')->count(),
                        'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8a', 1)
                            ->where('prodim_id', $prodi)

                            ->count(),
                        'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('prodim_id', $prodi)
                            ->where('f8a', 2)->count(),
                        'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('prodim_id', $prodi)
                            ->where('f8a', 3)->count(),
                        'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)

                            ->orderBy('total', 'desc')
                            ->first(),
                        'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)

                            ->orderBy('total', 'asc')
                            ->first(),
                        'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)

                            ->get()->avg('total'),
                        //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                        //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                        //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                    ];
                    //dd($data);
                } else {
                    //odin, prodi pilih,  tahun angkatan all, tahun lulus pilih
                    $data = [
                        'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                        'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                        'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                        'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus', 'angkatan')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                        'daftarangkatan' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                        'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                        'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                        'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                        'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 1)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 7)
                            ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'f14-1' => F14m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f14', 1)->count(),
                        'f14-2' => F14m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f14', 2)->count(),
                        'f14-3' => F14m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f14', 3)->count(),
                        'f14-4' => F14m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f14', 4)->count(),
                        'f14-5' => F14m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f14', 5)->count(),
                        'f15-1' => F15m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f15', 1)->count(),
                        'f15-2' => F15m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f15', 2)->count(),
                        'f15-3' => F15m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f15', 3)->count(),
                        'f15-4' => F15m::whereHas('mahasiswa', function ($q) {
                            $q->where('prodim_id', $prodi);
                        })->where('f15', 4)->count(),
                        'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 1)
                            ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)
                            ->count(),
                        'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 2)
                            ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)
                            ->count(),
                        'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->whereRaw('f502+f503 >= 18')
                            ->count(),
                        'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->whereRaw('f502+f503 > 6')
                            ->whereRaw('f502+f503 < 18')
                            ->count(),
                        'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->whereRaw('f502+f503 <= 6')->count(),
                        'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8a', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->count(),
                        'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('prodim_id', $prodi)
                            ->where('f8a', 2)->count(),
                        'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('prodim_id', $prodi)
                            ->where('f8a', 3)->count(),
                        'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->orderBy('total', 'desc')
                            ->first(),
                        'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->orderBy('total', 'asc')
                            ->first(),
                        'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->get()->avg('total'),
                        //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                        //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                        //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                    ];
                }
            } else {
                //odin, prodi pilih,  tahun angkatan pilih, tahun lulus all
                if ($tahunlulus == 0) {
                    $data = [
                        'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 1)->count(),
                        'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', '>', '1')->where('status', '<', '99')->count(),
                        'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 99)->count(),
                        'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus', 'angkatan')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 99)->get(),
                        'daftarangkatan' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 99)->get(),
                        'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->orderBy('ipk', 'desc')->first(),
                        'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->orderBy('ipk', 'asc')->first(),
                        'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->avg('ipk'),
                        'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 1)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 7)
                            ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 1)
                            ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)
                            ->count(),
                        'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 2)
                            ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)
                            ->count(),
                        'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->whereRaw('f502+f503 >= 18')
                            ->count(),
                        'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->whereRaw('f502+f503 > 6')
                            ->whereRaw('f502+f503 < 18')
                            ->count(),
                        'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->whereRaw('f502+f503 <= 6')->count(),
                        'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8a', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->count(),
                        'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('prodim_id', $prodi)
                            ->where('f8a', 2)->count(),
                        'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('prodim_id', $prodi)
                            ->where('f8a', 3)->count(),
                        'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->orderBy('total', 'desc')
                            ->first(),
                        'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->orderBy('total', 'asc')
                            ->first(),
                        'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->get()->avg('total'),
                        //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                        //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                        //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                    ];
                } else {
                    //odin, prodi all,  tahun angkatan pilih, tahun lulus pilih
                    $data = [
                        'semuamahasiswa' => Mahasiswam::where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'mahasiswabelumisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                        'mahasiswasedangisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                        'mahasiswaselesaiisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                        'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                        'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                        'ipkmax' => Mahasiswam::where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                        'ipkmin' => Mahasiswam::where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                        'ipkavg' => Mahasiswam::where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                        'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 1)->where('angkatan', $tahunangkatan)
                            ->where('mahasiswams.prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 7)
                            ->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 1)
                            ->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)
                            ->count(),
                        'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 2)
                            ->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)
                            ->count(),
                        'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->whereRaw('f502+f503 >= 18')
                            ->count(),
                        'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->whereRaw('f502+f503 > 6')
                            ->whereRaw('f502+f503 < 18')
                            ->count(),
                        'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->whereRaw('f502+f503 <= 6')->count(),
                        'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8a', 1)
                            ->count(),
                        'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 2)->count(),
                        'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 3)->count(),
                        'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->orderBy('total', 'desc')
                            ->first(),
                        'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->orderBy('total', 'asc')
                            ->first(),
                        'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->get()->avg('total'),
                        //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                        //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                        //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                    ];
                }
            }
        } else {
            return '404';
        }
        //dd($data);
        return view('administrasi.tracer.index')->with([
            'data' => $data,
            'prodi' => $prodi,
            'tahunangkatan' => $tahunangkatan,
            'tahunlulus' => $tahunlulus,
        ]);

        /*
        parameter
            $prodi[
                1->semua
                2 prodi yang ada
            ]
            $level[
                1->odin, rektor, humas
                2->dekan
                3->admin
            ]
            $tahunangkatan[
                0->all
                yyyy
            ]
            $tahunlulus[
                0->all
                yyyy
            ]

        */
        /*data mahasiswa
            [
                semuamahasiswa,
                mahasiswa sudah isi tracer,
                mahasiswa sedang isi tracer,
                mahasiswa belum ini tracer,
                ipk-tinggi, ipk-rendah, ipk-rata,

            ]
        */
    }
    public function rekaptracer2(Request $req)
    {
        $prodi = $req->prodi;
        $tahunangkatan = $req->tahunangkatan;
        $tahunlulus = $req->tahunlulus;
        if (Auth::user()->hasRole('odin|rektor|humas')) {
            if ($prodi == 1) {
                if ($tahunangkatan == 0) {
                    if ($tahunlulus == 0) {
                        //odin, prodi all,  tahun angkatan all, tahun lulus all OK
                        $data = [
                            'semuamahasiswa' => Mahasiswam::count(),
                            'mahasiswabelumisi' => Mahasiswam::where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::avg('ipk'),
                            'biayasendiri' => F12m::where('f121', 1)->count(),
                            'adik' => F12m::where('f121', 2)->count(),
                            'bidikmisi' => F12m::where('f121', 3)->count(),
                            'ppa' => F12m::where('f121', 4)->count(),
                            'afirmasi' => F12m::where('f121', 5)->count(),
                            'perusahaan' => F12m::where('f121', 6)->count(),
                            'lainnya' => F12m::where('f121', 7)->count(),
                            'f14-1' => F14m::where('f14', 1)->count(),
                            'f14-2' => F14m::where('f14', 2)->count(),
                            'f14-3' => F14m::where('f14', 3)->count(),
                            'f14-4' => F14m::where('f14', 4)->count(),
                            'f14-5' => F14m::where('f14', 5)->count(),
                            'f15-1' => F15m::where('f15', 1)->count(),
                            'f15-2' => F15m::where('f15', 2)->count(),
                            'f15-3' => F15m::where('f15', 3)->count(),
                            'f15-4' => F15m::where('f15', 4)->count(),
                            'kerja' => F8m::where('f8', 1)->count(),
                            'belumkerja' => F8m::where('f8', 2)->count(),
                            'lebih18' => F5m::select(DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select(DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select(DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::where('f8a', 1)->count(),
                            'nasional' => F8a::where('f8a', 2)->count(),
                            'internasional' => F8a::where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->get()->avg('total'),

                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi all,  tahun angkatan all, tahun lulus pilih OK
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                } else {
                    if ($tahunlulus == 0) {
                        //odin, prodi all,  tahun angkatan pilih, tahun lulus all
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('angkatan', $tahunangkatan)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('angkatan', $tahunangkatan)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('angkatan', $tahunangkatan)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('angkatan', $tahunangkatan)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('angkatan', $tahunangkatan)->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('angkatan', $tahunangkatan)->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('angkatan', $tahunangkatan)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('angkatan', $tahunangkatan)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('angkatan', $tahunangkatan)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('angkatan', $tahunangkatan)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('angkatan', $tahunangkatan)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('angkatan', $tahunangkatan)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('angkatan', $tahunangkatan)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('angkatan', $tahunangkatan)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('angkatan', $tahunangkatan)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('angkatan', $tahunangkatan)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('angkatan', $tahunangkatan)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('angkatan', $tahunangkatan)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('angkatan', $tahunangkatan)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('angkatan', $tahunangkatan)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('angkatan', $tahunangkatan)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('angkatan', $tahunangkatan)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('angkatan', $tahunangkatan)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('angkatan', $tahunangkatan)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('angkatan', $tahunangkatan)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('angkatan', $tahunangkatan)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('angkatan', $tahunangkatan)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi all,  tahun angkatan pilih, tahun lulus pilih
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                }
            } else {
                //odin, prodi pilih,  tahun angkatan all, tahun lulus all
                if ($tahunangkatan == 0) {
                    if ($tahunlulus == 0) {
                        //odin, prodi pilih,  tahun angkatan all, tahun lulus all
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('prodim_id', $prodi)->where('status', 99)->where('prodim_id', $prodi)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('prodim_id', $prodi)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('prodim_id', $prodi)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('prodim_id', $prodi)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('prodim_id', $prodi)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('prodim_id', $prodi)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('prodim_id', $prodi)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('prodim_id', $prodi)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('prodim_id', $prodi)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('prodim_id', $prodi)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f121', 1)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 2)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 3)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 4)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 5)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 6)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 7)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f8', 1)->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f8', 2)->count(),
                            'lebih18' => F5m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*', DB::raw('f502+f503 as total'))
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*', DB::raw('f502+f503 as total'))
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*', DB::raw('f502+f503 as total'))
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)->where('prodim_id', $prodi)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 1)->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 3)->count(),
                            'gajimax' => F13m::select('mahasiswams.*', DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('prodim_id', $prodi)
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select('mahasiswams.*', DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('prodim_id', $prodi)
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select('mahasiswams.*', DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('prodim_id', $prodi)
                                ->where('f8ms.f8', 1)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi pilih,  tahun angkatan all, tahun lulus pilih
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus', 'angkatan')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                } else {
                    //odin, prodi pilih,  tahun angkatan pilih, tahun lulus all
                    if ($tahunlulus == 0) {
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 99)->count(),
                            'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi all,  tahun angkatan pilih, tahun lulus pilih
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'ipkmax' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                }
            }
        } elseif (Auth::user()->hasRole('dekan')) {
            if ($prodi == 1) {
                if ($tahunangkatan == 0) {
                    if ($tahunlulus == 0) {
                        //odin, prodi all,  tahun angkatan all, tahun lulus all OK
                        $data = [
                            'semuamahasiswa' => Mahasiswam::count(),
                            'mahasiswabelumisi' => Mahasiswam::where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::avg('ipk'),
                            'biayasendiri' => F12m::where('f121', 1)->count(),
                            'adik' => F12m::where('f121', 2)->count(),
                            'bidikmisi' => F12m::where('f121', 3)->count(),
                            'ppa' => F12m::where('f121', 4)->count(),
                            'afirmasi' => F12m::where('f121', 5)->count(),
                            'perusahaan' => F12m::where('f121', 6)->count(),
                            'lainnya' => F12m::where('f121', 7)->count(),
                            'f14-1' => F14m::where('f14', 1)->count(),
                            'f14-2' => F14m::where('f14', 2)->count(),
                            'f14-3' => F14m::where('f14', 3)->count(),
                            'f14-4' => F14m::where('f14', 4)->count(),
                            'f14-5' => F14m::where('f14', 5)->count(),
                            'f15-1' => F15m::where('f15', 1)->count(),
                            'f15-2' => F15m::where('f15', 2)->count(),
                            'f15-3' => F15m::where('f15', 3)->count(),
                            'f15-4' => F15m::where('f15', 4)->count(),
                            'kerja' => F8m::where('f8', 1)->count(),
                            'belumkerja' => F8m::where('f8', 2)->count(),
                            'lebih18' => F5m::select(DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select(DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select(DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::where('f8a', 1)->count(),
                            'nasional' => F8a::where('f8a', 2)->count(),
                            'internasional' => F8a::where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->get()->avg('total'),

                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi all,  tahun angkatan all, tahun lulus pilih OK
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                } else {
                    if ($tahunlulus == 0) {
                        //odin, prodi all,  tahun angkatan pilih, tahun lulus all
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('angkatan', $tahunangkatan)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('angkatan', $tahunangkatan)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('angkatan', $tahunangkatan)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('angkatan', $tahunangkatan)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('angkatan', $tahunangkatan)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('angkatan', $tahunangkatan)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('angkatan', $tahunangkatan)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('angkatan', $tahunangkatan)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('angkatan', $tahunangkatan)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('angkatan', $tahunangkatan)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('angkatan', $tahunangkatan)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('angkatan', $tahunangkatan)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('angkatan', $tahunangkatan)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('angkatan', $tahunangkatan)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('angkatan', $tahunangkatan)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('angkatan', $tahunangkatan)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('angkatan', $tahunangkatan)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('angkatan', $tahunangkatan)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('angkatan', $tahunangkatan)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('angkatan', $tahunangkatan)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('angkatan', $tahunangkatan)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('angkatan', $tahunangkatan)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('angkatan', $tahunangkatan)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('angkatan', $tahunangkatan)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('angkatan', $tahunangkatan)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi all,  tahun angkatan pilih, tahun lulus pilih
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('tahun_lulus', $tahunlulus)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('angkatan', $tahunangkatan)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                }
            } else {
                //odin, prodi pilih,  tahun angkatan all, tahun lulus all
                if ($tahunangkatan == 0) {
                    if ($tahunlulus == 0) {
                        //odin, prodi pilih,  tahun angkatan all, tahun lulus all
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('prodim_id', $prodi)->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('prodim_id', $prodi)->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('prodim_id', $prodi)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('prodim_id', $prodi)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('prodim_id', $prodi)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('prodim_id', $prodi)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('prodim_id', $prodi)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('prodim_id', $prodi)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('prodim_id', $prodi)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('prodim_id', $prodi)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('prodim_id', $prodi)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f121', 1)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 2)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 3)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 4)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 5)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 6)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f121', 7)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f8', 1)->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)->where('f8', 2)->count(),
                            'lebih18' => F5m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*', DB::raw('f502+f503 as total'))
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*', DB::raw('f502+f503 as total'))
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*', DB::raw('f502+f503 as total'))
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('f8ms.f8', 1)->where('prodim_id', $prodi)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 1)->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 3)->count(),
                            'gajimax' => F13m::select('mahasiswams.*', DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('prodim_id', $prodi)
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select('mahasiswams.*', DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('prodim_id', $prodi)
                                ->where('f8ms.f8', 1)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select('mahasiswams.*', DB::raw('f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->where('prodim_id', $prodi)
                                ->where('f8ms.f8', 1)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi pilih,  tahun angkatan all, tahun lulus pilih
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus', 'angkatan')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'daftarangkatan' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                            'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                } else {
                    //odin, prodi pilih,  tahun angkatan pilih, tahun lulus all
                    if ($tahunlulus == 0) {
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 99)->count(),
                            'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->avg('ipk'),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('prodim_id', $prodi)
                                ->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('prodim_id', $prodi)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    } else {
                        //odin, prodi all,  tahun angkatan pilih, tahun lulus pilih
                        $data = [
                            'semuamahasiswa' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'mahasiswabelumisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                            'mahasiswasedangisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                            'mahasiswaselesaiisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                            'f14-1' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 1)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-2' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 2)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-3' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 3)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-4' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 4)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f14-5' => F14m::select('mahasiswams.id, mahasiswams.prodim_id, f14ms.*')
                                ->join('mahasiswams', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f14', 5)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-1' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 1)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-2' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 2)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-3' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 3)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'f15-4' => F15m::select('mahasiswams.id, mahasiswams.prodim_id, f15ms.*')
                                ->join('mahasiswams', 'f15ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f15', 4)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'ipkmax' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                            'ipkmin' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                            'ipkavg' => Mahasiswam::where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                            'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 1)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                                ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f121', 7)
                                ->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)->count(),
                            'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 1)
                                ->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                                ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8', 2)
                                ->where('angkatan', $tahunangkatan)
                                ->where('prodim_id', $prodi)
                                ->where('tahun_lulus', $tahunlulus)
                                ->count(),
                            'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 >= 18')
                                ->count(),
                            'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 > 6')
                                ->whereRaw('f502+f503 < 18')
                                ->count(),
                            'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                                ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->whereRaw('f502+f503 <= 6')->count(),
                            'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8a', 1)
                                ->count(),
                            'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 2)->count(),
                            'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                                ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 3)->count(),
                            'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'desc')
                                ->first(),
                            'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->orderBy('total', 'asc')
                                ->first(),
                            'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                                ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                                ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                                ->where('f8ms.f8', 1)
                                ->where('mahasiswams.angkatan', $tahunangkatan)
                                ->where('mahasiswams.tahun_lulus', $tahunlulus)
                                ->get()->avg('total'),
                            //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                            //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                            //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                        ];
                    }
                }
            }
        } elseif (Auth::user()->hasRole('admin')) {
            $prodi = Auth::user()->admin->prodim_id;

            if ($tahunangkatan == 0) {
                if ($tahunlulus == 0) {
                    //odin, prodi pilih,  tahun angkatan all, tahun lulus all
                    $data = [
                        'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->count(),
                        'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', 1)->count(),
                        'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', '>', '1')->where('status', '<', '99')->count(),
                        'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('status', 99)->count(),
                        'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('prodim_id', $prodi)->where('status', 99)->get(),
                        'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('prodim_id', $prodi)->where('status', 99)->get(),
                        'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->orderBy('ipk', 'desc')->first(),
                        'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->orderBy('ipk', 'asc')->first(),
                        'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->avg('ipk'),
                        'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 1)->where('prodim_id', $prodi)->count(),
                        'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('prodim_id', $prodi)->count(),
                        'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('prodim_id', $prodi)->count(),
                        'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('prodim_id', $prodi)->count(),
                        'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('prodim_id', $prodi)->count(),
                        'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('prodim_id', $prodi)->count(),
                        'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 7)
                            ->where('prodim_id', $prodi)->count(),
                        'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 1)
                            ->where('prodim_id', $prodi)
                            ->count(),
                        'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 2)
                            ->where('prodim_id', $prodi)
                            ->count(),
                        'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)

                            ->whereRaw('f502+f503 >= 18')
                            ->count(),
                        'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)

                            ->whereRaw('f502+f503 > 6')
                            ->whereRaw('f502+f503 < 18')
                            ->count(),
                        'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)

                            ->whereRaw('f502+f503 <= 6')->count(),
                        'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8a', 1)
                            ->where('prodim_id', $prodi)

                            ->count(),
                        'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('prodim_id', $prodi)
                            ->where('f8a', 2)->count(),
                        'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('prodim_id', $prodi)
                            ->where('f8a', 3)->count(),
                        'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)

                            ->orderBy('total', 'desc')
                            ->first(),
                        'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)

                            ->orderBy('total', 'asc')
                            ->first(),
                        'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)

                            ->get()->avg('total'),
                        //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                        //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                        //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                    ];
                    //dd($data);
                } else {
                    //odin, prodi pilih,  tahun angkatan all, tahun lulus pilih
                    $data = [
                        'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                        'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                        'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                        'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus', 'angkatan')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                        'daftarangkatan' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                        'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                        'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                        'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                        'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 1)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 7)
                            ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)->count(),
                        'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 1)
                            ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)
                            ->count(),
                        'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 2)
                            ->where('prodim_id', $prodi)->where('tahun_lulus', $tahunlulus)
                            ->count(),
                        'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->whereRaw('f502+f503 >= 18')
                            ->count(),
                        'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->whereRaw('f502+f503 > 6')
                            ->whereRaw('f502+f503 < 18')
                            ->count(),
                        'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->whereRaw('f502+f503 <= 6')->count(),
                        'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8a', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->count(),
                        'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('prodim_id', $prodi)
                            ->where('f8a', 2)->count(),
                        'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('prodim_id', $prodi)
                            ->where('f8a', 3)->count(),
                        'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->orderBy('total', 'desc')
                            ->first(),
                        'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->orderBy('total', 'asc')
                            ->first(),
                        'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->get()->avg('total'),
                        //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                        //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                        //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                    ];
                }
            } else {
                //odin, prodi pilih,  tahun angkatan pilih, tahun lulus all
                if ($tahunlulus == 0) {
                    $data = [
                        'semuamahasiswa' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'mahasiswabelumisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 1)->count(),
                        'mahasiswasedangisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', '>', '1')->where('status', '<', '99')->count(),
                        'mahasiswaselesaiisi' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 99)->count(),
                        'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus', 'angkatan')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 99)->get(),
                        'daftarangkatan' => Mahasiswam::select('status', 'angkatan', 'tahun_lulus')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->where('status', 99)->get(),
                        'ipkmax' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->orderBy('ipk', 'desc')->first(),
                        'ipkmin' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->orderBy('ipk', 'asc')->first(),
                        'ipkavg' => Mahasiswam::where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->avg('ipk'),
                        'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 1)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 7)
                            ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)->count(),
                        'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 1)
                            ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)
                            ->count(),
                        'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 2)
                            ->where('prodim_id', $prodi)->where('angkatan', $tahunangkatan)
                            ->count(),
                        'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->whereRaw('f502+f503 >= 18')
                            ->count(),
                        'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->whereRaw('f502+f503 > 6')
                            ->whereRaw('f502+f503 < 18')
                            ->count(),
                        'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->whereRaw('f502+f503 <= 6')->count(),
                        'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8a', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->count(),
                        'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('prodim_id', $prodi)
                            ->where('f8a', 2)->count(),
                        'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('prodim_id', $prodi)
                            ->where('f8a', 3)->count(),
                        'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->orderBy('total', 'desc')
                            ->first(),
                        'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->orderBy('total', 'asc')
                            ->first(),
                        'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('prodim_id', $prodi)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->get()->avg('total'),
                        //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                        //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                        //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                    ];
                } else {
                    //odin, prodi all,  tahun angkatan pilih, tahun lulus pilih
                    $data = [
                        'semuamahasiswa' => Mahasiswam::where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'mahasiswabelumisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->where('status', 1)->count(),
                        'mahasiswasedangisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->where('status', '>', '1')->where('status', '<', '99')->count(),
                        'mahasiswaselesaiisi' => Mahasiswam::where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->where('status', 99)->count(),
                        'daftartahunlulus' => Mahasiswam::select('status', 'tahun_lulus')->distinct('tahun_lulus')->orderBy('tahun_lulus', 'asc')->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                        'daftarangkatan' => Mahasiswam::select('status', 'angkatan')->distinct('angkatan')->orderBy('angkatan', 'asc')->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->where('status', 99)->get(),
                        'ipkmax' => Mahasiswam::where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'desc')->first(),
                        'ipkmin' => Mahasiswam::where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->orderBy('ipk', 'asc')->first(),
                        'ipkavg' => Mahasiswam::where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->avg('ipk'),
                        'biayasendiri' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 1)->where('angkatan', $tahunangkatan)
                            ->where('mahasiswams.prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'adik' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 2)->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'bidikmisi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 3)->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'ppa' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 4)->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'afirmasi' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 5)->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'perusahaan' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')->where('f121', 6)->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'lainnya' => F12m::select('mahasiswams.id, mahasiswams.prodim_id, f12ms.*')
                            ->join('mahasiswams', 'f12ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f121', 7)
                            ->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)->count(),
                        'kerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 1)
                            ->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)
                            ->count(),
                        'belumkerja' => F8m::select('mahasiswams.id, mahasiswams.prodim_id, f8ms.*')
                            ->join('mahasiswams', 'f8ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8', 2)
                            ->where('angkatan', $tahunangkatan)
                            ->where('prodim_id', $prodi)
                            ->where('tahun_lulus', $tahunlulus)
                            ->count(),
                        'lebih18' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->whereRaw('f502+f503 >= 18')
                            ->count(),
                        'antara618' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->whereRaw('f502+f503 > 6')
                            ->whereRaw('f502+f503 < 18')
                            ->count(),
                        'kurang6' => F5m::select('mahasiswams.*', 'f5ms.*', DB::raw('f502+f503 as total'))
                            ->join('f8ms', 'f5ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f5ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->whereRaw('f502+f503 <= 6')->count(),
                        'lokal' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8a', 1)
                            ->count(),
                        'nasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 2)->count(),
                        'internasional' => F8a::select('mahasiswams.id, mahasiswams.prodim_id, f8as.*')
                            ->join('mahasiswams', 'f8as.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)->where('f8a', 3)->count(),
                        'gajimax' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->orderBy('total', 'desc')
                            ->first(),
                        'gajimin' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->orderBy('total', 'asc')
                            ->first(),
                        'gajiavg' => F13m::select(DB::raw('mahasiswams.*, f8ms.*, f13ms.*, f13ms.f131+f13ms.f132+f13ms.f133 as total'))
                            ->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')
                            ->join('mahasiswams', 'f13ms.mahasiswam_id', '=', 'mahasiswams.id')
                            ->where('f8ms.f8', 1)
                            ->where('mahasiswams.angkatan', $tahunangkatan)
                            ->where('mahasiswams.tahun_lulus', $tahunlulus)
                            ->get()->avg('total'),
                        //'gajimax' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'desc')->first(),
                        //'gajimin' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->orderBy('f131', 'asc')->first(),
                        //'gajiavg' => F13m::select('f8ms.*', 'f13ms.*')->join('f8ms', 'f13ms.mahasiswam_id', '=', 'f8ms.mahasiswam_id')->where('f8ms.f8', 1)->avg('f131'),
                    ];
                }
            }
        } else {
            return '404';
        }
        //dd($data);
        return view('administrasi.tracer.index')->with([
            'data' => $data,
            'prodi' => $prodi,
            'tahunangkatan' => $tahunangkatan,
            'tahunlulus' => $tahunlulus,
        ]);

        /*
        parameter
            $prodi[
                1->semua
                2 prodi yang ada
            ]
            $level[
                1->odin, rektor, humas
                2->dekan
                3->admin
            ]
            $tahunangkatan[
                0->all
                yyyy
            ]
            $tahunlulus[
                0->all
                yyyy
            ]

        */
        /*data mahasiswa
            [
                semuamahasiswa,
                mahasiswa sudah isi tracer,
                mahasiswa sedang isi tracer,
                mahasiswa belum ini tracer,
                ipk-tinggi, ipk-rendah, ipk-rata,

            ]
        */
    }
}
