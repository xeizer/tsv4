@extends('layouts.theme1')
@section('slider')
@include('layouts.komponentheme1.slider')
@endsection
@section('konten')
<div class="container py-3">
    <div class="header-title">
        <h2 class="heading-title text-info">Lowongan Pekerjaan</h2>
    </div>
    @foreach($data as $a)
    <div class="card-2">
        <div class="row ">
            <div class="col-md-4">
                <img src="{{asset('gambarberita/'.$a->gambar)}}" style="width: 350; height: 200">
            </div>
            <div class="col-md-8">
                <div class="card-block">
                    <h3 class="card-title">{{$a->judul}}</h3>
                    <div class="meta">
                        <a>{{$a->update_at}}</a>
                    </div>
                    <div class="description">

                    </div>
                </div>
                <div class="extra">
                    <span class="right">
                  <a class="btn btn-sm btn-common animated fadeInUp pull-right" href="{{route('lowongan.detil',['id'=>$a->id])}}">Selengkapnya</a>
                </span>
                    <span>
                  <i class="fa fa-user"></i>
                  Di Posting Oleh : {{ $a->user->name ?? '' }} pada {{$a->updated_at}}
                </span>
                </div>
            </div>
        </div>
    </div>
    @endforeach {{$data->links()}}
</div>
<!-- Akhir Berita -->
@endsection
