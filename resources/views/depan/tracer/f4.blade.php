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
                <h3 class="log-title">Bagaimana Anda Mencari Informasi Terkait Pekerjaan?(Dapat Memilih Lebih dari
                Satu)?</h3>
                <form name="registrasi" action="{{route('simpan.f4')}}" method="post" id="kirimtracer">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label id="lblNama" class="lead">Bagaimana Anda Mencari Informasi Terkait Pekerjaan?(Dapat Memilih Lebih dari
                            Satu)</label>
                        @if ($errors->has('f4'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Pilihlah paling tidak satu pilihan</strong>
                        </span>
                        @endif
                        <div class="checkbox-primary form-control">
                            <input type="checkbox" value="1" class="checkbox" id="Check1" name="f41" @if(old('f41')) checked @endif>
                            <label class="custom-control-label" for="Check1">Melalui iklan di koran/majalah, brosur </label>
                            <br>
                            <input type="checkbox" value="1" class="checkbox" id="Check2" name="f42" @if(old('f42')) checked @endif>
                            <label class="custom-control-label" for="Check2">Melamar ke perusahaan tanpa mengetahui lowongan yang
                                ada</label>
                            <br>
                            <input type="checkbox" value="1" class="checkbox" id="Check3" name="f43" @if(old('f43')) checked @endif>
                            <label class="custom-control-label" for="Check3">Pergi ke bursa/pameran kerja</label>
                            <br>
                            <input type="checkbox" value="1" class="checkbox" id="Check4" name="f44" @if(old('f44')) checked @endif>
                            <label class="custom-control-label" for="Check4">Mencari lewat internet/iklan online/milis</label>
                            <br>
                            <input type="checkbox" value="1" class="checkbox" id="Check5" name="f45" @if(old('f45')) checked @endif>
                            <label class="custom-control-label" for="Check5">Dihubungi oleh perusahaan</label>
                            <br>
                            <input type="checkbox" value="1" class="checkbox" id="Check6" name="f46" @if(old('f46')) checked @endif>
                            <label class="custom-control-label" for="Check6">Menghubungi Kementrian Tenaga Kerja dan Transmigrasi/Dinas
                                Tenaga Kerja</label>
                            <br>
                            <input type="checkbox" value="1" class="checkbox" id="Check7" name="f47" @if(old('f47')) checked @endif>
                            <label class="custom-control-label" for="Check7">Menghubungi agen tenaga kerja komersial/swasta</label>
                            <br>
                            <input type="checkbox" value="1" class="checkbox" id="Check8" name="f48" @if(old('f48')) checked @endif>
                            <label class="custom-control-label" for="Check8">Memperoleh informasi dari pusat/kantor pengembangan karir
                                fakultas/universitas</label>
                            <br>
                            <input type="checkbox" value="1" class="checkbox" id="Check9" name="f49" @if(old('f49')) checked @endif>
                            <label class="custom-control-label" for="Check9">Menghubungi kantor kemahasiswaan/himpunan alumni</label>
                            <br>
                            <input type="checkbox" value="1" class="checkbox" id="Check10" name="f410" @if( old('f410')) checked @endif>
                            <label class="custom-control-label" for="Check10">Membangun jejaring (network) sejak masih kuliah</label>
                            <br>
                            <input type="checkbox" value="1" class="checkbox" id="Check11" name="f411" @if( old('f411')) checked @endif>
                            <label class="custom-control-label" for="Check11">Melalui relasi (misalnya dosen, orang tua, saudara, teman,
                                dll.)</label>
                            <br>
                            <input type="checkbox" value="1" class="checkbox" id="Check12" name="f412" @if( old('f412')) checked @endif>
                            <label class="custom-control-label" for="Check12">Membangun bisnis sendiri</label>
                            <br>
                            <input type="checkbox" value="1" class="checkbox" id="Check13" name="f413" @if( old('f413')) checked @endif>
                            <label class="custom-control-label" for="Check13">Melalui penempatan kerja atau magang atau praktik
                                lapangan</label>
                            <br>
                            <input type="checkbox" value="1" class="checkbox" id="Check14" name="f414" @if( old('f414')) checked @endif>
                            <label class="custom-control-label" for="Check14">Bekerja di tempat yang sama dengan tempat kerja semasa
                                kuliah</label>
                            <br>
                            <input type="checkbox" value="1" class="checkbox" id="Check15" name="f415" @if( old('f415')) checked @endif>
                            <label class="custom-control-label" for="Check15">Lainnya</label>
                            <input type="text" class="form-control" id="lain" name="f416" placeholder="Lainnya (tuliskan)"
                                data-error="*Please fill out this field">
                        </div>
                    </div>

                    <div class="log-line reg-form-1 no-margin">
                        <div class="pull-left">
                            <p class="lead">Halaman 4 dari 11</p>
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
