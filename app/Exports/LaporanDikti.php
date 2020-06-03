<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
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
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanDikti implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function  __construct($prodi)
    {
        $this->prodi = $prodi;
    }
    public function view(): View
    {

        $data = Mahasiswam::where('status', '99')->where('prodim_id', $this->prodi)->get();
        return view('administrasi.laporan.index')->with([
            'data' => $data,
        ]);
    }
}
