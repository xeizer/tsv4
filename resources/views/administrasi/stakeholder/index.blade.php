@extends('layouts.theme2')
@section('isi')
<div class="row">

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Mahasiswa</div>
      </div>
      <div class="box-body">
          Tanggapan pada tahun xxxxx<br />
          jumlah Lulusan : xxxx Orang <br />
          Jumah Tanggapan Stakeholder : xxx Orang <br />
        <button class="btn btn-primary">Semua Tahun</button><br />
        <button class="btn btn-primary">2000</button><br />
        <button class="btn btn-primary">2001</button><br />
        <button class="btn btn-primary">2002</button>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Etika</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- round($integeritasSB/$sudahisifb*100) --}}%">{{-- $integeritasSB --}} ({{-- round($integeritasSB/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- round($integeritasB/$sudahisifb*100) --}}%">{{-- $integeritasB --}} ({{-- round($integeritasB/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- round($integeritasC/$sudahisifb*100) --}}%">{{-- $integeritasC --}} ({{-- round($integeritasC/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- round($integeritasK/$sudahisifb*100) --}}%">{{-- $integeritasK --}} ({{-- round($integeritasK/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Keahlian Pada Bidang Ilmu (Kompetensi Utama)</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $profesiSB/$sudahisifb*100 --}}%">{{-- $profesiSB --}} ({{-- round($profesiSB/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $profesiB/$sudahisifb*100 --}}%">{{-- $profesiB --}} ({{-- round($profesiB/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $profesiC/$sudahisifb*100 --}}%">{{-- $profesiC --}} ({{-- round($profesiC/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $profesiK/$sudahisifb*100 --}}%">{{-- $profesiK --}} ({{-- round($profesiK/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Kemampuan Berbahasa Asing</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $wawasanSB/$sudahisifb*100 --}}%">{{-- $wawasanSB --}} ({{-- round($wawasanSB/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $wawasanB/$sudahisifb*100 --}}%">{{-- $wawasanB --}} ({{-- round($wawasanB/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $wawasanC/$sudahisifb*100 --}}%">{{-- $wawasanC --}} ({{-- round($wawasanC/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $wawasanK/$sudahisifb*100 --}}%">{{-- $wawasanK --}} ({{-- round($wawasanK/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
   <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Penggunaan Teknologi Informasi</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $kepemimpinanSB/$sudahisifb*100 --}}%">{{-- $kepemimpinanSB --}} ({{-- round($kepemimpinanSB/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $kepemimpinanB/$sudahisifb*100 --}}%">{{-- $kepemimpinanB --}} ({{-- round($kepemimpinanB/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $kepemimpinanC/$sudahisifb*100 --}}%">{{-- $kepemimpinanC --}} ({{-- round($kepemimpinanC/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $kepemimpinanK/$sudahisifb*100 --}}%">{{-- $kepemimpinanK --}} ({{-- round($kepemimpinanK/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Kemampuan Berkomunikasi</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $kerjasamaSB/$sudahisifb*100 --}}%">{{-- $kerjasamaSB --}} ({{-- round($kerjasamaSB/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $kerjasamaB/$sudahisifb*100 --}}%">{{-- $kerjasamaB --}} ({{-- round($kerjasamaB/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $kerjasamaC/$sudahisifb*100 --}}%">{{-- $kerjasamaC --}} ({{-- round($kerjasamaC/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $kerjasamaK/$sudahisifb*100 --}}%">{{-- $kerjasamaK --}} ({{-- round($kerjasamaK/$sudahisifb*100) --}}%)
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
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $bahasaSB/$sudahisifb*100 --}}%">{{-- $bahasaSB --}} ({{-- round($bahasaSB/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $bahasaB/$sudahisifb*100 --}}%">{{-- $bahasaB --}} ({{-- round($bahasaB/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $bahasaC/$sudahisifb*100 --}}%">{{-- $bahasaC --}} ({{-- round($bahasaC/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $bahasaK/$sudahisifb*100 --}}%">{{-- $bahasaK --}} ({{-- round($bahasaK/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="box box-danger">
      <div class="box-header with-border">
        <div class="box-title">Pengembangan diri</div>
      </div>
      <div class="box-body">
        <p>Sangat Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $komunikasiSB/$sudahisifb*100 --}}%">{{-- $komunikasiSB --}} ({{-- round($komunikasiSB/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Baik</p>
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $komunikasiB/$sudahisifb*100 --}}%">{{-- $komunikasiB --}} ({{-- round($komunikasiB/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Cukup</p>
        <div class="progress">
          <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $komunikasiC/$sudahisifb*100 --}}%">{{-- $komunikasiC --}} ({{-- round($komunikasiC/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>

        <p>Kurang</p>
        <div class="progress">
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{-- $komunikasiK/$sudahisifb*100 --}}%">{{-- $komunikasiK --}} ({{-- round($komunikasiK/$sudahisifb*100) --}}%)
            <span class="sr-only">40% Complete (success)</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




</div>
@endsection
