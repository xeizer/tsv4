@extends('layouts.theme1')
@section('konten')
<div class="container py-3">
    <div class="header-title">
        <h2 class="heading-title text-info">Profil Mahasiswa</h2>
    </div>
    <div class="card-2">
        <div class="row ">
            <div class="col-md-4">
                <img src="{{asset('foto/'.Auth::user()->foto)}}" style="width: 350; height: 200">
            </div>
            <div class="col-md-8">
                <div class="card-block">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <h3 class="card-title">{{Auth::user()->name}}</h3>
                    <div class="meta">
                        <a>{{Auth::user()->mahasiswa->prodi->nama_prodi}}</a>
                    </div>
                    <div class="description">
                        <table class="table table-hover">
                            <tr>
                                <td  style="text-align: left">Nama</td>
                                <td  style="text-align: left">:</td>
                                <td  style="text-align: left">{{Auth::user()->name}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left">Nomor Induk Mahasiswa (NIM)</td>
                                <td style="text-align: left">:</td>
                                <td style="text-align: left">{{Auth::user()->nim}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left">Fakultas</td>
                                <td style="text-align: left">:</td>
                                <td style="text-align: left">{{Auth::user()->mahasiswa->prodi->fakultas->nama_fakultas}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left">Prodi</td>
                                <td style="text-align: left">:</td>
                                <td style="text-align: left">{{Auth::user()->mahasiswa->prodi->nama_prodi}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left">Email</td>
                                <td style="text-align: left">:</td>
                                <td style="text-align: left">{{Auth::user()->email}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left">Nomor Telepon</td>
                                <td style="text-align: left">:</td>
                                <td style="text-align: left">{{Auth::user()->tlp}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left">Tahun Lulus - Semester</td>
                                <td style="text-align: left">:</td>
                                <td style="text-align: left">{{Auth::user()->mahasiswa->tahun_lulus}} - {{Auth::user()->mahasiswa->semester_lulus}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left">Durasi Kuliah</td>
                                <td style="text-align: left">:</td>
                                <td style="text-align: left">{{Auth::user()->mahasiswa->durasi_tahun}} Tahun, {{Auth::user()->mahasiswa->durasi_bulan}} Bulan, {{Auth::user()->mahasiswa->durasi_hari}} Hari</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="extra">
                    <span class="right">
                        <a class="btn btn-sm btn-common animated fadeInUp pull-right" data-toggle="modal" data-target="#ubahdata">Ubah</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Button trigger modal -->

<!-- Modal -->
<form action="{{route('alumni.profil.simpan')}}" method="POST" enctype="multipart/form-data" class="loading">
    @csrf
<div class="modal fade" id="ubahdata" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Data Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="inputNim" class="col-md-5 control-label">NIM</label>
                        <div class="col-md-10">
                            <input type="text" name="nim" class="form-control" id="inputNIM" value="{{Auth::user()->nim}}" readonly>
                        </div>

                </div>

                <div class="form-group">
                    <label for="inputNama" class="col-sm-5 control-label">Nama</label>

                    <div class="col-sm-10">
                        <input type="text" name="nama" class="form-control" id="inputNama" value="{{Auth::user()->name}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail" class="col-sm-5 control-label">Email</label>

                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" id="inputEmail" value="{{Auth::user()->email}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword" class="col-sm-5 control-label">Password</label>

                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" id="inputPassword"
                            placeholder="Kosongkan jika tidak diubah">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword2" class="col-sm-5 control-label">Ulangi Password</label>

                    <div class="col-sm-10">
                        <input name="password_confirmation" type="password" class="form-control" id="inputEmail3"
                            placeholder="Ulangi Password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-sm-5 control-label">No Telpon</label>

                    <div class="col-sm-10">
                        <input type="text" name="tlp" class="form-control" id="inputPassword"
                    placeholder="Nomor telepon Anda" value="{{Auth::user()->tlp}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword2" class="col-sm-5 control-label">File Foto</label>

                    <div class="col-sm-10">
                        <input name="image" type="file" class="form-control" placeholder="file foto">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
</form>
<!-- Akhir Berita -->
@endsection
