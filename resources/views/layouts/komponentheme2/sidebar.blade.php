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
        <a href="{{route('administrasi')}}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    @role('odin|admin')
    <li class="@isset ($active) @if($active=='berita')active @endif @endisset">
        <a href="{{route('beritadaninformasi.index')}}">
            <i class="fa fa-newspaper-o"></i> <span>Berita dan Lowongan</span>
        </a>
    </li>
    @endrole
    @role('odin|rektor|humas')
    @include('layouts.komponentheme2._sidebarodin')
    @endrole
    @role('dekan')
    @include('layouts.komponentheme2._sidebardekan')
    @endrole
    @role('admin')
    @include('layouts.komponentheme2._sidebaradmin')
    @endrole

</ul>
