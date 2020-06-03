<!DOCTYPE html>
<html lang="en">
@include('depan.layout.header')
<body>
<div id="content">
      <!-- sliders -->
@yield('slider')
      <!-- End sliders -->
    <!-- container breadcumb -->  
    <!-- akhir container bradcumb -->
<!-- berita -->
@yield('container1')
@yield('container2')
@yield('container3')
@yield('container4')

		
</div><!-- end content-->
@include('depan.layout.footer')
@include('depan.layout.script')
@yield('scripttambahan')
</body>
</html>