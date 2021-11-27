<ul class="sidebar-menu" data-widget="tree">
    {{--}}
    1   DASHBOARD
    2   ALUMNI
        ->SEMUA(1) +JURUSAN
    3   AKUN
    4   Ringkasan TRacer
        ->SEMUA(1) +JURUSAN
    5   Stakeholder
        ->SEMUA(1) +JURUSAN
    6   Dikti
        ->SEMUA(1) +JURUSAN
    {{--}}
    <li class="header">MAIN NAVIGATION</li>
    <li class="@isset ($active) @if($active==1)active @endif @endisset">
        <a href="{{route('index')}}">
            <i class="fa fa-dashboard"></i> <span>Kembali</span>
        </a>
    </li>
    @include('layouts.komponentheme2._sidebarpublik')
 


</ul>
