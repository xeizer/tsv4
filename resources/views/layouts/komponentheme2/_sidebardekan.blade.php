<li class="treeview @isset ($active) @if($active==2)active @endif @endisset">
    <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Alumni</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>

    <ul class="treeview-menu">
        @foreach (App\Prodim::where('fakultasm_id',Auth::user()->admin->prodi->fakultasm_id)->get() as $p)
        <li class="@isset ($subactive) @if($subactive=="2$p->id")active @endif @endisset">
            <a href="{{route('administrasi.alumni',['$prodi'=>$p->id,'status'=>1])}}"><i class="fa @isset ($subactive) @if($subactive=="2$p->id")fa-circle @else fa-circle-o @endif @endisset"></i>
                     {{$p->slug_prodi}}
                </a>
        </li>
        @endforeach
    </ul>

</li>
<li class="header">TRACER STUDY</li>
<li class="treeview @isset ($active) @if($active==4)active @endif @endisset">
    <a href="#">
        <i class="fa fa-files-o"></i>
        <span>Rangkuman Tracer</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @foreach (App\Prodim::where('fakultasm_id',Auth::user()->admin->prodi->fakultasm_id)->get() as $p)
        <li class="@isset ($subactive) @if($subactive==" 4$p->id")active @endif @endisset">
            <a href="{{route('statistik.tracer',['prodi'=>$p->id,'tahunangkatan'=>0, 'tahunlulus'=>0])}}"><i
                    class="fa @isset ($subactive) @if($subactive==" 4$p->id")fa-circle @else fa-circle-o @endif
                    @endisset"></i>
                {{$p->slug_prodi}}
            </a>
        </li>
        @endforeach
    </ul>
</li>
<li class="treeview @isset ($active) @if($active==5)active @endif @endisset">
    <a href="#">
        <i class="fa fa-files-o"></i>
        <span>Stakeholder</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @foreach (App\Prodim::where('fakultasm_id',Auth::user()->admin->prodi->fakultasm_id)->get() as $p)
        <li class="@isset ($subactive) @if($subactive==" 5$p->id")active @endif @endisset">
            <a href="{{route('statistik.index',['prodi_id'=>$p->id])}}"><i
                    class="fa @isset ($subactive) @if($subactive==" 5$p->id")fa-circle @else fa-circle-o @endif
                    @endisset"></i>
                {{$p->slug_prodi}}
            </a>
        </li>
        @endforeach
    </ul>
</li>
<li class="header">LAPORAN</li>
<li class="treeview @isset ($active) @if($active==6)active @endif @endisset">
    <a href="#">
        <i class="fa fa-files-o"></i>
        <span>Dikti</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">

        @foreach (App\Prodim::where('fakultasm_id',Auth::user()->admin->prodi->fakultasm_id)->get() as $p)
        <li class="@isset ($subactive) @if($subactive==" 6$p->id")active @endif @endisset">
            <a href="{{route('lapor.dikti',['prodi_id'=>$p->id])}}"><i
                    class="fa @isset ($subactive) @if($subactive==" 6$p->id")fa-circle @else fa-circle-o @endif
                    @endisset"></i>
                {{$p->slug_prodi}}
            </a>
        </li>
        @endforeach
    </ul>

</li>
<li class="treeview @isset ($active) @if($active==7)active @endif @endisset">
    <a href="#">
        <i class="fa fa-files-o"></i>
        <span>Laporan Dikti</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @foreach (App\Prodim::where('fakultasm_id',Auth::user()->admin->prodi->fakultasm_id)->get() as $p)
        <li class="@isset ($subactive) @if($subactive==" 6$p->id")active @endif @endisset">
            <a href="{{route('laporan.dikti',['prodi_id'=>$p->id])}}"><i
                    class="fa @isset ($subactive) @if($subactive==" 6$p->id")fa-circle @else fa-circle-o @endif
                    @endisset"></i>
                {{$p->slug_prodi}}
            </a>
        </li>
        @endforeach
    </ul>

</li>

