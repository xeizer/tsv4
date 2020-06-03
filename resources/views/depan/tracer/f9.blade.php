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
                <h3 class="log-title">Bagaimana anda menggambarkan situasi anda saat ini? Jawaban bisa lebih dari satu</h3>
                <form name="registrasi" action="{{route('simpan.f9')}}" method="post" id="kirimtracer">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label id="lblNama" class="lead">Bagaimana anda menggambarkan situasi anda saat ini? Jawaban bisa lebih dari satu</label>
                        @if ($errors->has('f9'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Pilihlah paling tidak satu pilihan</strong>
                        </span>
                        @endif
                        <div class="checkbox-primary form-control">
                            <input type="checkbox" value="1" class="checkbox" id="Check1" name="f91" @if(old('f91')) checked @endif>
                            <label class="custom-control-label" for="Check1"> Saya masih belajar/melanjutkan kuliah profesi atau pascasarjana</label>
                            <br>
                            <input type="checkbox" value="2" class="checkbox" id="Check2" name="f92" @if(old('f92')) checked @endif>
                            <label class="custom-control-label" for="Check2"> Saya menikah</label>
                            <br>
                            <input type="checkbox" value="3" class="checkbox" id="Check3" name="f93" @if(old('f93')) checked @endif>
                            <label class="custom-control-label" for="Check3"> Saya sibuk dengan keluarga dan anak-anak</label>
                            <br>
                            <input type="checkbox" value="4" class="checkbox" id="Check4" name="f94" @if(old('f94')) checked @endif>
                            <label class="custom-control-label" for="Check4">Saya sekarang sedang mencari pekerjaan</label>
                            <br>
                            <input type="checkbox" value="5" class="checkbox" id="Check5" name="f95" @if(old('f95')) checked @endif>
                            <label class="custom-control-label" for="Check5">Lainnya</label>
                            <br>
                            <input type="text" class="form-control" id="lain" name="f96" placeholder="Lainnya (tuliskan)"
                                data-error="*Please fill out this field">
                        </div>
                    </div>

                    <div class="log-line reg-form-1 no-margin">
                        <div class="pull-left">
                            <p class="lead">Halaman 7 dari 11</p>
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
