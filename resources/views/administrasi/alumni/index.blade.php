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
                        <th rowspan="2">NIM</th>
                        <th rowspan="2">Nama<br></th>
                        <th rowspan="2">IPK</th>
                        <th rowspan="2">Prodi</th>
                        <th rowspan="2">Angkatan</th>
                        <th rowspan="2">Tahun Lulus</th>
                        <th colspan="3">Lama Kuliah</th>
                        <th rowspan="2">Aksi</th>
                    </tr>
                    <tr>
                        <td>T</td>
                        <td>B</td>
                        <td>H</td>
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
                        <form method="POST" class="loading" action="{{route('import.alumni')}}" enctype="multipart/form-data">
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
<form class="form-horizontal" id="klass" method="POST" action="{{route('alumni.simpan')}}">
    <input type="hidden" value="" name="id" id="hiddenid">
    @csrf
    <input type="hidden" name="ajax" value="{{$ajax}}">
    <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modal-title">Tambah Alumni</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputNim" class="col-sm-2 control-label">NIM</label>

                        <div class="col-sm-10">
                            <input type="text" name="nim" class="form-control" id="inputNIM" placeholder="Nomor Induk Mahasiswa">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputNama" class="col-sm-2 control-label">Nama Alumni</label>

                        <div class="col-sm-10">
                            <input type="text" name="nama" class="form-control" id="inputNama" placeholder="Nama Alumni">
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
                                    <optgroup label="{{Auth::user()->admin->prodi->fakultas->nama_fakultas}}">
                                        @foreach (App\Prodim::where('fakultasm_id',Auth::user()->admin->prodi->fakultasm_id)->get() as $prod)
                                        <option value="{{$prod->id}}">{{$prod->id}}-{{$prod->nama_prodi}}</option>
                                        @endforeach

                                    </optgroup>
                                @endrole
                                @role('admin')
                                    <option value="{{Auth::user()->admin->prodi->id}}">{{Auth::user()->admin->prodi->nama_prodi}}</option>
                                @endrole

                                </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputTahun" class="col-sm-2 control-label">Tahun Lulus</label>

                        <div class="col-sm-5">
                            <input type="number" min="1000" max="9999" maxlength="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="tahunlulus"  class="form-control" id="inputtahun" placeholder="Tahun Lulus">
                        </div>
                        <div class="col-sm-5">
                            <input type="number" min="1" max="2" maxlength="1" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="semesterlulus" class="form-control" id="inputsemester" placeholder="Semester Lulus">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputTahun" class="col-sm-2 control-label">Durasi Kuliah</label>

                        <div class="col-sm-3">
                            <input type="number" min="0" max="50" maxlength="2"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                name="durasitahun" class="form-control" id="inputdurasitahun" placeholder="Tahun">
                        </div>
                        <div class="col-sm-3">
                            <input type="number" min="0" max="12" maxlength="2"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                name="durasibulan" class="form-control" id="inputdurasibulan" placeholder="bulan">
                        </div>
                        <div class="col-sm-3">
                            <input type="number" min="0" max="30" maxlength="2"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                name="durasihari" class="form-control" id="inputdurasihari" placeholder="hari">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputIPK" class="col-sm-2 control-label">IPK</label>

                        <div class="col-sm-10">
                            <input name="ipk" type="text" max="4" maxlength="4" min="1" minlength="1" class="form-control" id="inputIPK" placeholder="IPK (contoh: 3.54)">
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

<form action="{{route('alumni.hapus')}}" id="modal-hapus" method="POST">
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
            @role('odin|humas|admin')
            buttons: [
                {extend: 'excel', text:'Ekspor Ke Excel',exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ] //kolom yang mau di tampilkan
                    }},
                {text: 'Import', action: function ( e, dt, node, config ) { $('#modal-import').modal(); }},
                {text: 'Tambah', action: function ( e, dt, node, config ) { $('#modal-tambah').modal(); }}
            ],
            @endrole
            responsive: true,
            processing: true,
            serverside: true,
            initComplete : function () { table.buttons().container() .appendTo( $('#dt_wrapper .col-sm-6:eq(0)')); },
            ajax: "{{route('api.alumni',['id'=>$ajax, 'status'=>$status])}}",
            columns:[
                {data: 'nim', name: 'nim'},
                {data:'nama', name:'nama'},
                {data: 'ipk', name: 'ipk'},
                {data: 'prodi', name: 'prodi'},
                {data: 'angkatan', name: 'angkatan'},
                {data: 'tahun_semester', name: 'tahun_semester'},
                {data: 'durasi_tahun', name: 'durasi_tahun'},
                {data: 'durasi_bulan', name: 'durasi_bulan'},
                {data: 'durasi_hari', name: 'durasi_hari'},
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
        $('body').on('click', '#btn-ubah', function (event) {
        event.preventDefault();
        var ini = $(this),
        id= ini.attr('data-id'),
        nama= ini.attr('data-nama'),
        nim= ini.attr('data-nim'),
        email= ini.attr('data-email'),
        prodi= ini.attr('data-prodi'),
        tahunlulus= ini.attr('data-tahunlulus'),
        semesterlulus= ini.attr('data-semesterlulus'),
        durasitahun= ini.attr('data-durasitahun'),
        durasibulan= ini.attr('data-durasibulan'),
        durasihari= ini.attr('data-durasihari'),
        ipk= ini.attr('data-ipk');

        $('#modal-title').text('Ubah data Siswa');
        $('#hiddenid').attr('value',id);
        $('#inputNama').attr('value',nama);
        $('#inputEmail').attr('value', email);
        $('#inputNIM').attr('value',nim);
        $('#inputtahun').attr('value',tahunlulus);
        $('#inputsemester').attr('value',semesterlulus);
        $('#inputdurasitahun').attr('value',durasitahun);
        $('#inputdurasibulan').attr('value',durasibulan);
        $('#inputdurasihari').attr('value',durasihari);
        $('#inputIPK').attr('value',ipk);
        $('#prodi').val(prodi).change();
        $('#klass').attr('action','{{route('alumni.edit')}}');
        $('#modal-tambah').modal();

        });

</script>
{{-- expr --}}
@endpush
