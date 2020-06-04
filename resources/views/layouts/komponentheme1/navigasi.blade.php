<nav class="navbar navbar-toggleable-sm navbar-light bg-inverse sticky-top">
  <div class="container">
      <a class="navbar-brand hidden-xs-down" href="#"><img src="{{asset('depan/img/nav_head.png')}}" alt=""></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1" aria-expanded="false" aria-label="Toggle navigation">
                  <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbar1">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link @isset($aktif) @if($aktif=='beranda') bg-success @endif @endisset" href="{{route('beranda')}}">Beranda</a>
              </li>
              <li class="nav-item">
                <a class="nav-link @isset($aktif) @if($aktif=='Informasi') bg-success @endif @endisset" href="{{route('bil', 'Informasi')}}">Informasi</a>
              </li>
              <li class="nav-item">
                <a class="nav-link @isset($aktif) @if($aktif=='Lowongan') bg-success @endif @endisset" href="{{route('bil', 'Lowongan')}}">Lowongan</a>
              </li>
					    <li class="nav-item">
                <a class="nav-link @isset($aktif) @if($aktif=='alumni') bg-success @endif @endisset" href="{{route('alumni')}}">Alumni</a>
              </li>
              @role('mahasiswa')
					    <li class="nav-item">
                <a class="nav-link @isset($aktif) @if($aktif=='tracer') bg-success @endif @endisset" href="{{route('tracer.f1')}}">Tracer Study</a>
              </li>
              @endrole
              @role('stakeholder')
              <li class="nav-item">
                <a class="nav-link @isset($aktif) @if($aktif=='stakeholder') bg-success @endif @endisset" href="{{route('tracer.stakeholder')}}">Feedback Stakeholder</a>
              </li>
              @endrole
              @guest
					    <li class="nav-item">
                <a class="nav-link" href="{{route('login')}}">Login</a>
              </li>
              @endguest
              @auth
              <div class="dropdown">
                <a class="dropdown-toggle nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">
                  <img src="{{ asset('foto/'.Auth::user()->foto) }}" style="width: 20px;">
                  {{Auth::user()->name}}</a>
                <div class="dropdown-menu">
                  <!--<a class="dropdown-item" role="menu" href="#">Profil</a>-->
                  @role('admin|odin|humas|dekan|rektor')
                  <a class="dropdown-item" role="menu" href="{{route('administrasi')}}">Halaman Administrasi</a>
                  @endrole

                  @role('mahasiswa')
                <a class="dropdown-item" role="menu" href="{{route('alumni.profil')}}">Profil</a>
                  @endrole

                  <a class="dropdown-item" role="menu" href="{{route('logoutsemua')}}">Logout</a>
                </div>
              </div>

              @endauth
            </ul>
        </div>
  </div>
</nav>
