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
                <form name="registrasi" action="{{route('simpan.f11')}}" method="post" id="kirimtracer">
                    {{ csrf_field() }}
                    {{--f11--}}
                    <div class="form-group">
                        <label id="lblJenis" class="lead">Apakah jenis perusahaan/instansi/institusi tempat anda bekerja sekarang?</label>
                        @if ($errors->has('f111'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Mohon memilih paling tidak satu pilihan</strong>
                        </span>
                        @endif
                        <div class="form-control">
                            <input type="radio" class="custom-radio" value="1" name="f111" id="rJenis1" @if(old('f111')=='1' )
                                checked="" @endif><label class="form-check-label" for="rJenis1">Instansi pemerintah (termasuk
                                BUMN/BUMD)</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="2" name="f111" id="rJenis2" @if(old('f111')=='2' )
                                checked="" @endif><label class="form-check-label" for="rJenis2">Organisasi non-profit/Lembaga Swadaya
                                Masyarakat</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="3" name="f111" id="rJenis3" @if(old('f111')=='3' )
                                checked="" @endif><label class="form-check-label" for="rJenis3">Perusahaan swasta</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="4" name="f111" id="rJenis4" @if(old('f111')=='4' )
                                checked="" @endif><label class="form-check-label" for="rJenis4">Wiraswasta/perusahaan sendiri</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="5" name="f111" id="rJenis5" @if(old('f111')=='5' ) checked=""
                                @endif><label class="form-check-label" for="rJenis5">Lainnya</label>
                            <input type="text" class="form-control" name="f112" placeholder="Lainnya (tuliskan)" value="{{old('f112')}}">
                        </div>
                    </div>
                    {{--f12--}}
                    <div class="form-group">
                        <label id="lblJenis" class="lead">Sebutkan sumberdana dalam pembiayaan kuliah?</label>
                        @if ($errors->has('f121'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Mohon memilih paling tidak satu pilihan</strong>
                        </span>
                        @endif
                        <div class="form-control">
                            <input type="radio" class="custom-radio" value="1" name="f121" id="f121" @if(old('f121')=='1' ) checked=""
                                @endif><label class="form-check-label" for="f121">Biaya Sendiri / Keluarga</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="2" name="f121" id="f122" @if(old('f121')=='2' ) checked=""
                                @endif><label class="form-check-label" for="f122">Beasiswa ADIK
                                Masyarakat</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="3" name="f121" id="f123" @if(old('f121')=='3' ) checked=""
                                @endif><label class="form-check-label" for="f123">Beasiswa BIDIKMISI</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="4" name="f121" id="f124" @if(old('f121')=='4' ) checked=""
                                @endif><label class="form-check-label" for="f124">Beasiswa PPA</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="5" name="f121" id="f125" @if(old('f121')=='5' ) checked=""
                                @endif><label class="form-check-label" for="f124">Beasiswa AFIRMASI</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="6" name="f121" id="f126" @if(old('f121')=='6' ) checked=""
                                @endif><label class="form-check-label" for="f124">Beasiswa Perusahaan/Swasta</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="7" name="f121" id="f127" @if(old('f121')=='7' ) checked=""
                                @endif><label class="form-check-label" for="f127">Lainnya</label>
                            <input type="text" class="form-control" name="f122" placeholder="Lainnya (tuliskan)" value="{{old('f122')}}">
                        </div>
                    </div>
                    {{--f13--}}
                    <div class="form-group">
                        <label id="lblGaji" class="lead">Berapa pendapatan anda setiap bulannya (Tuliskan dalam angka tanpa titik dan
                            koma)</label>
                        <div class="form-control">
                            <label class="form-control-label">Dari Pekerjaan Utama</label>
                            @error('f131')
                            <span class="invalid-feedback" role="alert">
                                <strong class="text-danger">Mohon untuk diisi dengan ANGKA, TANPA TITIK atau KOMA</strong>
                            </span>
                            @enderror
                            <input type="text" class="form-control" placeholder="Dari Pekerjaan Utama" name="f131"
                                value="{{ old('f131') }}">
                            <div class="mb-2"></div>
                            <label class="form-control-label">Dari Lembur dan Tips</label>
                            @error('f132')
                            <span class="invalid-feedback" role="alert">
                                <strong class="text-danger">Mohon untuk diisi dengan ANGKA, TANPA TITIK atau KOMA</strong>
                            </span>
                            @enderror
                            <input type="text" class="form-control" placeholder="Dari Lembur dan Tips" name="f132"
                                value="{{ old('f132') }}">
                            <div class="mb-2"></div>
                            <label class="form-control-label">Dari Pekerjaan Lainnya</label>
                            @error('f133')
                            <span class="invalid-feedback" role="alert">
                                <strong class="text-danger">Mohon untuk diisi dengan ANGKA, TANPA TITIK atau KOMA</strong>
                            </span>
                            @enderror
                            <input type="text" class="form-control" placeholder="Dari Pekerjaan Lainnya" name="f133"
                                value="{{ old('f133') }}">
                        </div>
                    </div>
                    {{--f14--}}
                    <div class="form-group">
                        <label id="lblHub" class="lead">Seberapa erat hubungan antara bidang studi dengan pekerjaan anda?</label>
                        @if ($errors->has('f14'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Mohon Untuk Memilih salah satu</strong>
                        </span>
                        @endif
                        <div class="form-control">
                            <input type="radio" class="custom-radio" value="1" name="f14" id="f141" @if(old('f14')==1) checked=""
                                @endif><label class="form-check-label" for="f141">Sangat Erat</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="2" name="f14" id="f142" @if(old('f14')==2) checked=""
                                @endif><label class="form-check-label" for="f142">Erat</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="3" name="f14" id="f143" @if(old('f14')==3) checked=""
                                @endif><label class="form-check-label" for="f143">Cukup Erat</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="4" name="f14" id="f144" @if(old('f14')==4) checked=""
                                @endif><label class="form-check-label" for="f144">Tidak Erat</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="5" name="f14" id="f145" @if(old('f14')==5) checked=""
                                @endif><label class="form-check-label" for="f145">Tidak Sama Sekali</label>
                        </div>
                    </div>
                    {{--f15--}}
                    <div class="form-group">
                        <label id="lblTingkat" class="lead">Tingkat pendidikan apa yang paling tepat/sesuai untuk pekerjaan anda saat
                            ini?</label>
                        @if ($errors->has('f15'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">Mohon untuk memilih salah satu</strong>
                        </span>
                        @endif
                        <div class="form-control">
                            <input type="radio" class="custom-radio" value="1" name="f15" id="f151" @if(old('f15')==1)
                                checked="" @endif><label class="form-check-label" for="f151">Sangat Sesuai</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="2" name="f15" id="f152" @if(old('f15')==2)
                                checked="" @endif><label class="form-check-label" for="f152">Sesuai</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="3" name="f15" id="f153" @if(old('f15')==3)
                                checked="" @endif><label class="form-check-label" for="f153">Cukup Sesuai</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="4" name="f15" id="f154" @if(old('f15')==4)
                                checked="" @endif><label class="form-check-label" for="f154">Tidak Sesuai</label>
                            <div class="mb-2"></div>
                            <input type="radio" class="custom-radio" value="5" name="f15" id="f155" @if(old('f15')==5)
                                checked="" @endif><label class="form-check-label" for="f155">Sangat Tidak Sesuai</label>
                        </div>
                    </div>
                    <div class="log-line reg-form-1 no-margin">
                        <div class="pull-left">
                            <p class="lead">Halaman 9 dari 11</p>
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
