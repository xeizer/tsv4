@extends('layouts.theme1')

@section('konten')
@role('mahasiswa')
	<div class="container">
		<div class="jumbotron">
		    <h1>Terima Kasih Telah Mengisi Kuisioner </h1>
            <p>Alumni <strong>{{Auth::user()->name}}</strong>, Anda Telah menyelesaikan Proses Pengisian Kuisioner pada {{Carbon\Carbon::parse(Auth::user()->mahasiswa->updated_at)->format('d - M - Y')}}.</p>
            <p>Anda akan mengisi Tracer lagi Pada : {{Carbon\Carbon::parse(Auth::user()->mahasiswa->updated_at)->addYear(2)->format('d-M-Y')}} ({{
            Carbon\Carbon::parse(Auth::user()->mahasiswa->updated_at)->addYear(2)->diffInDays(Carbon\Carbon::now())
            }} Hari lagi)</p>
            @if(Carbon\Carbon::parse(Auth::user()->mahasiswa->updated_at)->addYear(2)->diffInDays(Carbon\Carbon::now())<=0)
            <a href="{{url('/tracer/reset')}}">Isi Ulang Tracer Studi</a>
            @else
            <p>Cetak Bukti <a href="{{route('cetak.tracer')}}" class="btn btn-info" target="new">DOWNLOAD</a></p>
            @endif
        </div>
        @if(Auth::user()->mahasiswa->f8->f8==1)
        <div class="jumbotron">
            <h1>Lanjutkan untuk mengisi informasi Stakeholder Oleh pimpinan saudara</h1>
            <p>Berikan informasi login ini kepada pimpinan Anda: </p>
            <table class="table table-1 table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>SH{{Auth::user()->nim}}</td>
                        <td>1234</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    </div><!-- end content-->
@endrole
@role('stakeholder')
<div class="container">
    <div class="jumbotron">
        <h1>Terima Kasih Telah Mengisi Kuisioner</h1>
        <p>Yang Terhormat, <strong>{{Auth::user()->name}}</strong>, Anda Telah menyelesaikan Proses Pengisian Kuisioner.</p>
        <p>Cetak Bukti <a href="{{route('cetak.stakeholder')}}"" class="btn btn-info" target="new">DOWNLOAD</a></p>
    </div>
</div><!-- end content-->
@endrole
@endsection
