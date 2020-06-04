@extends('layouts.theme2')
@section('isi')
        <!-- daftar Modal -->
<div class="col-md-10">
        <form action="{{route('adminberita.edit.proses',['id'=>$berita->id])}}" method="POST" enctype="multipart/form-data" class="form-horizontal">
          {{ method_field('PUT')}}
          {{ csrf_field()}}
          <div class="form-group">
            <label class="control-label col-sm-2" for="kategori">Kategori :</label>
            <div class="col-md-10">
                <select name="kategori" id="ketegori" class="form-control">
                    <option value="Berita" {{$berita->kategori == "Berita" ? 'selected' : ""}}>Berita</option>
                    <option value="Informasi" {{$berita->kategori == "Informasi" ? 'selected' : ""}}>Informasi</option>
                    <option value="Lowongan" {{$berita->kategori == "Lowongan" ? 'selected' : ""}}>Lowongan</option>
                </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="judul">Judul :</label>
            <div class="col-md-10">
              <input type="text" name="judul" value="{{$berita->judul}}" id="judul" class="form-control">
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
              <textarea name="konten" id="editor1" class="form-control">{{$berita->konten}}"</textarea>
            </div>
          </div>

      <div class="pull-right">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{route('beritadaninformasi.index')}}" class="btn btn-warning">BATAL</a>
      </div>
        </form>
</div>
        <!-- Akhir daftar Modal -->
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
