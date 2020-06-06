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
                    <th rowspan="2"><center>No</th>
                    <th rowspan="2"><center>Tahun Lulus<br></th>
                    <th rowspan="2"><center>Jumlah Lulusan<br></th>
                    <th rowspan="2"><center>Program Studi</th>
                    <th colspan="3"><center>IPK</th>
                </tr>
                <tr>
                    <td><center>Min<br></td>
                    <td><center>Rerata</td>
                    <td><center>Max</td>
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
                    <th rowspan="2"><center>No<br></th>
                    <th rowspan="2"><center>Angkatan<br></th>
                    <th rowspan="2"><center>Jumlah Lulusan Tercatat<br></th>
                    <th colspan="3"><center>Tercepat</th>
                    <th colspan="3"><center>Terlama<br></th>
                    <th colspan="3"><center>Rerata</th>
                </tr>
                <tr>
                    <td><center>Tahun</td>
                    <td><center>Bulan<br></td>
                    <td><center>Hari</td>
                    <td><center>Tahun</td>
                    <td><center>Bulan</td>
                    <td><center>Hari</td>
                    <td><center>Tahun</td>
                    <td><center>Bulan</td>
                    <td><center>Hari</td>
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
                    <th rowspan="2"><center>No</th>
                    <th rowspan="2"><center>Nama Kegiatan<br></th>
                    <th rowspan="2"><center>Prodi</th>
                    <th colspan="3"><center>Tingkat</th>
                    <th rowspan="2"><center>Prestasi yang Dicapai<br></th>
                </tr>
                <tr>
                    <td><center>Lokal</td>
                    <td><center>Nasional</td>
                    <td><center>Internasional<br></td>
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
                    <th rowspan="2"><center>No</th>
                    <th rowspan="2"><center>Nama Kegiatan<br></th>
                    <th rowspan="2"><center>Prodi</th>
                    <th colspan="3"><center>Tingkat</th>
                    <th rowspan="2"><center>Prestasi yang Dicapai<br></th>
                </tr>
                <tr>
                    <td><center>Lokal</td>
                    <td><center>Nasional</td>
                    <td><center>Internasional<br></td>
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
                    <th rowspan="2" style="vertical-align: middle; text-align: center">Tahun lulus<br></th>
                    <th rowspan="2" style="vertical-align: middle; text-align: center">Jumlah Lulusan<br></th>
                    <th rowspan="2" style="vertical-align: middle; text-align: center">Jumlah Lulusan Tercatat<br></th>
                    <th colspan="3" style="vertical-align: middle; text-align: center">Jumlah lulusan dengan waktu tunggu mendapatkan pekerjaan<br></th>
                </tr>
                <tr>
                    <td style="vertical-align: middle; text-align: center">WT &lt; 6<br>Sebelum Lulus</td>
                    <td style="vertical-align: middle; text-align: center">WT &gt; 6 dan &lt;18<br>Sebelum Lulus</td>
                    <td style="vertical-align: middle; text-align: center">WT &gt; 18<br>Sebelum Lulus</td>
                </tr>
            </thead>
            <tbody>
                @if($prodi==1)
                @foreach ($tahun as $t)
                <tr>
                    <td>{{$t->tahun_lulus}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->count()}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus',$t->tahun_lulus)->where('status','99')->count()}}</td>
                    <td>{{App\Mahasiswam::where('status', 99)->where('tahun_lulus', $t->tahun_lulus)->whereHas('f3', function ($q){
                        $q->where('f301',1)->where('f302','<',6);
                    })->count()}}
                    </td>
                    <td>{{App\Mahasiswam::where('status', 99)->where('tahun_lulus', $t->tahun_lulus)->whereHas('f3', function ($q){
                        $q->where('f301',1)->where('f302','>=',6)->where('f302','<=',18);
                    })->count()}}</td>
                    <td>{{App\Mahasiswam::where('status', 99)->where('tahun_lulus', $t->tahun_lulus)->whereHas('f3', function ($q){
                        $q->where('f301',1)->where('f302','>',18);
                    })->count()}}</td>
                </tr>
                @endforeach
                @else
                @foreach ($tahun as $t)
                <tr>
                    <td>{{$t->tahun_lulus}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $t->tahun_lulus)->count()}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->count()}}</td>
                    <td>{{App\Mahasiswam::where('status', 99)->where('prodim_id', $prodi)->where('tahun_lulus', $t->tahun_lulus)->whereHas('f3', function ($q){
                        $q->where('f301',1)->where('f302','<',6);
                    })->count()}}
                    </td>
                    <td>{{App\Mahasiswam::where('status', 99)->where('prodim_id', $prodi)->where('tahun_lulus', $t->tahun_lulus)->whereHas('f3', function ($q){
                        $q->where('f301',1)->where('f302','>=',6)->where('f302','<=',18);
                    })->count()}}</td>
                    <td>{{App\Mahasiswam::where('status', 99)->where('prodim_id', $prodi)->where('tahun_lulus', $t->tahun_lulus)->whereHas('f3', function ($q){
                        $q->where('f301',1)->where('f302','>',18);
                    })->count()}}</td>
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
        <h3 class="box-title">Waktu Tunggu Lulusan</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th rowspan="2" style="vertical-align: middle; text-align: center">Tahun lulus<br></th>
                    <th rowspan="2" style="vertical-align: middle; text-align: center">Jumlah Lulusan<br></th>
                    <th rowspan="2" style="vertical-align: middle; text-align: center">Jumlah Lulusan Tercatat<br></th>
                    <th colspan="3" style="vertical-align: middle; text-align: center">Jumlah lulusan dengan waktu tunggu mendapatkan pekerjaan<br></th>
                </tr>
                <tr>
                    <td style="vertical-align: middle; text-align: center">WT &lt; 6<br>Setelah Lulus</td>
                    <td style="vertical-align: middle; text-align: center">WT &gt; 6 dan &lt;18<br>Setelah Lulus</td>
                    <td style="vertical-align: middle; text-align: center">WT &gt; 18<br>Setelah Lulus</td>
                </tr>
            </thead>
            <tbody>
                @if($prodi==1)
                @foreach ($tahun as $t)
                <tr>
                    <td>{{$t->tahun_lulus}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->count()}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus',$t->tahun_lulus)->where('status','99')->count()}}</td>
                    <td>{{App\Mahasiswam::where('status', 99)->where('tahun_lulus', $t->tahun_lulus)->whereHas('f3', function ($q){
                        $q->where('f301', 2)->where('f301',2);
                    })->count()}}
                    </td>
                    <td>{{App\Mahasiswam::where('status', 99)->where('tahun_lulus', $t->tahun_lulus)->whereHas('f3', function ($q){
                        $q->where('f301', 2)->where('f303','>=',6)->where('f303','<=',18);
                    })->count()}}</td>
                    <td>{{App\Mahasiswam::where('status', 99)->where('tahun_lulus', $t->tahun_lulus)->whereHas('f3', function ($q){
                        $q->where('f301', 2)->where('f303','>',18);
                    })->count()}}</td>
                </tr>
                @endforeach
                @else
                @foreach ($tahun as $t)
                <tr>
                    <td>{{$t->tahun_lulus}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus', $t->tahun_lulus)->count()}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', $prodi)->where('tahun_lulus',$t->tahun_lulus)->where('status','99')->count()}}</td>
                    <td>{{App\Mahasiswam::where('status', 99)->where('prodim_id', $prodi)->where('tahun_lulus', $t->tahun_lulus)->whereHas('f3', function ($q){
                        $q->where('f301', 2)->where('f303','<',6);
                    })->count()}}
                    </td>
                    <td>{{App\Mahasiswam::where('status', 99)->where('prodim_id', $prodi)->where('tahun_lulus', $t->tahun_lulus)->whereHas('f3', function ($q){
                        $q->where('f301', 2)->where('f303','>=',6)->where('f303','<=',18);
                    })->count()}}</td>
                    <td>{{App\Mahasiswam::where('status', 99)->where('prodim_id', $prodi)->where('tahun_lulus', $t->tahun_lulus)->whereHas('f3', function ($q){
                        $q->where('f301', 2)->where('f303','>',18);
                    })->count()}}</td>
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
                    <th rowspan="2"><center>Tahun lulus<br></th>
                    <th rowspan="2"><center>Jumlah Lulusan<br></th>
                    <th rowspan="2"><center>Jumlah Lulusan Tercatat<br></th>
                    <th colspan="3"><center>Jumlah lulusan dengan tingkat keseuaian bidang kerja<br></th>
                </tr>
                <tr>
                    <td><center>Rendah<br></td>
                    <td><center>Sedang<br></td>
                    <td><center>Tinggi<br></td>
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
                    <th rowspan="2"><center>Tahun lulus</th>
                    <th rowspan="2"><center>Jumlah Lulusan</th>
                    <th rowspan="2"><center>Jumlah Lulusan Tercata</th>
                    <th rowspan="2"><center>Jumlah Lulusan yang Telah Bekerja Berwirausaha</th>
                    <th colspan="3"><center>Jumlah Lulusan yang Bekerja Berdasarkan Tingkat/Ukuran Tempat Kerja/Berwirausah</th>
                </tr>
                <tr>
                    <td><center>Lokal Wilayah Berwirausah tidak Berbadan Hukum</td>
                    <td><center>Nasional Berwirausaha Berbadan Hukum</td>
                    <td><center>Multinasional/Internasiona</td>
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
        <h3 class="box-title">Tanggapan Lulusan Terhadap Metode Pembelajaran</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th rowspan="3"><center>No</th>
                    <th rowspan="3"><center>Tahun Lulus<br></th>
                    <th rowspan="3"><center>Jumlah Lulusan<br></th>
                    <th rowspan="3"><center>Jumlah Lulusan Tercatat</th>
                    <th colspan="21"><center>Aspek Tanggapan</th>
                </tr>
                <tr>
                    <th colspan="3"><center>Perkuliahan</th>
                    <th colspan="3"><center>Demonstrasi</th>
                    <th colspan="3"><center>Partisipasi proyek riset</th>
                    <th colspan="3"><center>Magang</th>
                    <th colspan="3"><center>Praktikum</th>
                    <th colspan="3"><center>Kerja Lapangan</th>
                    <th colspan="3"><center>Diskusi</th>
                </tr>
                <tr>
                    <th>B</th>
                    <th>C</th>
                    <th>K</th>
                     <th>B</th>
                    <th>C</th>
                    <th>K</th>
                     <th>B</th>
                    <th>C</th>
                    <th>K</th>
                     <th>B</th>
                    <th>C</th>
                    <th>K</th>
                     <th>B</th>
                    <th>C</th>
                    <th>K</th>
                     <th>B</th>
                    <th>C</th>
                    <th>K</th>
                     <th>B</th>
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
