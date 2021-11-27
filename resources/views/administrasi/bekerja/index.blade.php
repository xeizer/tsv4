@extends('layouts.theme2')
@section('isi')

<div class="box box-primary">
  <div class="box-header with-border">
      <h3 class="box-title">Bekerja</h3>

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
                      <th>NIM</th>
                      <th>Nama</th>
                      <th>Jabatan</th>
                      <th>Instansi</th>
                      <th>Alamat</th>
                      <th>Telp</th>
                  </tr>
              </thead>
              <tbody>
                @foreach ($data as $d)
                <tr>
                  <td>{{ $d->mahasiswa->user->nim }}</td>
                  <td>{{ $d->mahasiswa->user->name }}</td>
                  <td>{{$d->jabatanalumni}}</td>
                  <td>{{$d->instansi}}</td>
                  <td>{{$d->alamat}}</td>
                  <td>{{$d->fax}}</td>
              </tr>
                @endforeach
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
            
            responsive: true,
            processing: true,
           
            
            } );
          

            
        } );
        

</script>
{{-- expr --}}
@endpush