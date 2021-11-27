@extends('layouts.theme3')
@section('isi')
@if((!isset($data))||($data['mahasiswaselesaiisi']==0))
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
            <strong>Sepertinya belum ada mahasiswa yang mengisi tracerstudi pada prodi ini</strong>
        </div>
        <div class="alert alert-danger" role="alert">
            <strong>Sepertinya belum ada mahasiswa yang mengisi tracerstudi pada Tahun Lulus ini</strong>
        </div>
        <div class="alert alert-danger" role="alert">
            <strong>Sepertinya belum ada mahasiswa yang mengisi tracerstudi pada Tahun Angkatan ini</strong>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Semua Mahasiswa</span>
                <span class="info-box-number">{{$data['semuamahasiswa']}} <small>orang</small></span>
                <span class="info-box-more">-</span>
            </div>
        <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Selesai Mengisi</span>
                <span class="info-box-number">{{$data['mahasiswaselesaiisi']}} <small>orang</small></span>
                <span class="info-box-more">-</span>
            </div>
        <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-pencil"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">Sedang Mengisi</span>
            <span class="info-box-number">{{$data['mahasiswasedangisi']}} <small>orang</small></span>
            <span class="info-box-more">-</span>
        </div>
        <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-close"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">Belum Mengisi</span>
            <span class="info-box-number">{{$data['mahasiswabelumisi']}} <small>orang</small></span>
            <span class="info-box-more">-</span>
        </div>
        <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- ./col -->
