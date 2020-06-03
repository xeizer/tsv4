  <script src="{{asset('depan/js/jquery.min.js')}}"></script>
  <script src="{{asset('depan/js/tether.min.js')}}"></script>
  <script src="{{asset('depan/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('depan/js/owl.carousel.min.js')}}"></script>

  <script src="{{asset('depan/js/form-validator.min.js')}}"></script>
  <script src="{{asset('depan/js/contact-form-script.js')}}"></script>
  <script src="{{asset('depan/js/main.js')}}"></script>
  <script src="{{asset('vendor/sw/dist/sweetalert2.all.js')}}"></script>
  <script type="text/javascript">
        $(document).ready(function() {
                    $('.loading').submit(function(){
                        Swal.fire({
                            text:'Proses',
                            onOpen: () => { swal.showLoading(); }
                        });

                    });
                });

    </script>
