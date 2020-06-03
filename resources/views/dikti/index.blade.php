@extends('layouts.theme2')
@section('isi')

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">IPK Lulusan</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Tahun Lulus<br></th>
                    <th rowspan="2">Jumlah Lulusan<br></th>
                    <th rowspan="2">Prodi</th>
                    <th colspan="3">IPK</th>
                </tr>
                <tr>
                    <td>Min<br></td>
                    <td>Avg.</td>
                    <td>Max</td>
                </tr>
            </thead>
            <tbody>
                @if($prodi==1)
                @foreach($tahun as $t)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$t->tahun_lulus}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->count()}}</td>
                    <td>Semua</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->max('ipk')}}</td>
                    <td>{{number_format(App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->avg('ipk'),2)}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->min('ipk')}}</td>
                </tr>
                @endforeach
                @else
                @foreach($tahun as $t)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$t->tahun_lulus}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->where('prodim_id', $prodi)->count()}}</td>
                    <td>{{App\Prodim::find($prodi)->nama_prodi}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->where('prodim_id', $prodi)->max('ipk')}}</td>
                    <td>{{number_format(App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->where('prodim_id', $prodi)->avg('ipk'),2)}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->where('prodim_id', $prodi)->min('ipk')}}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Durasi Kelulusan</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th rowspan="2">No<br></th>
                    <th rowspan="2">Angkatan<br></th>
                    <th rowspan="2">Jumlah Lulusan Tercatat<br></th>
                    <th colspan="3">Tercepat</th>
                    <th colspan="3">Terlama<br></th>
                    <th colspan="3">Rerata</th>
                </tr>
                <tr>
                    <td>Tahun</td>
                    <td>Bulan<br></td>
                    <td>Hari</td>
                    <td>Tahun</td>
                    <td>Bulan</td>
                    <td>Hari</td>
                    <td>Tahun</td>
                    <td>Bulan</td>
                    <td>Hari</td>
                </tr>
            </thead>
            <tbody>
                @if($prodi==1)
                @foreach($angkatan as $a)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$a->angkatan}}</td>
                    <td>{{App\Mahasiswam::where('angkatan', $a->angkatan)->count()}}</td>
                    <td>{{App\Mahasiswam::tercepat($a->angkatan, 'tahun', $prodi)}}</td>
                    <td>{{App\Mahasiswam::tercepat($a->angkatan, 'bulan', $prodi)}}</td>
                    <td>{{App\Mahasiswam::tercepat($a->angkatan, 'hari', $prodi)}}</td>
                    <td>{{App\Mahasiswam::terlama($a->angkatan, 'tahun', $prodi)}}</td>
                    <td>{{App\Mahasiswam::terlama($a->angkatan, 'bulan', $prodi)}}</td>
                    <td>{{App\Mahasiswam::terlama($a->angkatan, 'hari', $prodi)}}</td>
                    <td>{{App\Mahasiswam::rerata($a->angkatan, 'durasi_tahun', $prodi)}}</td>
                    <td>{{App\Mahasiswam::rerata($a->angkatan, 'durasi_bulan', $prodi)}}</td>
                    <td>{{App\Mahasiswam::rerata($a->angkatan, 'durasi_hari', $prodi)}}</td>
                </tr>
                @endforeach
                @else
                @foreach($angkatan as $a)
                <tr><td>{{$loop->iteration}}</td>
                    <td>{{$a->angkatan}}</td>
                    <td>{{App\Mahasiswam::where('angkatan', $a->angkatan)->where('prodim_id', $prodi)->count()}}</td>
                    <td>{{App\Mahasiswam::tercepat($a->angkatan, 'tahun', $prodi)}}</td>
                    <td>{{App\Mahasiswam::tercepat($a->angkatan, 'bulan', $prodi)}}</td>
                    <td>{{App\Mahasiswam::tercepat($a->angkatan, 'hari', $prodi)}}</td>
                    <td>{{App\Mahasiswam::terlama($a->angkatan, 'tahun', $prodi)}}</td>
                    <td>{{App\Mahasiswam::terlama($a->angkatan, 'bulan', $prodi)}}</td>
                    <td>{{App\Mahasiswam::terlama($a->angkatan, 'hari', $prodi)}}</td>
                    <td>{{App\Mahasiswam::rerata($a->angkatan, 'durasi_tahun', $prodi)}}</td>
                    <td>{{App\Mahasiswam::rerata($a->angkatan, 'durasi_bulan', $prodi)}}</td>
                    <td>{{App\Mahasiswam::rerata($a->angkatan, 'durasi_hari', $prodi)}}</td>

                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Prestasi Akademik</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Kegiatan<br></th>
                    <th rowspan="2">Prodi</th>
                    <th colspan="3">Tingkat</th>
                    <th rowspan="2">Prestasi yang Dicapai<br></th>
                </tr>
                <tr>
                    <td>Lokal</td>
                    <td>Nasional</td>
                    <td>Internasional<br></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Prestasi Non Akademik</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Kegiatan<br></th>
                    <th rowspan="2">Prodi</th>
                    <th colspan="3">Tingkat</th>
                    <th rowspan="2">Prestasi yang Dicapai<br></th>
                </tr>
                <tr>
                    <td>Lokal</td>
                    <td>Nasional</td>
                    <td>Internasional<br></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
{{--}}
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Masa Studi Lulusan</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th rowspan="2">Tahun Masuk</th>
                    <th rowspan="2">Jumlah Mahasiswa Diterima</th>
                    <th colspan="7">Tingkat</th>
                    <th rowspan="2">Jumlah Lulusan s.d. akhir TS</th>
                    <th rowspan="2">Rata-rata Masa Studi</th>
                </tr>
                <tr>
                    <td>Akhir TS-6</td>
                    <td>Akhir TS-5</td>
                    <td>Akhir TS-4</td>
                    <td>Akhir TS-3</td>
                    <td>Akhir TS-2</td>
                    <td>Akhir TS-1</td>
                    <td>Akhir TS</td>
                </tr>
            </thead>
            <tbody>
                @foreach($angkatan as $a)
                <tr>
                    <td>{{$a->angkatan}}</td>
                    <td>{{App\Mahasiswam::where('angkatan', $a->angkatan)->count()}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        footer
    </div>
    <!-- /.box-footer-->
</div>
{{--}}

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Waktu Tunggu Lulusan</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th rowspan="2">Tahun lulus<br></th>
                    <th rowspan="2">Jumlah Lulusan<br></th>
                    <th rowspan="2">Jumlah Lulusan Tercatat<br></th>
                    <th colspan="3">Jumlah lulusan dengan waktu tunggu mendapatkan pekerjaan<br></th>
                </tr>
                <tr>
                    <td>WT &lt; 6<br></td>
                    <td>WT &gt; 6 dan &lt;18</td>
                    <td>WT &gt; 18<br></td>
                </tr>
            </thead>
            <tbody>
                @if($prodi==1)
                @foreach ($tahun as $t)
                <tr>
                    <td>{{$t->tahun_lulus}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->count()}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus',$t->tahun_lulus)->where('status','99')->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f3ms.*')->join('f3ms', 'f3ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->where('f3ms.f301','1')->where([['f3ms.f302','<=','6']])
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f3ms.*')->join('f3ms', 'f3ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->where('f3ms.f301','1')->where([['f3ms.f302','>','6'],['f3ms.f302','<=','18']])
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f3ms.*')->join('f3ms', 'f3ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->where('f3ms.f301','1')->where([['f3ms.f302','>','18']])
                    ->count()}}</td>
                </tr>
                @endforeach
                @else
                @foreach ($tahun as $t)
                <tr>
                    <td>{{$t->tahun_lulus}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->where('prodim_id', $prodi)->count()}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus',$t->tahun_lulus)->where('prodim_id', $prodi)->where('status','99')->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f3ms.*')->join('f3ms', 'f3ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->where('f3ms.f301','1')->where([['f3ms.f302','<=','6']])
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f3ms.*')->join('f3ms', 'f3ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->where('f3ms.f301','1')->where([['f3ms.f302','>','6'],['f3ms.f302','<=','18']])
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f3ms.*')->join('f3ms', 'f3ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->where('f3ms.f301','1')->where([['f3ms.f302','>','18']])
                    ->count()}}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Kesesuaian Bidang Kerja Lulusan</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th rowspan="2">Tahun lulus<br></th>
                    <th rowspan="2">Jumlah Lulusan<br></th>
                    <th rowspan="2">Jumlah Lulusan Tercatat<br></th>
                    <th colspan="3">Jumlah lulusan dengan tingkat keseuaian bidang kerja<br></th>
                </tr>
                <tr>
                    <td>Rendah<br></td>
                    <td>Sedang<br></td>
                    <td>Tinggi<br></td>
                </tr>
            </thead>
            <tbody>
                @if($prodi==1)
                @foreach($tahun as $t)
                <tr>
                    <td>{{$t->tahun_lulus}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->count()}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus',$t->tahun_lulus)->where('status','99')->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f14ms.*')->join('f14ms', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f14ms.f14',[0,1,2])
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f14ms.*')->join('f14ms', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->where('f14ms.f14','3')
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f14ms.*')->join('f14ms', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f14ms.f14',[4,5])
                    ->count()}}</td>
                </tr>
                @endforeach
                @else
                @foreach($tahun as $t)
                <tr>
                    <td>{{$t->tahun_lulus}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->where('prodim_id', $prodi)->count()}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus',$t->tahun_lulus)->where('prodim_id', $prodi)->where('status','99')->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f14ms.*')->join('f14ms', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('prodim_id', $prodi)->where('status','99')->whereIn('f14ms.f14',[0,1,2])
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f14ms.*')->join('f14ms', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('prodim_id', $prodi)->where('status','99')->where('f14ms.f14','3')
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f14ms.*')->join('f14ms', 'f14ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('prodim_id', $prodi)->where('status','99')->whereIn('f14ms.f14',[4,5])
                    ->count()}}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->

