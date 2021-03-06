<!DOCTYPE html>
<html lang="en">

<head>
    <!-- meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Bootstrap UI Kit">
    <meta name="keywords" content="ui kit">
    <meta name="author" content="UIdeck">

    <title>Tracer Study - IKIP PGRI Pontianak</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('master/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('master/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('master/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('master/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('master/css/owl.theme.css') }}">
    <link rel="stylesheet" href="{{ asset('master/css/responsive.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('depan/img/favicon.png')}}" />

    <!-- Fonts icons -->

</head>

<body>
    <htmlpageheader name="page-header">
        Bukti Tracer | {{$nama}}
    </htmlpageheader>
    <div id="content">

        <div class="mb-4"></div>
        <div class="container">
            <div id="headers">
                <!-- Light header -->
                <div class="mb-4">
                </div>
                <header>
                    {{--<img src="{{ asset('gambarumum/PPK_header.jpg') }}" style="width: 100%">--}}
                </header>
            </div>
            <!-- Light header -->
            <div class="mb-4">
            </div>

        </div>
        <div class="title-head">
            <h3 class="small-title text-center">SURAT PERNYATAAN<br>BUKTI PENGISIAN FEEDBACK KOMPETENSI LULUSAN<br>OLEH STAKEHOLDER</h3>
        </div>
        <div class="col-md-8 col-sm-8">
            <p style="margin-left: 40px">Yang bertanda tangan dibawah ini :</p>

            <table border="0" style="margin-left: 30px">
                <tr>
                    <td width='230'>Nama</td>
                    <td>:</td>
                    <td width="250"> {{ $namastakeholder }}</td>
                </tr>
                <tr>
                    <td width='230'>Jabatan</td>
                    <td>:</td>
                    <td width="200"> {{ $jabatan }}</td>
                </tr>
                <tr>
                    <td width='230'>No Handphone</td>
                    <td>:</td>
                    <td width="200"> {{ $hp }}</td>
                </tr>
                <tr>
                    <td width='230'>Pekerjaan</td>
                    <td>:</td>
                    <td width="200"> {{ $pekerjaan }}</td>
                </tr>
                <tr>
                    <td width='230'>Instansi</td>
                    <td>:</td>
                    <td width="200"> {{ $instansi }}</td>
                </tr>
                <tr>
                    <td width='230'>Alamat Kantor</td>
                    <td>:</td>
                    <td width="200"> {{ $alamat }}</td>
                </tr>
                <tr>
                    <td width='230'>Telp. Kantor</td>
                    <td>:</td>
                    <td width="200"> {{ $fax }}</td>
                </tr>
                <tr>
                    <td width='230'>E-mail</td>
                    <td>:</td>
                    <td width="200"> {{ $email }}</td>
                </tr>
                <tr>
                    <td width='230'>Nama Alumni yang di nilai</td>
                    <td>:</td>
                    <td width="200"> {{ $namamahasiswa }}</td>
                </tr>
                <tr>
                    <td width='230'>Jabatan Alumni</td>
                    <td>:</td>
                    <td width="200"> {{ $jabatanalumni }}</td>
                </tr>
            </table>


        </div>
        <p style="margin: 5%" align="justify">Dengan ini saya menyatakan telah mengisi data feedback stakeholder terkait kompetensi
            lulusan
            IKIP PGRI Pontianak.
            Demikian surat pernyataan ini
            dibuat dengan penuh kesadaran dan rasa tanggung jawab untuk dapat dipergunakan sebagaimana mestinya.
        </p>
        <table align="center" width="90%"">
    <tr>
        <td width=" 40%">
            {{--<p><br>Kasubag. Pusat Karir dan Tracer Study</p>
            <img src="{{ asset('gambarumum/ttd_norsidi.jpg') }}" class="gambar_ttd" style="width: 200">
            <p><b><u>Tri Megalarasi, M.Pd.</u></b>
                <br><b>NPP. 202 2014 286</b></p>
            </td>
            <td width="20%"><img
                    src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($nama." ".date('d-m-Y H:i:s', strtotime($selesai)))) !!} "
                    style="width: 100"></td>--}}
            <td width="30%">
                <p>Pontianak, {{date('d-m-Y', strtotime($selesai))}}<br>
                    Pimpinan</p>
                <br /><br /><br /><br />
                <p><b><u>{{ $nama }}</u></b>
                    <br><b></b></p>
            </td>
            </tr>
        </table>
    </div>

    </div><!-- end content-->
</body>

</html>
