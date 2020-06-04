@extends('layouts.theme1')
@section('slider')
@include('layouts.komponentheme1.slider')
@endsection
@section('konten')
<div class="mb-60"></div>
<!-- Data untuk MIPATEK-->
<div class="table-style1">
    <div class="title-head">
        <h3 class="small-title text-center">Data Alumni IKIP PGRI Pontianak<br>Fakultas Pendidikan MIPA dan Teknologi</h3>
    </div>
    <div class="container">
        <div class="table-responsive mtb">
            <table class="table table-bordered table-1 table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pendidikan Matematika</th>
                        <th>Pendidikan Fisika</th>
                        <th>Pendidikan TIK</th>
                        <th>Pendidikan Biologi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><a href="{{ route('data_alumni.detil',['prodi'=>'6']) }}">{{App\Mahasiswam::where('prodim_id', 6)->count()}}</a></td>
                        <td><a href="{{ route('data_alumni.detil',['prodi'=>'7']) }}">{{App\Mahasiswam::where('prodim_id', 7)->count()}}</a></td>
                        <td><a href="{{ route('data_alumni.detil',['prodi'=>'8']) }}">{{App\Mahasiswam::where('prodim_id', 8)->count()}}</a></td>
                        <td><a href="{{ route('data_alumni.detil',['prodi'=>'9']) }}">{{App\Mahasiswam::where('prodim_id', 9)->count()}}</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div> <!-- AKhir Data Mipatek-->
<div class="mb-60"></div>
<!-- Data untuk FBS-->
<div class="table-style1">
    <div class="title-head">
        <h3 class="small-title text-center">Data Alumni IKIP PGRI Pontianak<br>Fakultas Pendidikan Bahasa dan Seni</h3>
    </div>
    <div class="container">
        <div class="table-responsive mtb">
            <table class="table table-bordered table-1 table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>

                        <th>Pendidikan Bahasa Inggris</th>
                        <th>Pendidikan Bahasa Indonesia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>

                        <td><a href="{{ route('data_alumni.detil',['prodi'=>'11']) }}">{{App\Mahasiswam::where('prodim_id', 11)->count()}}</a></td>
                        <td><a href="{{ route('data_alumni.detil',['prodi'=>'10']) }}">{{App\Mahasiswam::where('prodim_id', 10)->count()}}</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div> <!-- AKhir Data FBS-->

<div class="mb-60"></div>
<!-- Data untuk FIPPS-->
<div class="table-style1">
    <div class="title-head">
        <h3 class="small-title text-center">Data Alumni IKIP PGRI Pontianak<br>Fakultas Ilmu Pendidikan dan Pengetahuan Sosial</h3>
    </div>
    <div class="container">
        <div class="table-responsive mtb">
            <table class="table table-bordered table-1 table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>

                        <th>Bimbingan Konseling</th>
                        <th>Pendidikan PKn</th>
                        <th>Pendidikan Sejarah</th>
                        <th>Pendidikan Geografi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>

                        <td><a href="{{ route('data_alumni.detil',['prodi'=>'2']) }}">{{App\Mahasiswam::where('prodim_id', 2)->count()}}</a></td>
                        <td><a href="{{ route('data_alumni.detil',['prodi'=>'3']) }}">{{App\Mahasiswam::where('prodim_id', 3)->count()}}</a></td>
                        <td><a href="{{ route('data_alumni.detil',['prodi'=>'4']) }}">{{App\Mahasiswam::where('prodim_id', 4)->count()}}</a></td>
                        <td><a href="{{ route('data_alumni.detil',['prodi'=>'5']) }}">{{App\Mahasiswam::where('prodim_id', 5)->count()}}</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div> <!-- AKhir Data FIPPS-->

<div class="mb-60"></div>
<!-- Data untuk FIPPS-->
<div class="table-style1">
    <div class="title-head">
        <h3 class="small-title text-center">Data Alumni IKIP PGRI Pontianak<br>Fakultas Pendidikan Olahraga dan Kesehatan</h3>
    </div>
    <div class="container">
        <div class="table-responsive mtb">
            <table class="table table-bordered table-1 table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>

                        <th>PENJASKESREK</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>

                        <td><a href="{{ route('data_alumni.detil',['prodi'=>'12']) }}">{{App\Mahasiswam::where('prodim_id', 12)->count()}}</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div> <!-- AKhir Data FIPPS-->
@endsection
