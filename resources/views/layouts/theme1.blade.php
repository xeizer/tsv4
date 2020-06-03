<!DOCTYPE html>
<html lang="en">
    @include('layouts.komponentheme1.header')

<body>
    <div id="content">
        <!-- sliders -->
        @include('layouts.komponentheme1.navigasi')
        @yield('slider')

        <!-- End sliders -->
        <!-- container breadcumb -->
        <!-- akhir container bradcumb -->
        <!-- berita -->
        @yield('konten')


    </div>
    <!-- end content-->
    @include('layouts.komponentheme1.footer')
    @include('layouts.komponentheme1.script')
    @yield('scripttambahan')
</body>

</html>
