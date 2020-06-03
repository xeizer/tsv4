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
                <h3 class="log-title">Identitas Alumni</h3>
                <form name="registrasi" action="{{route('simpan.f1')}}" method="post" id="kirimtracer">
                    {{ csrf_field() }}
                    <input type="hidden" name="mahasiswa_id" value="">
                    <div class="form-group">
                        <label id="lblNIM" class="lead"><strong>Nomor Induk Mahasiswa&nbsp;&nbsp;<span
                                    class="fa fa-address-book"></span></strong></label>
                        <input type="text" value="{{Auth::user()->nim}}" class="form-control" id="eNim"
                            placeholder="Nomor Induk Mahasiswa" required disabled=""
                            data-error="*Silahkan isi dengan NIM anda">
                    </div>
                    <div class="form-group">
                        <label id="lblNama" class="lead"><strong>Kode Perguruan Tinggi&nbsp;&nbsp;<span
                                    class="fa fa-user"></span></strong></label>
                        <input type="text" value="112002" class="form-control" disabled="">
                    </div>
                    <div class="form-group">
                        <label id="lblNama" class="lead"><strong>Tahun Lulus&nbsp;&nbsp;<span
                                    class="fa fa-user"></span></strong></label>
                        <input type="text" value="{{Auth::user()->mahasiswa->tahun_lulus}}" class="form-control"
                            disabled="">
                    </div>
                    <div class="form-group">
                        <label id="lblNama" class="lead"><strong>Program Studi&nbsp;&nbsp;<span
                                    class="fa fa-user"></span></strong></label>
                        <input type="text"
                            value="{{Auth::user()->mahasiswa->prodi->kd_prodi}} - {{Auth::user()->mahasiswa->prodi->nama_prodi}}"
                            class="form-control" disabled="">
                    </div>
                    <div class="form-group">
                        <label id="lblNama" class="lead"><strong>Nama&nbsp;&nbsp;<span
                                    class="fa fa-user"></span></strong></label>
                        <input type="text" value="{{Auth::user()->name}}" class="form-control" disabled="">
                    </div>
                    <div class="form-group">
                        <label id="lblNama" class="lead"><strong>Nomor Telp&nbsp;&nbsp;<span
                                    class="fa fa-user"></span></strong></label>
                        <input type="text" value="{{Auth::user()->tlp}}" class="form-control" required name="tlp">
                    </div>
                    <div class="form-group">
                        <label id="lblNama" class="lead"><strong>Email&nbsp;&nbsp;<span
                                    class="fa fa-user"></span></strong></label>
                        <input type="text" value="{{Auth::user()->email}}" class="form-control" required name="email">
                    </div>


                    <div class="log-line reg-form-1 no-margin">
                        <div class="pull-left">
                            <p class="lead">Halaman 1 dari 11</p>
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
