@if ($data->user_id==1)
<i class="fa fa-lock fa-lg"></i>
@else
  <button id="btn-hapus" class="btn btn-danger btn-sm" data-id="{{$data->user_id}}" data-nama="{{$data->user->name}}">
    <i class="fa fa-pie-zoom-in"></i> Hapus
</button>
@endif
