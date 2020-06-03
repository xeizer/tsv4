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
                <form name="registrasi" action="{{route('simpan.f10')}}" method="post" id="kirimtracer">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label id="lblUsaha" class="lead">Apakah anda aktif mencari pekerjaan dalam 1 bulan terakhir?</label>
                        @if ($errors->has('f101'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Mohon memilih paling tidak satu Pilihan</strong>
                        </span>
                        @endif
                        <div class="form-control">
                            <input type="radio" class="custom-radio" value="1" name="f101" id="rUsaha1" @if(old('f101')=='1' )
                                checked="" @endif><label class="form-check-label" for="rUsaha1">Tidak</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="2" name="f101" id="rUsaha2" @if(old('f101')=='2' )
                                checked="" @endif><label class="form-check-label" for="rUsaha2">Tidak, tetapi saya sedang menunggu hasil
                                lamaran kerja</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="3" name="f101" id="rUsaha3" @if(old('f101')=='3' )
                                checked="" @endif><label class="form-check-label" for="rUsaha3">Ya, saya akan bekerja dalam beberapa minggu
                                ke depan</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="4" name="f101" id="rUsaha4" @if(old('f101')=='4' )
                                checked="" @endif><label class="form-check-label" for="rUsaha4">Ya, tetapi saya belum tahu dengan pasti
                                kapan saya akan bekerja</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="5" name="f101" id="rUsaha5" @if(old('f101')=='5' ) checked=""
                                @endif><label class="form-check-label" for="rUsaha5">Lainnya</label>
                                <input type="text" class="form-control" name="f102" placeholder="Lainnya (tuliskan)" value="{{old('f102')}}">
                        </div>
                    </div>
                    <div class="log-line reg-form-1 no-margin">
                        <div class="pull-left">
                            <p class="lead">Halaman 8 dari 11</p>
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
