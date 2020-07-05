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
                <form name="registrasi" action="{{route('simpan.f8')}}" method="post" id="kirimtracer">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label id="lblBekerja" class="lead">Apakah anda bekerja saat ini?(Termasuk kerja sambilan dan wirausaha)</label>
                        @if ($errors->has('f8'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Pilih Salah Satu</strong>
                        </span>
                        @endif
                        <div class="form-control">
                            <input type="radio" value="1" class="custom-radio" name="f8" id="rKerja1"><label class="form-check-label"
                                for="rKerja1">Ya</label>
                            <div class="mb-2"></div>
                            <input type="radio" value="2" class="custom-radio" name="f8" id="rKerja2"><label
                                class="form-check-label" for="rKerja2">Tidak</label>
                        </div>

                    </div>
                    <div id="multinasional">
                    <div class="form-group">
                        <label id="lblBekerja" class="lead">Jika Anda telah bekerja, Ruang Lingkup Pekerjaan Anda dalam Skala</label>
                        @if ($errors->has('f8A'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Pilih Salah Satu</strong>
                        </span>
                        @endif
                        <div class="form-control">
                            <input type="radio" value="1" class="custom-radio" name="f8A" id="rKerja1"><label class="form-check-label"
                                for="rKerjaA1">Lokal</label>
                            <div class="mb-2"></div>
                            <input type="radio" value="2" class="custom-radio" name="f8A" id="rKerja2"><label
                                class="form-check-label" for="rKerjaA2">Nasional</label>
                            <div class="mb-2"></div>
                            <input type="radio" value="3" class="custom-radio" name="f8A" id="rKerja3"><label
                                class="form-check-label" for="rKerjaA3">Multinasional / Internasional</label>
                        </div>

                    </div>
                    </div>

                    <div class="log-line reg-form-1 no-margin">
                        <div class="pull-left">
                            <p class="lead">Halaman 6 dari 11</p>
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
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#multinasional').hide();

$('input:radio[name="f8"]').change(
    function(){

        if ($(this).is(':checked') && $(this).val() == '2') {
            $('#multinasional').hide();
        };
        if ($(this).is(':checked') && $(this).val() == '1') {
            $('#multinasional').show();
        };
    });
                    });
                </script>

 @endsection
<!-- Akhir Berita -->
@endsection
