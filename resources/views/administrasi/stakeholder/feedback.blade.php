@extends('administrasi.masteradmin')
@if($sudahisifb<1)
@section('row1')
<div class="col-md-12">
  <div class="alert alert-danger">
    Belum terdapat mahasiswa yang selesai mengisi Feedback pada Prodi @isset($prodipilih){{ $prodipilih->nama_prodi }}@endisset
  </div>
</div>
@endsection
@else
@section('row1')
<div class="col-md-12">
  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Mahasiswa</div>
      </div>
      <div class="box-body">
        <p>Total Mahasiswa Keseluruhan</p>
        <div class="progress">
          <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%">{{ $jumlah_mahasiswa_prodi }}
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Yang Telah Bekerja<small class="label pull-right bg-green">{{ $yangbekerja }} ({{ round($yangbekerja/$jumlah_mahasiswa_prodi*100) }}%)</small></p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ round($yangbekerja/$jumlah_mahasiswa_prodi*100) }}%">{{ $yangbekerja }} ({{ round($yangbekerja/$jumlah_mahasiswa_prodi*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Belum Mengisi Feedback</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%">{{ $belumisifb }}
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Selesai Mengisi Feedback</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%">{{ $sudahisifb }}
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Integeritas</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ round($integeritasSB/$sudahisifb*100) }}%">{{ $integeritasSB }} ({{ round($integeritasSB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ round($integeritasB/$sudahisifb*100) }}%">{{ $integeritasB }} ({{ round($integeritasB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ round($integeritasC/$sudahisifb*100) }}%">{{ $integeritasC }} ({{ round($integeritasC/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ round($integeritasK/$sudahisifb*100) }}%">{{ $integeritasK }} ({{ round($integeritasK/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Profesionalisme</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $profesiSB/$sudahisifb*100 }}%">{{ $profesiSB }} ({{ round($profesiSB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $profesiB/$sudahisifb*100 }}%">{{ $profesiB }} ({{ round($profesiB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $profesiC/$sudahisifb*100 }}%">{{ $profesiC }} ({{ round($profesiC/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $profesiK/$sudahisifb*100 }}%">{{ $profesiK }} ({{ round($profesiK/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Wawasan</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $wawasanSB/$sudahisifb*100 }}%">{{ $wawasanSB }} ({{ round($wawasanSB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $wawasanB/$sudahisifb*100 }}%">{{ $wawasanB }} ({{ round($wawasanB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $wawasanC/$sudahisifb*100 }}%">{{ $wawasanC }} ({{ round($wawasanC/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $wawasanK/$sudahisifb*100 }}%">{{ $wawasanK }} ({{ round($wawasanK/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Kepemimpinan</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $kepemimpinanSB/$sudahisifb*100 }}%">{{ $kepemimpinanSB }} ({{ round($kepemimpinanSB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $kepemimpinanB/$sudahisifb*100 }}%">{{ $kepemimpinanB }} ({{ round($kepemimpinanB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $kepemimpinanC/$sudahisifb*100 }}%">{{ $kepemimpinanC }} ({{ round($kepemimpinanC/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $kepemimpinanK/$sudahisifb*100 }}%">{{ $kepemimpinanK }} ({{ round($kepemimpinanK/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Kerjasama</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $kerjasamaSB/$sudahisifb*100 }}%">{{ $kerjasamaSB }} ({{ round($kerjasamaSB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $kerjasamaB/$sudahisifb*100 }}%">{{ $kerjasamaB }} ({{ round($kerjasamaB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $kerjasamaC/$sudahisifb*100 }}%">{{ $kerjasamaC }} ({{ round($kerjasamaC/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $kerjasamaK/$sudahisifb*100 }}%">{{ $kerjasamaK }} ({{ round($kerjasamaK/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Bahasa Asing</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $bahasaSB/$sudahisifb*100 }}%">{{ $bahasaSB }} ({{ round($bahasaSB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $bahasaB/$sudahisifb*100 }}%">{{ $bahasaB }} ({{ round($bahasaB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $bahasaC/$sudahisifb*100 }}%">{{ $bahasaC }} ({{ round($bahasaC/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $bahasaK/$sudahisifb*100 }}%">{{ $bahasaK }} ({{ round($bahasaK/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Komunikasi</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $komunikasiSB/$sudahisifb*100 }}%">{{ $komunikasiSB }} ({{ round($komunikasiSB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $komunikasiB/$sudahisifb*100 }}%">{{ $komunikasiB }} ({{ round($komunikasiB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $komunikasiC/$sudahisifb*100 }}%">{{ $komunikasiC }} ({{ round($komunikasiC/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $komunikasiK/$sudahisifb*100 }}%">{{ $komunikasiK }} ({{ round($komunikasiK/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Penggunaan IT</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $penggunaantikSB/$sudahisifb*100 }}%">{{ $penggunaantikSB }} ({{ round($penggunaantikSB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $penggunaantikB/$sudahisifb*100 }}%">{{ $penggunaantikB }} ({{ round($penggunaantikB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $penggunaantikC/$sudahisifb*100 }}%">{{ $penggunaantikC }} ({{ round($penggunaantikC/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $penggunaantikK/$sudahisifb*100 }}%">{{ $penggunaantikK }} ({{ round($penggunaantikK/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Pengembangan Diri</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $pengembanganSB/$sudahisifb*100 }}%">{{ $pengembanganSB }} ({{ round($pengembanganSB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $pengembanganB/$sudahisifb*100 }}%">{{ $pengembanganB }} ({{ round($pengembanganB/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $pengembanganC/$sudahisifb*100 }}%">{{ $pengembanganC }} ({{ round($pengembanganC/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{ $pengembanganK/$sudahisifb*100 }}%">{{ $pengembanganK }} ({{ round($pengembanganK/$sudahisifb*100) }}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Berdasar Tahun</div>
      </div>
      <div class="box-body">
        @role('rektor|odin')
        @foreach ($daftartahun as $t)
        {{-- expr --}}
        @isset($prodi)
        <a href="{{ route('hasil.feedback.prodi.tahun',['tahun'=>$t->tahun_lulus, 'prodi'=>$prodi]) }}" class="btn btn-info">{{ $t->tahun_lulus }}</a> 
        @endisset
        @empty ($prodi)
        <a href="{{ route('hasil.feedback.rektor.tahun',['tahun'=>$t->tahun_lulus]) }}" class="btn btn-info btn-block">{{ $t->tahun_lulus }}</a>
        @endempty
        @endforeach
        @endrole

        @role('dekan')
        @foreach ($daftartahun as $t)
        {{-- expr --}}
        @isset($prodi)
        <a href="{{ route('hasil.feedback.prodi.tahun',['tahun'=>$t->tahun_lulus, 'prodi'=>$prodi]) }}" class="btn btn-info">{{ $t->tahun_lulus }}</a> 
        @endisset
        @empty ($prodi)
        <a href="{{ route('hasil.feedback.dekan.tahun',['tahun'=>$t->tahun_lulus]) }}" class="btn btn-info btn-block">{{ $t->tahun_lulus }}</a>
        @endempty
        @endforeach
        @endrole

        @role('admin')
        @foreach ($daftartahun as $t)
        {{-- expr --}}
        @empty ($prodi)
        <a href="{{ route('hasil.feedback.tahun',['tahun'=>$t->tahun_lulus]) }}" class="btn btn-info btn-block">{{ $t->tahun_lulus }}</a>
        @endempty
        @endforeach
        @endrole
      </div>
    </div>
  </div>



  

</div>
@endsection
@section('srctambahan')
<script src="{{asset('adminlte/bower_components/Flot/jquery.flot.js')}}"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="{{asset('adminlte/bower_components/Flot/jquery.flot.resize.js')}}"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="{{asset('adminlte/bower_components/Flot/jquery.flot.pie.js')}}"></script>
<!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
<script src="{{asset('adminlte/bower_components/Flot/jquery.flot.categories.js')}}"></script>
@endsection
@section('jstambahan')
<script type="text/javascript">
 /*
     * DONUT CHART
     * -----------
     */

    var donutData = [
      { label: 'Sebelum Lulus', data: 100, color: '#00ff00' },
      { label: 'Setelah Lulus', data: 200, color: '#0073b7' }
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
    
@endsection
@endif