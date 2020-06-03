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
                <form name="registrasi" action="{{route('simpan.f16')}}" method="post" id="kirimtracer">
                    {{ csrf_field() }}
                    {{--f11--}}
                    <div class="form-group">
                        <label id="lblNama" class="lead">Jika menurut anda pekerjaan anda saat ini tidak sesuai dengan pendidikan anda,
                            Mengapa anda mengambilnya?(Dapat Memilih Lebih dari Satu)</label>
                        @if ($errors->has('f16'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Mohon untuk memilih paling tidak satu pilihan</strong>
                        </span>
                        @endif
                        <div class="checkbox-primary form-control">
                            <input type="checkbox" class="checkbox" value="1" id="f161" name="f161" @if(old('f161')) checked @endif>
                            <label class="custom-control-label" for="f161">Pertanyaan tidak sesuai, pekerjaan saya sekarang sudah sesuai
                                dengan pendidikan saya </label>
                            <br>
                            <input type="checkbox" class="checkbox" value="2" id="f162" name="f162" @if(old('f162')) checked @endif>
                            <label class="custom-control-label" for="f162">Saya belum mendapatkan pekerjaan yang lebih sesuai</label>
                            <br>
                            <input type="checkbox" class="checkbox" value="3" id="f163" name="f163" @if(old('f163')) checked @endif>
                            <label class="custom-control-label" for="f163">Saya merasa pekerjaan ini memiliki prospek karir yang
                                baik</label>
                            <br>
                            <input type="checkbox" class="checkbox" value="4" id="f164" name="f164" @if(old('f164')) checked @endif>
                            <label class="custom-control-label" for="f164">Saya lebih suka bekerja di area pekerjaan yang tidak ada
                                hubungannya dengan pendidikan saya</label>
                            <br>
                            <input type="checkbox" class="checkbox" value="5" id="f165" name="f165" @if(old('f165')) checked @endif>
                            <label class="custom-control-label" for="f165">Saya dipromosikan ke posisi yang kurang berhubungan dengan
                                pendidikan saya dibanding posisi sebelumnya</label>
                            <br>
                            <input type="checkbox" class="checkbox" value="6" id="f166" name="f166" @if(old('f166')) checked @endif>
                            <label class="custom-control-label" for="f166">Saya dapat memeroleh pendapatan yang lebih tinggi di
                                pekerjaan ini</label>
                            <br>
                            <input type="checkbox" class="checkbox" value="7" id="f167" name="f167" @if(old('f167')) checked @endif>
                            <label class="custom-control-label" for="f167">Saya merasa pekerjaan saya saat ini lebih
                                aman/terjamin</label>
                            <br>
                            <input type="checkbox" class="checkbox" value="8" id="f168" name="f168" @if(old('f168')) checked @endif>
                            <label class="custom-control-label" for="f168">Saya merasa pekerjaan saya saat ini lebih menarik</label>
                            <br>
                            <input type="checkbox" class="checkbox" value="9" id="f169" name="f169" @if(old('f169')) checked @endif>
                            <label class="custom-control-label" for="f169">Saya dapat mengambil pekerjaan tambahan (jam kerja yang
                                fleksibel) dengan pekerjaan saya saat ini</label>
                            <br>
                            <input type="checkbox" class="checkbox" value="10" id="f1610" name="f1610" @if(old('f1610')) checked @endif>
                            <label class="custom-control-label" for="f1610">Lokasi tempat kerja saya dekat dengan rumah saya</label>
                            <br>
                            <input type="checkbox" class="checkbox" value="11" id="f1611" name="f1611" @if(old('f1611')) checked @endif>
                            <label class="custom-control-label" for="f1611">Pekerjaan saya saat ini lebih menjamin kebutuhan keluarga
                                saya</label>
                            <br>
                            <input type="checkbox" class="checkbox" value="12" id="f1612" name="f1612" @if(old('f1612')) checked @endif>
                            <label class="custom-control-label" for="f1612">Saya merasa dalam meniti karir, saya harus menerima
                                pekerjaan yang tidak berhubungan dengan pendidikan saya</label>
                            <br>
                            <input type="checkbox" class="checkbox" value="13" id="f1613" name="f1613" @if(old('f1613')) checked @endif>
                            <label class="custom-control-label" for="f1613">Lainnya</label>
                            <input type="text" class="form-control" name="f1614" placeholder="Lainnya (tuliskan)">
                        </div>
                    </div>
                    <div class="log-line reg-form-1 no-margin">
                        <div class="pull-left">
                            <p class="lead">Halaman 10 dari 11</p>
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
