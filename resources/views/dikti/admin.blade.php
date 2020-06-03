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
                @foreach($tahun as $t)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$t->tahun_lulus}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->where('prodim_id', Auth::user()->admin->prodim_id)->count()}}</td>
                    <td></td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->where('prodim_id', Auth::user()->admin->prodim_id)->max('ipk')}}</td>
                    <td>{{number_format(App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->where('prodim_id', Auth::user()->admin->prodim_id)->avg('ipk'),2)}}</td>
                    <td>{{App\Mahasiswam::where('tahun_lulus', $t->tahun_lulus)->where('prodim_id', Auth::user()->admin->prodim_id)->min('ipk')}}</td>
                </tr>
                @endforeach
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
                @foreach($angkatan as $a)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$a->angkatan}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', Auth::user()->admin->prodim_id)->where('angkatan', $a->angkatan)->count()}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', Auth::user()->admin->prodim_id)->tercepat($a->angkatan, 'tahun')}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', Auth::user()->admin->prodim_id)->tercepat($a->angkatan, 'bulan')}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', Auth::user()->admin->prodim_id)->tercepat($a->angkatan, 'hari')}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', Auth::user()->admin->prodim_id)->terlama($a->angkatan, 'tahun')}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', Auth::user()->admin->prodim_id)->terlama($a->angkatan, 'bulan')}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', Auth::user()->admin->prodim_id)->terlama($a->angkatan, 'hari')}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', Auth::user()->admin->prodim_id)->rerata($a->angkatan, 'durasi_tahun')}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', Auth::user()->admin->prodim_id)->rerata($a->angkatan, 'durasi_bulan')}}</td>
                    <td>{{App\Mahasiswam::where('prodim_id', Auth::user()->admin->prodim_id)->rerata($a->angkatan, 'durasi_hari')}}</td>
                </tr>
                @endforeach
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
                    <td>WT &lt; 6 dan &gt;18</td>
                    <td>WT &gt; 18<br></td>
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
                </tr>
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
                <tr>
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
                    <th>No</th>
                    <th>Tahun Lulus<br></th>
                    <th>Jumlah Lulusan<br></th>
                    <th>Jumlah Tanggapan Kepuasan Pengguna yang Terlacak</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>

@endsection
