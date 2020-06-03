@extends('layouts.theme2')
@section('isi')

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Title</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i>
                </button>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered tble-hover nowrap" id="dt">
                <thead class="bg-primary">
                    <tr>
                        <th>Usename</th>
                        <th>Nama</th>
                        <th>Fakultas</th>
                        <th>Prodi</th>
                        <th>Peran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        footer
    </div>
    <!-- /.box-footer-->
</div>
<!--modal -->
<div class="modal fade" id="modal-import">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Import Data</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{asset('files/BLANKO_ALUMNI_IKIP.xlsx')}}" target="_blank" class="btn btn-primary btn-block">Format Import</a>
                        <p>
                        <form method="POST" action="{{route('import.alumni')}}" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="{{ $errors->has('file') ? ' has-error' : '' }}">
                                <label for="file" class="col-md-1 control-label">File</label>
                                <div class="col-md-7">
                                    <input id="file" class="" type="file" name="file" accept=".xls,.xlsx,.cvs" required>
                                    @if ($errors->has('file'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('file') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-md-4">
                                <button type="submit" id="tombol-import" class="btn btn-primary btn-block">
                                    IMPORT DATA
                                </button>
                            </div>

                        </form>
                        </p>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!--modal -->
<form class="form-horizontal" id="klass" method="POST" action="{{route('akun.simpan')}}">
    @csrf

    <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Tambah Akun Administrasi</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputusername" class="col-sm-2 control-label">Username</label>

                        <div class="col-sm-10">
                            <input type="text" name="nim" class="form-control" id="inputNIM" placeholder="Masukkan Username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputNama" class="col-sm-2 control-label">Nama</label>

                        <div class="col-sm-10">
                            <input type="text" name="nama" class="form-control" id="inputNama" placeholder="Nama">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control" id="inputEmail" placeholder="Email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword" class="col-sm-2 control-label">Password</label>

                        <div class="col-sm-10">
                            <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword2" class="col-sm-2 control-label">Ulangi Password</label>

                        <div class="col-sm-10">
                            <input name="password_confirmation" type="password" class="form-control" id="inputEmail3" placeholder="Ulangi Password">
                        </div>
                    </div>

                    <div class="form-group">
                    <label class="control-label col-sm-2" for="prodi">Prodi :</label>
                        <div class="col-md-10">
                            <select id="prodi" name="prodi" class="form-control" required>
                                @role('odin|humas')
                                <optgroup label="Fakultas Ilmu Pendidikan dan Pengetahuan Sosial">
                                    <option value="2">Bimbingan Konseling</option>
                                    <option value="3">Pendidikan Pancasila dan Kewarganegaraan</option>
                                    <option value="4">Pendidikan Sejarah</option>
                                    <option value="5">Pendidikan Geografi</option>
                                </optgroup>
                                <optgroup label="Fakultas Pendidikan MIPA dan Teknologi">
                                    <option value="6">Pendidikan Matematika</option>
                                    <option value="7">Pendidikan Fisika</option>
                                    <option value="8">Pendidikan Teknologi Informasi dan Komputer</option>
                                    <option value="9">Pendidikan Biologi</option>
                                </optgroup>
                                <optgroup label="Fakultas Pendidikan Bahasa dan Seni">
                                    <option value="10">Pendidikan Bahasa dan Sastra Indonesia</option>
                                    <option value="11">Pendidikan Bahasa Inggris</option>
                                </optgroup>
                                <optgroup label="Fakultas Pendidikan Olah Raga dan Kesehatan">
                                    <option value="12">Pendidikan Jasmani, Kesehatan dan Rekreasi</option>
                                </optgroup>
                                @endrole
                                @role('dekan')
                                    <optgroup label="{{session('fakultas_id')->fakultas->nama_fakultas}}">
                                        @foreach (App\Prodim::where('fakultas_id',session('fakultas_id')->fakultas->id)->get() as $prod)
                                        <option value="{{$prod->id}}">{{$prod->id}}-{{$prod->nama_prodi}}</option>
                                        @endforeach

                                    </optgroup>
                                @endrole
                                @role('admin')
                                    <option value="{{session('prodi_id')->id}}">{{session('prodi_id')->nama_prodi}}</option>
                                @endrole

                                </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputTahun" class="col-sm-2 control-label">Peran / Role</label>
                        <div class="col-md-10">
                        <select id="prodi" name="role" class="form-control" required>
                            @role('odin|humas')
                            <option value="rektor">Rektor</option>
                            <option value="dekan">Dekan</option>
                            <option value="humas">Humas</option>
                            <option value="admin">Adminitrator Prodi</option>
                            @endrole
                        </select>
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
<!-- /.modal -->

<form action="{{route('akun.hapus')}}" id="modal-hapus" method="POST">
    @csrf
@include('layouts.komponentheme2._modaldelete')
</form>
@endsection
 @push('script')
 {{--}}
<script type="text/javascript">
    $('#dts').DataTable({

		"language":{
		    "sEmptyTable":   "Tidak ada data yang tersedia pada tabel ini",
		    "sProcessing":   "<i class='fa fa-spinner fa-spin'></i> Memproses ...",
		    "sLengthMenu":   "Tampilkan _MENU_ entri",
		    "sZeroRecords":  "Tidak ditemukan data yang sesuai",
		    "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
		    "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
		    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
		    "sInfoPostFix":  "",
		    "sSearch":       "Cari:",
		    "sUrl":          "",
		    "oPaginate": {
		        "sFirst":    "Pertama",
		        "sPrevious": "Sebelumnya",
		        "sNext":     "Selanjutnya",
		        "sLast":     "Terakhir"
		    }
		},
        dom: 'Blfrtip',
		responsive: true,
        buttons: [ 'excel', 'pdf' ],
		processing: true,
		serverside: true,
		ajax: "{{route('api.alumni')}}",
		columns: [

			{data: 'nim', name: 'nim'},
			{data: 'ipk', name: 'ipk'},



		]

	});

</script>
{{--}}
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#dt').DataTable( {
            "language":{
                "sEmptyTable": "Tidak ada data yang tersedia pada tabel ini",
                "sProcessing": "<i class='fa fa-spinner fa-spin'></i>Memproses ...",
                "sLengthMenu": "Tampilkan _MENU_ entri",
                "sZeroRecords": "Tidak ditemukan data yang sesuai",
                "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "sInfoPostFix": "",
                "sSearch": "Cari:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Pertama",
                    "sPrevious": "Sebelumnya",
                    "sNext": "Selanjutnya",
                    "sLast": "Terakhir" }
                    },
            lengthChange: false,
            buttons: [
                {extend: 'excel', text:'Ekspor Ke Excel',exportOptions: {
                    columns: [ 0, 1, 2, 3 ] //kolom yang mau di tampilkan
                    }},
                {text: 'Tambah', action: function ( e, dt, node, config ) { $('#modal-tambah').modal(); }}
            ],
            responsive: true,
            processing: true,
            serverside: true,
            initComplete : function () { table.buttons().container() .appendTo( $('#dt_wrapper .col-sm-6:eq(0)')); },
            ajax: "{{route('api.akun')}}",
            columns:[
                {data: 'username', name: 'username'},
                {data:'nama', name:'nama'},
                {data: 'fakultas', name: 'fakultas'},
                {data: 'prodi', name: 'prodi'},
                {data: 'peran', name: 'peran'},
                {data: 'aksi', name: 'aksi', orderable: false},
                ]
            } );
            table.buttons().container() .appendTo( '#dt_wrapper .col-sm-6:eq(0)' );

            $('#klass').submit(function(){
                Swal.fire({
                    text:'Proses',
                    onOpen: () => { swal.showLoading(); }
                });
            });
            $('body').on('click', '#btn-hapus', function (event) {
                event.preventDefault();
                var ini = $(this),
                nama = ini.attr('data-nama');
                id = ini.attr('data-id');
                $('#modal-id').attr('value', id)
                $('#datahapus').text(nama);
                $('#modal-delete').modal('show');

            });
            $('#modal-hapus').submit(function(){
                Swal.fire({
                    text:'Proses',
                    onOpen: () => { swal.showLoading(); }
                });
            });
        } );

</script>
{{-- expr --}}
@endpush