</div>
<div class="row">
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-header">
                <div class="box-title">Berdasar tahun Lulus dan Angkatan</div>
                <div class="box-body">
                    <form action="{{route('statistik.tracer2publik')}}" method="POST">
                        @csrf
                        <input type="hidden" name="prodi" value="{{$prodi}}">
                        <table class="table">
                            <tr>
                                <td>
                                    Pilih Tahun Lulus
                                </td>
                                <td>
                                    <select class="form-control" name="tahunlulus">
                                        <option value="0">semua</option>
                                        @if($prodi==1)
                                            @foreach(App\Mahasiswam::select('tahun_lulus')->distinct()->where('status', 99)->get() as $d)
                                            <option value="{{$d->tahun_lulus}}" {{$tahunlulus==$d->tahun_lulus ? "selected" : ""}}>{{$d->tahun_lulus}}</option>
                                            @endforeach
                                        @else
                                            @foreach(App\Mahasiswam::select('tahun_lulus')->where('prodim_id', $prodi)->distinct()->where('status', 99)->get() as $d)
                                            <option value="{{$d->tahun_lulus}}" {{$tahunlulus==$d->tahun_lulus ? "selected" : ""}}>{{$d->tahun_lulus}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Pilih Angkatan
                                </td>
                                <td>
                                    <select class="form-control" name="tahunangkatan">
                                        <option value="0">semua</option>
                                        @if($prodi==1)
                                            @foreach(App\Mahasiswam::select('angkatan')->distinct()->where('status', 99)->get() as $d)
                                            <option value="{{$d->angkatan}}" {{$tahunangkatan==$d->angkatan ? "selected" : ""}}>{{$d->angkatan}}</option>
                                            @endforeach
                                        @else
                                            @foreach(App\Mahasiswam::select('angkatan')->where('prodim_id', $prodi)->distinct()->where('status', 99)->get() as $d)
                                            <option value="{{$d->angkatan}}" {{$tahunangkatan==$d->angkatan ? "selected" : ""}}>{{$d->angkatan}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><button class="btn btn-primary" type="submit">Lihat Data</td>
                            </tr>

                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-header">
                <div class="box-title">Berdasar tahun Lulus dan Angkatan</div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">Fakultas: {{App\Prodim::with('fakultas')->where('id', $prodi)->first()->fakultas->nama_fakultas}}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="alert alert-info">Prodi:  {{ App\Prodim::find($prodi)->nama_prodi}}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info">Tahun lulus: {{$tahunlulus ==0 ? 'Semua Tahun': $tahunlulus}}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info">Tahun angkatan: {{$tahunangkatan ==0 ? 'Semua Tahun': $tahunangkatan}}</div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-title">
                    IPK
                </div>
            </div>
            <div class="box-body">
                Tertinggi <a class="text-green"> NIM: {{$data['ipkmax']->user->nim}}-{{$data['ipkmax']->user->name}}-{{$data['ipkmax']->prodi->slug_prodi}}</a>
                <div class="progress">
                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                        aria-valuenow="3" aria-valuemin="0" aria-valuemax="4" style="width: {{$data['ipkmax']->ipk/4*100}}%">{{$data['ipkmax']->ipk}}</div>
                </div>
                Terendah <a class="text-red">NIM: {{$data['ipkmin']->user->nim}}-{{$data['ipkmin']->user->name}}-{{$data['ipkmin']->prodi->slug_prodi}}</a>

                <div class="progress">
                    <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"
                        aria-valuenow="3" aria-valuemin="0" aria-valuemax="4" style="width: {{$data['ipkmin']->ipk/4*100}}%">{{$data['ipkmin']->ipk}}</div>
                </div>
                Rata-Rata
                <div class="progress">
                    <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
                        aria-valuenow="3" aria-valuemin="0" aria-valuemax="4" style="width: {{$data['ipkavg']/4*100}}%">{{number_format($data['ipkavg'],2)}}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Sumber Dana Kuliah</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-condensed table-hover">
                    <tr>
                        <td>Biaya Sendiri</td>
                        <td>{{$data['biayasendiri']}} Orang</td>
                        <td>{{number_format($data['biayasendiri']/ $data['mahasiswaselesaiisi'] * 100,2)}}%</td>
                    </tr>
                    <tr>
                        <td>ADIK</td>
                        <td>{{$data['adik']}} Orang</td>
                        <td>{{number_format($data['adik']/ $data['mahasiswaselesaiisi'] * 100,2)}}%</td>
                    </tr>
                    <tr>
                        <td>BIDIKMISI</td>
                        <td>{{$data['bidikmisi']}} Orang</td>
                        <td>{{number_format($data['bidikmisi']/ $data['mahasiswaselesaiisi'] * 100,2)}}%</td>
                    </tr>
                    <tr>
                        <td>PPA</td>
                        <td>{{$data['ppa']}} Orang</td>
                        <td>{{number_format($data['ppa']/ $data['mahasiswaselesaiisi'] * 100,2)}}%</td>
                    </tr>
                    <tr>
                        <td>AFIRMASI</td>
                        <td>{{$data['afirmasi']}} Orang</td>
                        <td>{{number_format($data['afirmasi']/ $data['mahasiswaselesaiisi'] * 100,2)}}%</td>
                    </tr>
                    <tr>
                        <td>Perusahaan/Swasta</td>
                        <td>{{$data['perusahaan']}} Orang</td>
                        <td>{{number_format($data['perusahaan']/ $data['mahasiswaselesaiisi'] * 100,2)}}%</td>
                    </tr>
                    <tr>
                        <td>Lainnya</td>
                        <td>{{$data['lainnya']}} Orang</td>
                        <td>{{number_format($data['lainnya']/ $data['mahasiswaselesaiisi'] * 100,2)}}%</td>
                    </tr>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
            <!-- /.box-footer-->
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header">
                <box class="title">
                    Keselarasan Horizontal
                </box>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tr class="bg-success">
                        <td>Sangat Erat</td>
                        <td>{{$data['f14-1']}}</td>
                        <td rowspan="2" style="vertical-align : middle;text-align:center;">Tinggi<br />{{$data['f14-1']+$data['f14-2']}}</td>
                    </tr>
                    <tr  class="bg-success">
                        <td>Erat</td>
                        <td>{{$data['f14-2']}}</td>

                    </tr>
                    <tr class="bg-warning">
                        <td>Cukup Erat</td>
                        <td>{{$data['f14-3']}}</td>
                        <td style="vertical-align : middle;text-align:center;">x</td>
                    </tr>
                    <tr class="bg-danger">
                        <td>Kurang Erat</td>
                        <td>{{$data['f14-4']}}</td>
                        <td rowspan="2" style="vertical-align : middle;text-align:center;">Rendah<br />{{$data['f14-4']+$data['f14-5']}}</td>
                    </tr>
                    <tr class="bg-danger">
                        <td>Tidak Sama Sekali</td>
                        <td>{{$data['f14-5']}}</td>

                    </tr>
                </table>

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header">
                <box class="title">
                    Keselarasan Vertikal
                </box>
            </div>
            <div class="box-body">
                <table class="table">
                    <tr>
                        <td>Setingkat Lebih Tinggi</td>
                        <td>{{$data['f15-1']}}</td>
                        <td>x</td>
                    </tr>
                    <tr>
                        <td>Tingkat Yang sama</td>
                        <td>{{$data['f15-2']}}</td>
                        <td>x</td>
                    </tr>
                    <tr>
                        <td>Setingkat Lebih Rendah</td>
                        <td>{{$data['f15-3']}}</td>
                        <td>x</td>
                    </tr>
                    <tr>
                        <td>Tidak Perlu Pendidikan Tinggi</td>
                        <td>{{$data['f15-4']}}</td>
                        <td>x</td>
                    </tr>

                </table>

            </div>
        </div>
    </div>
</div>
<div class="row">
    @if($data['kerja']>0)
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Alumni Kerja</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <table class="table">
                    <tr>
                        <td>Bekerja</td>
                        <td>{{$data['kerja']}} Orang</td>
                        <td>{{number_format($data['kerja']/$data['mahasiswaselesaiisi']*100,2)}} %</td>
                    </tr>
                    <tr>
                        <td>Belum Bekerja</td>
                        <td>{{$data['belumkerja']}} Orang</td>
                        <td>{{number_format($data['belumkerja']/$data['mahasiswaselesaiisi']*100, 2)}} %</td>
                    </tr>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
            <!-- /.box-footer-->
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Masa Tunggu Kerja</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">

                <table class="table">
                    <tr>
                        <td>Lebih dari 18 Bulan</td>
                        <td>{{$data['lebih18']}} Orang</td>
                        <td>{{number_format($data['lebih18']/$data['kerja']*100,2)}} %</td>
                    </tr>
                    <tr>
                        <td>diantara 6 sampai 18 Bulan</td>
                        <td>{{$data['antara618']}} Orang</td>
                        <td>{{number_format($data['antara618']/$data['kerja']*100,2)}} %</td>
                    </tr>
                    <tr>
                        <td>Kurang dari 6 Bulan</td>
                        <td>{{$data['kurang6']}} Orang</td>
                        <td>{{number_format($data['kurang6']/$data['kerja']*100,2)}} %</td>
                    </tr>
                </table>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
            <!-- /.box-footer-->
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Ruang Lingkup Pekerjaan</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <table class="table">
                    <tr>
                        <td>Lokal</td>
                        <td>{{$data['lokal']}} Orang</td>
                        <td>{{number_format($data['lokal']/$data['kerja']*100,2)}} %</td>
                    </tr>
                    <tr>
                        <td>Nasional</td>
                        <td>{{$data['nasional']}} Orang</td>
                        <td>{{number_format($data['nasional']/$data['kerja']*100,2)}} %</td>
                    </tr>
                    <tr>
                        <td>Internasional / Multinasional</td>
                        <td>{{$data['internasional']}} Orang</td>
                        <td>{{number_format($data['internasional']/$data['kerja']*100,2)}} %</td>
                    </tr>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
            <!-- /.box-footer-->
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Gaji</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">

                <table class="table">
                    <tr>
                        <td>Tertinggi</td>
                        <td>{{$data['gajimax']->mahasiswa->user->name}}</td>
                        <td>Rp. {{number_format($data['gajimax']->f131,0,'.','.')}}</td>
                    </tr>
                    <tr>
                        <td>Terendah</td>
                        <td>{{$data['gajimin']->mahasiswa->user->name}}</td>
                        <td>Rp. {{number_format($data['gajimin']->f131,0,'.','.')}}</td>
                    </tr>
                    <tr>
                        <td>Rerata</td>
                        <td>-</td>
                        <td>Rp. {{number_format($data['gajiavg'],0,'.',',')}}</td>
                    </tr>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
            <!-- /.box-footer-->
        </div>
    </div>
    @endif
</div>
@push('script')
    <script src="{{asset('adminlte/bower_components/Flot/jquery.flot.js')}}"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script src="{{asset('adminlte/bower_components/Flot/jquery.flot.resize.js')}}"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="{{asset('adminlte/bower_components/Flot/jquery.flot.pie.js')}}"></script>
    <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
    <script src="{{asset('adminlte/bower_components/Flot/jquery.flot.categories.js')}}"></script>
    {{--}}
    <script type="text/javascript">
        /*
         * DONUT CHART
         * -----------
         */

        var donutData = [
          { label: 'Bekerja', data: {{$f8['job']}}, color: '#00ff00' },
          { label: 'Tidak Bekerja', data: {{$f8['neet']}}, color: '#0073b7' }
        ]
        $.plot('#donut-chart', donutData, {
          series: {
            pie: {
              show       : true,
              radius     : 1,
              //innerRadius: 0.5,
              label      : {
                show     : true,
                radius   : 1.5 / 3,
                formatter: labelFormatter,
                threshold: 0.1
              }

            }
          },
          legend: {
            show: true
          }
        })
        /*
         * END DONUT CHART
         */

      /*
       * Custom Label formatter
       * ----------------------
       */
      function labelFormatter(label, series) {
        return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
          + label
          + '<br>'
          + Math.round(series.percent*100)/100 + '%</div>'
      }
      </script>
      {{--}}
@endpush

@endif
@endsection
