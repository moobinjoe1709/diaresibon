<!DOCTYPE html>
<html lang="th">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>เข้าสู่ระบบ</title>
        <meta name='subject' content='Free bootstrap 4 dashboard.'>
        <meta name="description" content="Free download bootstrap 4 dashboard, created with creativity and love of design. Free bootstrap dashboard." />
        <meta name='copyright' content='DevCRUD'>
        <meta name='robots' content='index,follow'>
        <meta name='author' content='DevCRUD'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/jpg" href="{{ asset('logo.jpg') }}">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/fonts/font-awesome-4.7.0/font-awesome.css">
        <link rel="stylesheet" href="assets/css/app.css">
    </head>

    <body class="user-page">

        <!-- Page content -->
        <div class="container-fluid">
            <div class="row wrapper-middle">
                <div class="col-sm-6 col-md-4 p0 m-auto">
                    <div class="card">
                       <div class="card-body p30">
                            @if(\Session::has('success'))
                            <div class="alert alert-success">
                                <li>{{ \Session::get('success') }}</li>
                            </div><br />
                            @endif
                            @if(\Session::has('danger'))
                            <div class="alert alert-danger">
                                <li>{{ \Session::get('danger') }}</li>
                            </div><br />
                            @endif
                            <h5 class="mb20 text-muted text-center"> Packing Calculation v2.0</h5>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Enter Your Email"  autofocus>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password">
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-block btn-success btn-flat mt20" style="color:white;">
                                    {{-- {{ __('Login') }} --}}
                                   {{ Lang::get('message.login')}}
                                </button>
                                {{-- <a href="{{ url('dashboard') }}"  class="btn btn-block btn-success btn-flat mt20">Login</a> --}}
                              
                                <hr>
                                {{-- <p class="text-mutted">Or login using social:</p>
                                <div class="row">
                                    <div class="col">
                                        <span class="btn btn-primary btn-flat btn-block"> <i class="fa fa-facebook mr5"></i>Facebook Login</span>
                                    </div>
                                    <div class="col">
                                        <span class="btn btn-danger btn-flat btn-block"> <i class="fa fa-google-plus mr5"></i>Google Login</span>
                                    </div>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
    <script src="{{ asset('assets/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/tether.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
    <!-- CountUP -->
    <script src="{{ asset('assets/js/vendor/countUp/countUp.js') }}"></script>
    <!-- Charts Libraries -->
    <script src="{{ asset('assets/js/vendor/charts/highcharts.js') }}"></script>
    <!-- Datatables Libraries -->
    <script src="{{ asset('assets/js/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
            $(".alert").fadeTo(3000, 500).slideUp(1000, function () {
                $(".alert").slideUp(8000);
            });
    
        </script>
</html>