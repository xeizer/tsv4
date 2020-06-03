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
                <h3 class="log-title">Menurut anda seberapa besar penekanan pada metode pembelajaran di bawah ini dilaksanakan di program studi anda?</h3>
                <form name="registrasi" action="{{route('simpan.f2')}}" method="post" id="kirimtracer">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label id="lblBekerja" class="lead"><strong>Perkuliahan</strong></label>
                        @if ($errors->has('f21'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Pilih Salah Satu</strong>
                        </span>
                        @endif
                        <div class="form-control">
                            <div class="radio">
                                <label><input type="radio" name="f21" value="1"> Sangat Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f21" value="2"> Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f21" value="3"> Cukup Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f21" value="4"> Kurang</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f21" value="5"> Tidak Sama Sekali</label>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label id="lblBekerja" class="lead"><strong>Demonstrasi</strong></label>
                        @if ($errors->has('f22'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Pilih Salah Satu</strong>
                        </span>
                        @endif
                        <div class="form-control">
                            <div class="radio">
                                <label><input type="radio" name="f22" value="1"> Sangat Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f22" value="2"> Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f22" value="3"> Cukup Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f22" value="4"> Kurang</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f22" value="5"> Tidak Sama Sekali</label>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label id="lblBekerja" class="lead"><strong>Partisipasi dalam proyek riset</strong></label>
                        @if ($errors->has('f23'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Pilih Salah Satu</strong>
                        </span>
                        @endif
                        <div class="form-control">
                            <div class="radio">
                                <label><input type="radio" name="f23" value="1"> Sangat Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f23" value="2"> Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f23" value="3"> Cukup Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f23" value="4"> Kurang</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f23" value="5"> Tidak Sama Sekali</label>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label id="lblBekerja" class="lead"><strong>Magang</strong></label>
                        @if ($errors->has('f24'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Pilih Salah Satu</strong>
                        </span>
                        @endif
                        <div class="form-control">
                            <div class="radio">
                                <label><input type="radio" name="f24" value="1"> Sangat Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f24" value="2"> Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f24" value="3"> Cukup Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f24" value="4"> Kurang</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f24" value="5"> Tidak Sama Sekali</label>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label id="lblBekerja" class="lead"><strong>Praktikum</strong></label>
                        @if ($errors->has('f25'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Pilih Salah Satu</strong>
                        </span>
                        @endif
                        <div class="form-control">
                            <div class="radio">
                                <label><input type="radio" name="f25" value="1"> Sangat Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f25" value="2"> Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f25" value="3"> Cukup Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f25" value="4"> Kurang</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f25" value="5"> Tidak Sama Sekali</label>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label id="lblBekerja" class="lead"><strong>Kerja Lapangan</strong></label>
                        @if ($errors->has('f26'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Pilih Salah Satu</strong>
                        </span>
                        @endif
                        <div class="form-control">
                            <div class="radio">
                                <label><input type="radio" name="f26" value="1"> Sangat Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f26" value="2"> Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f26" value="3"> Cukup Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f26" value="4"> Kurang</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f26" value="5"> Tidak Sama Sekali</label>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label id="lblBekerja" class="lead"><strong>Diskusi</strong></label>
                        @if ($errors->has('f27'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Pilih Salah Satu</strong>
                        </span>
                        @endif
                        <div class="form-control">
                            <div class="radio">
                                <label><input type="radio" name="f27" value="1"> Sangat Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f27" value="2"> Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f27" value="3"> Cukup Besar</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f27" value="4"> Kurang</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="f27" value="5"> Tidak Sama Sekali</label>
                            </div>
                        </div>

                    </div>
                    <div class="log-line reg-form-1 no-margin">
                        <div class="pull-left">
                            <p class="lead">Halaman 2 dari 11</p>
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
