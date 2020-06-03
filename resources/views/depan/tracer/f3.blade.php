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
                <h3 class="log-title">Dalam Satuan Bulan Kapan Anda Mulai Mencari Pekerjaan? (Pekerjaan Sambilan Tidak Termasuk)</h3>
                <form name="registrasi" action="{{route('simpan.f3')}}" method="post" id="kirimtracer">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label id="lblBekerja" class="lead"><strong></strong></label>
                        @if ($errors->has('f302') || $errors->has('f301'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Isi dalam bulan dan Pilih Salah Satu</strong>
                        </span>
                        @endif
                        <div class="form-inline">
                            <input type="number" name="f302" min="0" class="form-control" placeholder="Isi dalam angka" value="{{ old('f302') }}"
                                name="eMulai">
                            <select class="form-control" id="pilf3" name="f301" required="">
                                <option disabled selected hidden>Sebelum/Setelah Lulus</option>
                                <option value="1" @if(old('301')=='1' ) selected="" @endif>Sebelum Lulus Kuliah</option>
                                <option value="2" @if(old('301')=='2' ) selected="" @endif>Setelah Lulus Kuliah</option>
                                <option value="3" @if(old('301')=='3' ) selected="" @endif>Tidak Mencari Kerja</option>
                            </select>

                        </div>

                    </div>

                    <div class="log-line reg-form-1 no-margin">
                        <div class="pull-left">
                            <p class="lead">Halaman 3 dari 11</p>
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
