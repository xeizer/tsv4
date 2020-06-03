<li class="treeview @isset ($active) @if($active==2)active @endif @endisset">
    <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Alumni</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>

    <ul class="treeview-menu">
        @foreach (App\Prodim::where('fakultas_id',Auth::user()->admin->prodi->fakultas->id)->orWhere('fakultas_id',1)->get() as $p)
        <li class="@isset ($subactive) @if($subactive=="2$p->id")active @endif @endisset">
            <a href="{{route('administrasi.alumni',['$prodi'=>$p->id])}}"><i class="fa @isset ($subactive) @if($subactive=="2$p->id")fa-circle @else fa-circle-o @endif @endisset"></i>
                     {{$p->slug_prodi}}
                </a>
        </li>
        @endforeach
    </ul>
</li>