</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Tempat Kerja Lulusan</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th rowspan="2">Tahun lulus</th>
                    <th rowspan="2">Jumlah Lulusan</th>
                    <th rowspan="2">Jumlah Lulusan Tercata</th>
                    <th rowspan="2">Jumlah Lulusan yang Telah Bekerja Berwirausaha</th>
                    <th colspan="3">Jumlah Lulusan yang Bekerja Berdasarkan Tingkat/Ukuran Tempat Kerja/Berwirausah</th>
                </tr>
                <tr>
                    <td>Lokal Wilayah Berwirausah tidak Berbadan Hukum</td>
                    <td>Nasional Berwirausaha Berbadan Hukum</td>
                    <td>Multinasional/Internasiona</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        footer
    </div>
    <!-- /.box-footer-->
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Kepuasan Pengguna Lulusan</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th rowspan="3">No</th>
                    <th rowspan="3">Tahun Lulus<br></th>
                    <th rowspan="3">Jumlah Lulusan<br></th>
                    <th rowspan="3">Jumlah Lulusan Tercatat</th>
                    <th colspan="21">Jumlah Tanggapan Kepuasan Pengguna yang Terlacak</th>
                </tr>
                <tr>
                    <th colspan="3">Perkuliahan</th>
                    <th colspan="3">Demonstrasi</th>
                    <th colspan="3">partisipasi proyek riset</th>
                    <th colspan="3">magang</th>
                    <th colspan="3">praktikum</th>
                    <th colspan="3">PKL</th>
                    <th colspan="3">Diskusi</th>
                </tr>
                <tr>
                    <th>SB</th>
                    <th>C</th>
                    <th>K</th>
                     <th>SB</th>
                    <th>C</th>
                    <th>K</th>
                     <th>SB</th>
                    <th>C</th>
                    <th>K</th>
                     <th>SB</th>
                    <th>C</th>
                    <th>K</th>
                     <th>SB</th>
                    <th>C</th>
                    <th>K</th>
                     <th>SB</th>
                    <th>C</th>
                    <th>K</th>
                     <th>SB</th>
                    <th>C</th>
                    <th>K</th>
                </tr>
            </thead>
            <tbody>
                @if($prodi==1)
                @foreach($tahun as $t)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$t->tahun_lulus}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->count()}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus',$t->tahun_lulus)->where('status','99')->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f21',[0,1,2] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f21',[3] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f21',[4,5] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f22',[0,1,2] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f22',[3] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f22',[4,5] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f23',[0,1,2] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f23',[3] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f23',[4,5] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f24',[0,1,2] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f24',[3] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f24',[4,5] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f25',[0,1,2] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f25',[3] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f25',[4,5] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f26',[0,1,2] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f26',[3] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f26',[4,5] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f27',[0,1,2] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f27',[3] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f27',[4,5] )
                    ->count()}}</td>
                </tr>
                @endforeach
                @else
                @foreach($tahun as $t)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$t->tahun_lulus}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $t->tahun_lulus)->count()}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f21',[0,1,2] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f21',[3] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f21',[4,5] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f22',[0,1,2] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f22',[3] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f22',[4,5] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f23',[0,1,2] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f23',[3] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f23',[4,5] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f24',[0,1,2] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f24',[3] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f24',[4,5] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f25',[0,1,2] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f25',[3] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f25',[4,5] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f26',[0,1,2] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f26',[3] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f26',[4,5] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f27',[0,1,2] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f27',[3] )
                    ->count()}}</td>
                    <td>{{App\Mahasiswam::select('mahasiswams.*', 'f2ms.*')->join('f2ms', 'f2ms.mahasiswam_id', '=', 'mahasiswams.id')
                    ->where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->whereIn('f2ms.f27',[4,5] )
                    ->count()}}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>

@endsection
