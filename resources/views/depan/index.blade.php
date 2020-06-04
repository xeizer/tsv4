@extends('layouts.theme1')
@section('slider')
@include('layouts.komponentheme1.slider')
<!-- berita -->
<div class="container py-3">
	<div class="header-title">
	<h2 class="heading-title text-info">{{$title}}</h2>
	</div>
  @foreach($bil as $a)
    <div class="card-2">
      <div class="row ">
        <div class="col-md-4">
            <img src="{{asset('gambarberita/'.$a->gambar)}}" style="width: 350; height: 200" >
          </div>
          <div class="col-md-8">
              <div class="card-block">
                <h3 class="card-title">{{$a->judul}}
                    @if($a->kategori =="Berita")
                    <span class="badge badge-pill badge-primary">Berita</span>
                    @elseif($a->kategori == "Informasi")
                    <span class="badge badge-pill badge-info">Informasi</span>
                    @else
                    <span class="badge badge-pill badge-warning">Lowongan</span>
                    @endif
                </h3>
                <div class="meta">
                  <a>{{$a->update_at}}</a>
                </div>
                <div class="description">
                    {!! $a->konten !!}
                </div>
              </div>
              <div class="extra">
                <span class="right">
                  <a class="btn btn-sm btn-common animated fadeInUp pull-right" href="{{route('detil.bil', $a->id)}}">Selengkapnya</a>
                </span>
                <span>
                  <i class="fa fa-user"></i>
                  Di Posting Oleh : {{ $a->user->name }}
                  <br />
                  <i class="fa fa-clock-o"></i>
                    Pada : {{ date('d-m-Y H:i:s', strtotime($a->created_at)) }}
                  </i>
                </span>
              </div>
        </div>
      </div>
    </div>
  @endforeach
  {{$bil->links()}}
  </div><!-- Akhir Berita -->
@endsection
