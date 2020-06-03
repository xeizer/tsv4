<?php

namespace App\Http\Controllers;

use App\Imports\dikti;
use Illuminate\Http\Request;
use App\Mahasiswam;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class DiktiController extends Controller
{
    //
    public function laporandikti($prodi)
    {
        if (Auth::user()->hasRole('odin|rektor|humas|dekan')) {
            if ($prodi == 1) {
                $tahun = Mahasiswam::select('tahun_lulus')->distinct()->get();
                $angkatan = Mahasiswam::select('angkatan')->distinct()->get();
            } else {
                $tahun = Mahasiswam::select('tahun_lulus')->where('prodim_id', $prodi)->distinct()->get();
                $angkatan = Mahasiswam::select('angkatan')->where('prodim_id', $prodi)->distinct()->get();
            }

            $jumlahlulusantahun = [];
            foreach ($tahun as $t) {
                $jumlahlulusantahun[] = Mahasiswam::select('tahun_lulus')->where('tahun_lulus', $t->tahun_lulus)->count();
            }
        } elseif (Auth::user()->hasRole('admin')) {

            $tahun = Mahasiswam::select('tahun_lulus')->where('prodim_id', Auth::user()->admin->prodim_id)->distinct()->get();
            $jumlahlulusantahun = [];
            foreach ($tahun as $t) {
                $jumlahlulusantahun[] = Mahasiswam::select('tahun_lulus')->where('prodim_id', Auth::user()->admin->prodim_id)->where('tahun_lulus', $t->tahun_lulus)->count();
            }
            $angkatan = Mahasiswam::select('angkatan')->where('prodim_id', Auth::user()->admin->prodim_id)->distinct()->get();
        }


        if (Auth::user()->hasRole('odin|rektor|humas|dekan')) {
            return view('dikti.index')->with([
                'angkatan' => $angkatan,
                'jtl' => $jumlahlulusantahun,
                'tahun' => $tahun,
                'active' => '7',
                'subactive' => 'laporandikti',
                'title' => 'Laporan Dikti',
                'subtitle' => 'Laporan Dikti ',
                'prodi' => $prodi,
            ]);
        } elseif (Auth::user()->hasRole('admin')) {
            return view('dikti.index')->with([
                'angkatan' => $angkatan,
                'prodi' => $prodi,
                'jtl' => $jumlahlulusantahun,
                'tahun' => $tahun,
                'active' => '7',
                'subactive' => 'laporandikti',
                'title' => 'Laporan Dikti',
                'subtitle' => 'Laporan Dikti ',
            ]);
        }
    }
    public function importdikti()
    {

        return view('dikti.importdikti')->with([
            'active' => 'option1',
            'subactive' => 'Import Dikti',
            'title' => 'Import Dikti',
            'subtitle' => 'Import Dikti ',
        ]);
    }
    public function import_dikti(Request $request)
    {
        Excel::Import(new dikti, request()->file('file'));
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "ok"
        ]);
        return back();
    }
}
