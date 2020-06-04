@extends('layouts.theme2')
@section('isi')
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Daftar Berita <a href="{{route('beranda')}}" class="btn btn-xs btn-warning pull-right">Lihat halaman Depan</a></div>
                <div class="panel-body">
                  <button class="btn btn-primary pull-left" data-toggle="modal" data-target="#inputBerita ">Tambah Berita</button>

                  <table class="table table-responsive table-striped">
                    <thead>
                      <th>No</th>
                      <th>Penulis</th>
                      <th>Kategori</th>
                      <th>Judul</th>
                      <th>Berita</th>
                      @role('admin|odin')
                      <th>DATA</th>
                      @endrole
                    </thead>
                      @foreach($isi as $a)
                      <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$a->user->name}}</td>
                        <td>{{$a->kategori}}</td>
                        <td>{{$a->judul}}</td>
                        <td>{{$a->konten}}</td>
                        @role('admin|odin')
                        <td>
                          <a href="{{route('beritadaninformasi.hapus',['id'=>$a->id])}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                          <a href="{{route('adminberita.edit',['id'=>$a->id])}}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                        </td>
                        @endrole
                      </tr>
                      @endforeach
                  </table>
                </div>
            </div>
        </div>
        <div id="inputBerita" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-aqua">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Input Berita</h4>
      </div>
      <div class="modal-body">
        <form action="{{route('beritadaninformasi.simpan')}}" method="POST" enctype="multipart/form-data" class="form-horizontal">
          {{ csrf_field()}}
          <div class="form-group">
            <label class="control-label col-sm-2" for="kategori">Kategori :</label>
            <div class="col-md-10">
                <select name="kategori" id="ketegori" class="form-control">
                    <option value="Berita">Berita</option>
                    <option value="Informasi">Informasi</option>
                    <option value="Lowongan">Lowongan</option>
                </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="judul">Judul :</label>
            <div class="col-md-10">
              <input type="text" name="judul" id="judul" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="judul">Gambar Utama :</label>
            <div class="col-md-10">
               <input type="file" name="image" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="konten">Isi Berita :</label>
            <div class="col-md-10">
              <textarea name="konten" id="editor1" class="form-control">

              </textarea>
            </div>
          </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
      </div>
    </div>
        </form>
  </div>
</div>
@endsection

@push('script')
<script src="{{asset('adminlte\bower_components/ckeditor/ckeditor.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{asset('adminlte\plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>

<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1')
    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5()
  })
</script>
@endpush
