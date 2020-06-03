@extends('layouts.theme2')
@section('isi')
<div class="row">
    <div class="col-md-3">

        <!-- Profile Image -->
        <div class="box box-primary">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="{{asset('foto/'.Auth::user()->foto)}}" alt="User profile picture">

                <h3 class="profile-username text-center">
                    @auth {{Auth::user()->name}} @endauth
                </h3>

                <p class="text-muted text-center">
                    @role('odin|rektor|dekan')
                    {{session('datauser')->prodi->fakultas->nama_fakultas}} @endrole @role('admin')
                    {{session('datauser')->prodi->nama_prodi}} @endrole
                </p>

                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Email</b><a class="pull-right">{{Auth::user()->email}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Username</b> <a class="pull-right">{{Auth::user()->nim}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Role</b> <a class="pull-right">{{Auth::user()->roles->first()->display_name}}</a>
                    </li>
                </ul>

                <a href="#" class="btn btn-primary btn-block" onclick="$('#modal-profil').modal();"><b>Ubah</b></a>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->


    </div>
    <!-- /.col -->
    <div class="col-md-9">

    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<form class="form-horizontal loading" method="POST" action="{{route('administrasi.profil.simpan')}}" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="modal-profil">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Ubah Profil</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputNim" class="col-sm-2 control-label">Username</label>

                        <div class="col-sm-10">
                            <input type="text" name="nim" class="form-control" id="inputNIM" value="{{Auth::user()->nim}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputNama" class="col-sm-2 control-label">Nama</label>

                        <div class="col-sm-10">
                            <input type="text" name="nama" class="form-control" id="inputNama" value="{{Auth::user()->name}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control" id="inputEmail" value="{{Auth::user()->email}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword" class="col-sm-2 control-label">Password</label>

                        <div class="col-sm-10">
                            <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Kosongkan jika tidak diubah">
                            Kosongkan Jika tidak ingin Diubah
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword2" class="col-sm-2 control-label">Ulangi Password</label>

                        <div class="col-sm-10">
                            <input name="password_confirmation" type="password" class="form-control" id="inputEmail3" placeholder="Ulangi Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword2" class="col-sm-2 control-label">Nomor Telepon</label>

                        <div class="col-sm-10">
                            <input name="tlp" type="text" class="form-control" id="inputEmail3"
                                placeholder="Masukkan nomor Telepon">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword2" class="col-sm-2 control-label">File Foto</label>

                        <div class="col-sm-10">
                            <input name="image" type="file" class="form-control" placeholder="file foto">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>

@endsection

