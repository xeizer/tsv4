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
                <form name="registrasi" action="{{route('simpan.f17')}}" method="post" id="kirimtracer">
                    {{ csrf_field() }}
                    {{--f11--}}
                    <div class="form-group">
                        <label id="lblBekerja" class="lead">Pada saat lulus, pada tingkat mana kompetensi anda DAN bagaimana kontribusi
                            Perguruan Tinggi (PT) dalam hal kompetensi anda</label>

                        <table class="table table-1 table-striped">
                        <tr>
                            <th colspan="5">Kompetensi Anda</th>
                            <th rowspan="3">Pernyataan</th>
                            <th colspan="5">Kompetensi Pekerjaan</th>
                        </tr>
                        <tr>
                            <th colspan="2">Sangat Rendah</th>
                            <th></th>
                            <th colspan="2">Sangat Tinggi</th>
                            <th colspan="2">Sangat Rendah</th>
                            <th></th>
                            <th colspan="2">Sangat Tinggi</th>
                        </tr>
                        <tr>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                        </tr>
                        @foreach($keterangan as $ket)
                        <tr>
                            <td><input type="radio" value="1" name="f17a{{$loop->iteration}}"></td>
                            <td><input type="radio" value="2" name="f17a{{$loop->iteration}}"></td>
                            <td><input type="radio" value="3" name="f17a{{$loop->iteration}}"></td>
                            <td><input type="radio" value="4" name="f17a{{$loop->iteration}}"></td>
                            <td><input type="radio" value="5" name="f17a{{$loop->iteration}}"></td>
                            <td>{{$ket->keterangan}}</td>
                            <td><input type="radio" value="1" name="f17b{{$loop->iteration}}"></td>
                            <td><input type="radio" value="2" name="f17b{{$loop->iteration}}"></td>
                            <td><input type="radio" value="3" name="f17b{{$loop->iteration}}"></td>
                            <td><input type="radio" value="4" name="f17b{{$loop->iteration}}"></td>
                            <td><input type="radio" value="5" name="f17b{{$loop->iteration}}"></td>
                        </tr>
                        @endforeach
                        </table>
                    </div>
                    <div class="mb-3"></div>
                    <div class="log-line reg-form-1 no-margin">
                        <div class="pull-left">
                            <p class="lead">Halaman 11 dari 11</p>
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
                <script src="{{ asset('adminlte/plugins/sliderv2/bootstrap-slider.js') }}"></script>
                <script>
                    $('input#ex1').slider({
                  formatter: function(value) {
                    return 'Current value: ' + value;
                  }
                });
 @endsection
<!-- Akhir Berita -->
@endsection
