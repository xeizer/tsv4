@extends('layouts.theme1')

@section('konten')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- log forms -->
            <h3 class="text-center title-head">Tracer Study IKIP-PGRI Pontianak</h3>
            <p class="lead text-left">Isilah data-data berikut ini sesuai kondisi anda sekarang </p>
            <!-- Registrasi 1 -->
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10 col-sm-10 col-xs-10">
            <div id="register-form">
                <h3 class="log-title"></h3>
                <form name="registrasi" action="{{route('simpan.f5')}}" method="post" id="kirimtracer">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label id="lblKerja" class="lead">Berapa bulan waktu yang dihabiskan untuk memperoleh pekerjaan pertama? (dalam
                            satuan Bulan)</label>
                        @if ($errors->has('f501'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">{{ $errors->first('f501') }}</strong>
                        </span>
                        @endif
                        <div class="form-inline">
                            <input type="text" class="form-control" placeholder="Isi dalam angka" name="f502"
                                value="{{ old('f502') }}">
                            <select class="form-control" name="f501" required="">
                                <option disabled selected hidden>Sebelum/Setelah Lulus</option>
                                <option value="1" @if(old('f501')=='1' ) selected="" @endif>Sebelum Lulus Kuliah</option>
                                <option value="2" @if(old('f501')=='2' ) selected="" @endif>Setelah Lulus Kuliah</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label id="lblLamar" class="lead">Berapa perusahaan/instansi/institusi yang sudah anda lamar (melalui surat atau
                            email) sebelum anda memperoleh pekerjaan pertama?</label>
                        @if ($errors->has('f6'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Mohon untuk diisi dalam isian angka</strong>
                        </span>
                        @endif
                        <input type="number" min="0" class="form-control" placeholder="Isikan dengan angka" name="f6" value="{{ old('f6') }}">
                    </div>
                    <div class="form-group">
                        <label id="lblRespon" class="lead">Berapa banyak perusahaan/instansi/institusi yang merespon lamaran anda?</label>
                        @if ($errors->has('f7'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Mohon untuk diisi dalam isian angka</strong>
                        </span>
                        @endif
                        <input type="number" min="0" class="form-control" placeholder="Isikan dengan angka" name="f7"
                            value="{{ old('f7') }}">
                    </div>
                    <div class="form-group">
                        <label id="lblWawancara" class="lead">Berapa banyak perusahaan/instansi/institusi yang mengundang anda untuk
                            wawancara?</label>
                        @if ($errors->has('f7a'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Mohon Untuk diisi dalam isian angka</strong>
                        </span>
                        @endif
                        <input type="number" min="0" class="form-control" placeholder="Isikan dengan angka" name="f7a"
                            value="{{ old('f7a') }}">
                    </div>
                    <div class="log-line reg-form-1 no-margin">
                        <div class="pull-left">
                            <p class="lead">Halaman 5 dari 11</p>
                        </div>
                        <div class="pull-right form-group">
                            <button type="submit" id="next-submit" class="btn btn-md btn-common btn-log">Berikutnya</button>
                            <div id="msgSubmit" class="h3 text-center hidden"></div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div><!-- end content-->

            <div id="modalProses" class="modal fade" role="dialog">
                <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header bg-aqua">
                            <h4 class="modal-title">Proses</h4>
                        </div>
                        <div class="modal-body">
                            <div class="text-center"><i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                                <span class="sr-only">Loading...</span></div>
                        </div>
                    </div>
                </div>
@section('scripttambahan')
                <script type="text/javascript">
                    $(document).ready(function () {
    $('#kirimtracer').submit(function(){
      $('#modalProses').modal('show');
    });
  });
                </script>
 @endsection
<!-- Akhir Berita -->
@endsection
