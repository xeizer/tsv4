@extends('layouts.theme1')

@section('konten')
	<div class="container">
		<div class="row justify-content-center">
          <div class="col-md-10">
            <!-- log forms -->
			<div class="mb-40"></div>
            <h3 class="text-center title-head">Tracer Study IKIP-PGRI Pontianak</h3>
			<p class="lead text-left">Bapak/Ibu pimpinan dari mahasiswa <strong>{{Auth::user()->stakeholder->mahasiswa->user->name}}</strong>, Kami Mohon kerjasamanya untuk dapat mengisi Angket berikut untuk menilai kualitas dari Alumni Kami</p>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <!-- Registrasi 1 -->
		  </div>
		</div>
		<div class="row justify-content-center">
		    <div class="col-md-10 col-sm-10 col-xs-10">
		        <div id="register-form">
		            <h3 class="log-title">Identitas</h3>
		            <form name="registrasi" action="{{route('tracer.stakeholder.simpan')}}" method="post">
		                {{ csrf_field() }}
		                <div class="form-group">
		                    <label id="lblBekerja" class="lead">Nama</label>
		                    @if ($errors->has('nama'))
				              <span class="invalid-feedback" role="alert">
				                  <strong class="text-danger">Mohon Untuk Diisi</strong>
				              </span>
				          	@endif
		                    <input type="text" class="form-control" name="nama">
		                </div>
		                <div class="form-group">
		                    <label id="lblBekerja" class="lead">Jabatan</label>
		                    @if ($errors->has('jabatan'))
				              <span class="invalid-feedback" role="alert">
				                  <strong class="text-danger">Mohon untuk Diisi</strong>
				              </span>
				          	@endif
		                    <input type="text" class="form-control" name="jabatan">
		                </div>
		                <div class="form-group">
		                    <label id="lblBekerja" class="lead">Nomor HP</label>
		                    @if ($errors->has('hp'))
				              <span class="invalid-feedback" role="alert">
				                  <strong class="text-danger">Mohon untuk Diisi</strong>
				              </span>
				          	@endif
		                    <input type="text" class="form-control" name="hp">
		                </div>
		                <div class="form-group">
		                    <label id="lblBekerja" class="lead">Pekerjaan</label>
		                    @if ($errors->has('pekerjaan'))
				              <span class="invalid-feedback" role="alert">
				                  <strong class="text-danger">Mohon untuk Diisi</strong>
				              </span>
				          	@endif
		                    <input type="text" class="form-control" name="pekerjaan">
		                </div>
		                <div class="form-group">
		                    <label id="lblBekerja" class="lead">Nama Instansi/Kantor</label>
		                    @if ($errors->has('instansi'))
				              <span class="invalid-feedback" role="alert">
				                  <strong class="text-danger">Mohon untuk Diisi</strong>
				              </span>
				          	@endif
		                    <input type="text" class="form-control" name="instansi">
		                </div>
		                <div class="form-group">
		                    <label id="lblBekerja" class="lead">Alamat Kantor</label>
		                    @if ($errors->has('alamat'))
				              <span class="invalid-feedback" role="alert">
				                  <strong class="text-danger">Mohon untuk Diisi</strong>
				              </span>
				          	@endif
		                    <textarea class="form-control" name="alamat"></textarea>
		                </div>
		                <div class="form-group">
		                    <label id="lblBekerja" class="lead">Telepon Kantor/ FAX</label>
		                    @if ($errors->has('fax'))
				              <span class="invalid-feedback" role="alert">
				                  <strong class="text-danger">Mohon untuk Diisi</strong>
				              </span>
				          	@endif
		                    <input type="text" class="form-control" name="fax">
		                </div>
		                <div class="form-group">
		                    <label id="lblBekerja" class="lead">E-Mail</label>
		                    @if ($errors->has('email'))
				              <span class="invalid-feedback" role="alert">
				                  <strong class="text-danger">Mohon untuk Diisi</strong>
				              </span>
				          	@endif
		                    <input type="email" class="form-control" name="email">
                        </div>

		                <div class="form-group">
		                    <label id="lblBekerja" class="lead">Nama Alumni Yang Akan Dinilai</label>
		                    <input type="text" class="form-control" value="{{Auth::user()->stakeholder->mahasiswa->user->name}}">
		                </div>
		                <div class="form-group">
		                    <label id="lblBekerja" class="lead">Jabatan Alumni</label>
		                    @if ($errors->has('jabatanalumni'))
				              <span class="invalid-feedback" role="alert">
				                  <strong class="text-danger">Mohon untuk Diisi</strong>
				              </span>
				          	@endif
		                    <input type="text" class="form-control" name="jabatanalumni">
		                </div>
		        </div>
		    </div>
        </div>

		<div class="row justify-content-center">
		    <div class="col-md-10 col-sm-10 col-xs-10">
		        <div id="register-form">
		            <h3 class="log-title">Feedback</h3>
		            <div class="form-group">
		                <label id="lblBekerja" class="lead">Pada saat lulus, pada tingkat mana kompetensi anda DAN bagaimana kontribusi Perguruan Tinggi (PT) dalam hal kompetensi anda</label>
		                <table class="table table-1 table-bordered table-hover">
		                    <thead>
		                        <tr>
		                            <th rowspan="2">Jenis Kemampuan</th>
		                            <th colspan="4">Kualitas <abbr title="Perguruan Tinggi"></abbr> </th>
                                </tr>
                                <tr>
                                    <th>Sangat Baik</th>
                                    <th>Baik</th>
                                    <th>Cukup</th>
                                    <th>Kurang</th>

                                </tr>
		                    </thead>
		                    <tbody>
		                        <tr>
		                            <td>Etika @error('sh1')<strong style="color: red">Harus Diisi</strong>@enderror</td>
		                            <td>
		                                <input type="radio" name="sh1" value="1">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh1" value="2">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh1" value="3">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh1" value="4">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Keahlian pada bidang Ilmu (Kompetensi Utama) @error('sh2')<strong style="color: red">Harus Diisi</strong>@enderror</td>
                                    <td>
                                        <input type="radio" name="sh2" value="1">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh2" value="2">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh2" value="3">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh2" value="4">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kemampuan bahasa Asing @error('sh3')<strong style="color: red">Harus Diisi</strong>@enderror</td>
                                    <td>
                                        <input type="radio" name="sh3" value="1">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh3" value="2">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh3" value="3">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh3" value="4">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Penggunaan Teknologi Informasi @error('sh4')<strong style="color: red">Harus Diisi</strong>@enderror</td>
                                    <td>
                                        <input type="radio" name="sh4" value="1">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh4" value="2">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh4" value="3">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh4" value="4">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kemampuan Berkomunikasi @error('sh5')<strong style="color: red">Harus Diisi</strong>@enderror</td>
                                    <td>
                                        <input type="radio" name="sh5" value="1">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh5" value="2">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh5" value="3">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh5" value="4">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kerjasama @error('sh6')<strong style="color: red">Harus Diisi</strong>@enderror</td>
                                    <td>
                                        <input type="radio" name="sh6" value="1">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh6" value="2">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh6" value="3">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh6" value="4">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Pengembangan diri @error('sh7')<strong style="color: red">Harus Diisi</strong>@enderror</td>
                                    <td>
                                        <input type="radio" name="sh7" value="1">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh7" value="2">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh7" value="3">
                                    </td>
                                    <td>
                                        <input type="radio" name="sh7" value="4">
                                    </td>
                                </tr>

		                    </tbody>
		                </table>
		            </div>
		            <div class="mb-3"></div>
		            <div class="log-line reg-form-1 no-margin">
		                <div class="pull-right form-group">
		                    <button type="submit" id="next-submit" class="btn btn-md btn-common btn-log">Selesai</button>
		                    <div id="msgSubmit" class="h3 text-center hidden"></div>
		                    <div class="clearfix"></div>
		                </div>
		            </div>
		            </form>
		        </div>
		    </div>
        </div>

	  </div>
	</div><!-- end content-->
@endsection
@section('scripttambahan')
<script src="{{ asset('adminlte/plugins/sliderv2/bootstrap-slider.js') }}"></script>
<script>
$('input#ex1').slider({
  formatter: function(value) {
    return 'Current value: ' + value;
  }
});
</script>
@endsection
