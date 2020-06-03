<!DOCTYPE html>
<html lang="en">
    @include('layouts.komponentheme1.header')

<body>
    <!-- Page Wrapper Start -->
    <div class="wrapper">
        <!-- Content Area Start -->
        <div id="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <!-- log forms -->
                        <div class="mb-60"></div>
                        <h3 class="text-center title-head">Login</h3>
                        <!-- form 1 -->
                        <div class="row justify-content-center">
                            <!-- Start login form  -->
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div id="login-form">
                                    <h3 class="log-title">Log In</h3>
                                    <form action="{{ route('login') }}" method="POST" name="flogin">
                                        {{ csrf_field() }}
                                        <div class="form-group{{ $errors->has('nim') ? ' has-error' : '' }}">
                                            <label id="label_nim" class="form-control-label">Username &nbsp;&nbsp;<span class="icon-signin"></span>@if ($errors->has('nim'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('nim') }}</strong>
                                    </span>
                                @endif</label>
                                            <input type="text" class="form-control" id="nim" name="nim" placeholder="NIM" required data-error="*Please fill out this field">

                                        </div>

                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label id="label_pass" class="form-control-label">Password &nbsp;&nbsp;<span class="icon-key"></span></label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                                            <div class="help-block with-errors">
                                                @if ($errors->has('password'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span> @endif
                                            </div>
                                        </div>
                                        <div class="log-line">
                                            <div class="pull-left">
                                                <div class="checkbox space-bottom checkbox-primary">
                                                    <label class="hide"><input type="checkbox"></label>
                                                    <input id="checkbox1" type="checkbox">
                                                    <label for="checkbox1"><span>&nbsp;Ingat Saya</span></label>
                                                </div>
                                            </div>
                                            <div class="pull-right">
                                                <button type="submit" id="log-submit" class="btn btn-md btn-common btn-log">Log In</button>
                                                <a href="{{route('beranda')}}"><button class="btn btn-md btn-common btn-log">KEMBALI</button></a>
                                                <div id="msgSubmit" class="h3 text-center hidden"></div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <!-- <a href="lupa_pass.html" class="forgot-password">Lupa Password?</a> -->
                                    </form>
                                </div>
                            </div>
                            <!-- End login form 1 -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content Area End -->
    </div>
    <!-- Page Wrapper End -->

    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    @include('layouts.komponentheme1.script')
</body>

</html>
