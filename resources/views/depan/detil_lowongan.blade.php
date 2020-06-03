@extends('layouts.theme1')
@section('slider')
@include('layouts.komponentheme1.slider')
@endsection
@section('konten')
<div class="mb-60"></div>
<section class="blog">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-9">
                <div class="blog-block post-content-area">
                    <a href="#" data-toggle="modal" data-target="#gambar"><img src="{{ asset('gambarberita/'.$data->gambar) }}" style="width: 200"></a>
                    <div class="blog-post">
                        <h3><a href="#">{{$data->perusahaan}}</a></h3>
                        <p>{!!$data->berita!!}</p>
                    </div>
                </div>
            </div>

            <!-- Blog sidebar area -->
            <div class="col-sm-12 col-md-3">

            </div>
        </div>
    </div>
</section>

</div>
<!-- end content-->
@endsection


<div class="modal fade" id="gambar" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="image-gallery-title"></h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span>
                        </button>
            </div>
            <div class="modal-body">
                <img src="{{asset('gambarberita/'.$data->gambar)}}">
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
