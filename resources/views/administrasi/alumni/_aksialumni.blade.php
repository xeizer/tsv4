@role('humas|odin|admin')
<button id="btn-ubah" class="btn btn-warning btn-sm"
data-id="{{$data->user_id}}"
data-nama="{{$data->user->name}}"
data-nim="{{$data->user->nim}}"
data-email="{{$data->user->email}}"
data-prodi="{{$data->prodim_id}}"
data-tahunlulus="{{$data->tahun_lulus}}"
data-angkatan = "{{$data->angkatan}}"
data-semesterlulus="{{$data->semester_lulus}}"
data-durasitahun="{{$data->durasi_tahun}}"
data-durasibulan="{{$data->durasi_bulan}}"
data-durasihari="{{$data->durasi_hari}}"
data-ipk="{{$data->ipk}}">
    <i class="fa fa-edit"></i> Ubah Data
</button>

<button
    id="btn-hapus"
    class="btn btn-danger btn-sm"
    data-id="{{$data->user_id}}"
    data-nama="{{$data->user->name}}">
    <i class="fa fa-close"></i> Hapus
</button>

@endrole
