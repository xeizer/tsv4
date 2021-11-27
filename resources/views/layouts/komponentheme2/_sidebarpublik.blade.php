
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
        @foreach (App\Prodim::all() as $p)
        <li class="@isset ($subactive) @if($subactive==" 4$p->id")active @endif @endisset">
            <a href="{{route('statistik.tracerpublik',['prodi'=>$p->id,'tahunangkatan'=>0, 'tahunlulus'=>0])}}"><i
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
        @foreach (App\Prodim::all() as $p)
        <li class="@isset ($subactive) @if($subactive==" 5$p->id")active @endif @endisset">
            <a href="{{route('stakeholder.indexpublik',['prodi_id'=>$p->id, 'tahunangkatan' => 0, 'tahunlulus' =>0])}}"><i
                    class="fa @isset ($subactive) @if($subactive==" 5$p->id")fa-circle @else fa-circle-o @endif
                    @endisset"></i>
                {{$p->slug_prodi}}
            </a>
        </li>
        @endforeach
    </ul>
</li>
