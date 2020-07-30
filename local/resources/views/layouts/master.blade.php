<!DOCTYPE html>
<html lang="en">

<head>
    <!-- JQV Map -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/jqvmap/jqvmap.css') }}">
    <!-- Datatables -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/datatables/dataTables.bootstrap4.min.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Packing Calculation v2.0</title>
    <meta name='subject' content='Diaresibon.'>
    <meta name="description" content="Diaresibon." />
    <meta name='copyright' content='Diaresibon'>
    <meta name='robots' content='index,follow'>
    <meta name='author' content='Diaresibon'>
    <link rel="icon" type="image/jpg" href="{{ asset('logo.jpg') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU"
        crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/fonts/Linearicons-Free-v1.0.0/Linearicons-Free-v1.0.0.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    @yield('css')
</head>
<style>
    
    body{
            font-size:14px;
    }
</style>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <a href="javascript:void(0)" class="sidebar-brand">
                    <h4><span class="text-purple">Packing Calculation v2.0</span>  </h4>
            </a>
            <ul class="list-unstyled">
                <li class="sub-menu">
                    <a href="#import" data-toggle="collapse" aria-expanded="false">
                        <i class="fa fa-file-excel"></i>
                        <span class="nav-text"> &nbsp;จัดการ Shipment File</span>
                        <span class="fa fa-angle-left angle"></span>
                    </a>
                    <div class="sub-content">
                        <ul class="collapse list-unstyled" id="import">
                            <li>
                                <a href="{{ url('productimport') }}">Import Shipment File</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="sub-menu">
                    <a href="#box" data-toggle="collapse" aria-expanded="false">
                        <i class="fas fa-archive"></i>
                        <span class="nav-text">จัดการ Pack List </span>
                        <span class="fa fa-angle-left angle"></span>
                    </a>
                    <div class="sub-content">
                        <ul class="collapse list-unstyled" id="box">
                            <li>
                                <a href="{{ url('box') }}">รายการสินค้า Pack list</a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- <li class="sub-menu">
                    <a href="#contianer" data-toggle="collapse" aria-expanded="false">
                        <i class="fas fa-dolly-flatbed"></i>
                        <span class="nav-text">จัดการ Pallet </span>
                        <span class="fa fa-angle-left angle"></span>
                    </a>
                    <div class="sub-content">
                        <ul class="collapse list-unstyled" id="contianer">
                            <li>
                                <a href="{{ url('container') }}">จัดการ Pallet</a>
                            </li>
                        </ul>
                    </div>
                </li> --}}
                <li class="sub-menu">
                    <a href="#container" data-toggle="collapse" aria-expanded="false">
                        <i class="fas fa-th"></i>
                        <span class="nav-text">จัดการ Container</span>
                        <span class="fa fa-angle-left angle"></span>
                    </a>
                    <div class="sub-content">
                            <ul class="collapse list-unstyled" id="container">
                                <li>
                                    <a href="{{ url('adjust') }}">Container adjust</a>
                                </li>
                            </ul>
                        </div>
                </li>
                {{-- <li class="sub-menu">
                    <a href="#report" data-toggle="collapse" aria-expanded="false">
                        <i class="fas fa-th"></i>
                        <span class="nav-text">จัดการ Report</span>
                        <span class="fa fa-angle-left angle"></span>
                    </a>
                    <div class="sub-content">
                            <ul class="collapse list-unstyled" id="report">
                                <li>
                                    <a href="{{ url('report') }}"> รายการ Report</a>
                                </li>
                            </ul>
                        </div>
                </li> --}}
                {{-- <li class="sub-menu">
                    <a href="#container" data-toggle="collapse" aria-expanded="false">
                        <i class="fas fa-shuttle-van"></i>
                        <span class="nav-text">จัดการ Container</span>
                        <span class="fa fa-angle-left angle"></span>
                    </a>
                    <div class="sub-content">
                        <ul class="collapse list-unstyled" id="container">
                            <li>
                                <a href="#">เลือก สินค้าเข้า Container</a>
                            </li>
                        </ul>
                    </div>
                </li> --}}
                <li class="sub-menu">
                    <a href="#master" data-toggle="collapse" aria-expanded="false">
                        <i class="fab fa-medium-m"></i>
                        <span class="nav-text">จัดการ Master</span>
                        <span class="fa fa-angle-left angle"></span>
                    </a>
                    <div class="sub-content">
                        <ul class="collapse list-unstyled" id="master">
                            {{-- <li>
                                <a href="{{ url('master') }}">รายการ Master</a>
                            </li> --}}
                            <li>
                                <a href="{{ url('item') }}">รายการ Item</a>
                            </li>
                            <li>
                                <a href="{{ route('palate.create') }}">รายการ Pallet</a>
                            </li>
                            {{-- <li>
                                <a href="{{ url('ShowCustomer') }}">รายการ Customer</a>
                            </li> --}}
                        </ul>
                    </div>
                </li>
                <!-- <li class="sub-menu">
                        <a href="#colors" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-paint-brush"></i> 
                            <span class="nav-text">Color Pallets <small class="bg-warning font10 p0 pl8 pr8 rounded text-white"> 5</small></span>
                            <span class="fa fa-angle-left angle"></span>
                        </a>
                        <div class="sub-content">
                            <ul class="collapse list-unstyled" id="colors">
                                <li>
                                   <a href="color-primary.html">Primary Pallete</a>
                                </li>
                                <li>
                                   <a href="color-success.html">Success Pallete</a>
                                </li>
                                <li>
                                   <a href="color-info.html">Info Pallete</a>
                                </li>
                                <li>
                                   <a href="color-warning.html">Warning Pallete</a>
                                </li>
                                <li>
                                   <a href="color-danger.html">Danger Pallete</a>
                                </li>
                            </ul>
                        </div>
                    </li> -->
                <!-- <li class="sub-menu">
                        <a href="#forms" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-check-square-o"></i> 
                            <span class="nav-text">Forms</span>
                            <span class="fa fa-angle-left angle"></span>
                        </a>
                        <div class="sub-content">
                            <ul class="collapse list-unstyled" id="forms">
                                <li>
                                   <a href="form-basic.html">Form Elements</a>
                                </li>
                                <li>
                                   <a href="form-advance.html">Advance Form</a>
                                </li>
                            </ul>
                        </div>
                    </li> -->
                <!-- <li class="sub-menu">
                        <a href="#tables" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-table"></i> 
                            <span class="nav-text">Tables</span>
                            <span class="fa fa-angle-left angle"></span>
                        </a>
                        <div class="sub-content">
                            <ul class="collapse list-unstyled" id="tables">
                                <li>
                                   <a href="table-colors.html">Colors Tables</a>
                                </li>
                                <li>
                                   <a href="table-elements.html">Table Elements</a>
                                </li>
                            </ul>
                        </div>
                    </li> -->
                <!-- <li class="sub-menu">
                        <a href="#ui" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-vcard"></i> 
                            <span class="nav-text">UI Elements</span>
                            <span class="fa fa-angle-left angle"></span>
                        </a>
                        <div class="sub-content">
                            <ul class="collapse list-unstyled" id="ui">
                                <li>
                                   <a href="ui-elements-alerts.html">Alerts</a>
                                </li>
                                <li>
                                   <a href="ui-elements-progressbars.html">Progress bars</a>
                                </li>
                                <li>
                                   <a href="ui-elements-thumbnails.html">Thumbnails</a>
                                </li>
                                <li>
                                   <a href="ui-elements-typography.html">Typography</a>
                                </li>
                            </ul>
                        </div>
                    </li> -->
                <!-- <li class="sub-menu">
                        <a href="#userpages" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-user-circle-o"></i> 
                            <span class="nav-text">User Pages</span>
                            <span class="fa fa-angle-left angle"></span>
                        </a>
                        <div class="sub-content">
                            <ul class="collapse list-unstyled" id="userpages">
                                <li>
                                   <a href="user-login.html">Login</a>
                                </li>
                                <li>
                                   <a href="user-register.html">Register</a>
                                </li>
                                <li>
                                   <a href="user-profile.html">Profile</a>
                                </li>
                                <li>
                                   <a href="user-404.html">404 Page</a>
                                </li>
                            </ul>
                        </div>
                    </li> -->
                <!-- <li class="sub-menu">
                        <a href="#gallary" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-photo"></i> 
                            <span class="nav-text">Galleries</span>
                            <span class="fa fa-angle-left angle"></span>
                        </a>
                        <div class="sub-content">
                            <ul class="collapse list-unstyled" id="gallary">
                                <li>
                                   <a href="gallery-basic.html">Basic Gallery</a>
                                </li>
                                <li>
                                   <a href="gallery-masonry.html">Masonry Layout</a>
                                </li>
                            </ul>
                        </div>
                    </li> -->
            </ul>
        </nav>

        <!-- Navbar -->
        <nav class="navbar shadow navbar-expand navbar-light bg-light fixed-top">
            <ul class="navbar-nav m0 p10 pl15">
                <button type="button" id="sidebarCollapse" class="btn bg-white border navbar-btn btn-social-md rounded-circle">
                    <i class="fas fa-bars outline-0 border-0"></i>
                </button>
                <a href="javascript:void(0)" class="navbar-brand d-block mt2">
                    <h3><b class="text-purple">BS4</b></h3>
                </a>
                <!-- <form action="">
                        <input type="search" class="form-control form-control-md mt5 rounded-lg" placeholder="Search">
                    </form> -->
            </ul>
            <ul class="navbar-nav ml-auto pr20">
                <!-- <li class="nav-item dropdown transform navbar-dropdown">
                        <a class="nav-link" href="#" data-toggle="dropdown" aria-haspopup="" aria-expanded="false">
                            <i class="fa fa-envelope-o font16 notifacion-bdge"></i>
                            <sup class="badge bdge badge-success font10">3</sup>
                        </a>
                        <div class="dropdown-menu-right border border-gray-light dropdown-menu notification">
                            <div class="card m-0  border-0">
                                <div class="card-body m0 p15 font13">
                                    <div class="row pt5 pb0">
                                        <div class="col-2">
                                            <a href="javascript:void(0)">
                                                <img src="assets/imgs/Avatars/avatar8.jpg" class="circle-40 m-auto" alt="">
                                            </a>
                                        </div>
                                        <div class="col-10">
                                            <strong>John Doe</strong> Followed <strong>john Smith</strong>
                                            <p class="title font11">1 hour ago | October, 13, 17</p>
                                        </div>
                                    </div>
                                    <div class="row pt5 pb0">
                                        <div class="col-2">
                                            <a href="javascript:void(0)">
                                                <img src="assets/imgs/Avatars/avatar6.jpg" class="circle-40 m-auto" alt="">
                                            </a>
                                        </div>
                                        <div class="col-10">
                                            <strong>John Doe</strong> love <strong>john Smith</strong>
                                            <p class="title font11">3 hour ago | October, 13, 17</p>
                                        </div>
                                    </div>
                                    <div class="row pt5 pb0">
                                        <div class="col-2">
                                            <a href="javascript:void(0)">
                                                <img src="assets/imgs/Avatars/avatar7.jpg" class="circle-40 m-auto" alt="">
                                            </a>
                                        </div>
                                        <div class="col-10">
                                            <strong>John Doe</strong> Reacted <strong>john Smith</strong>
                                            <p class="title font11">9 hour ago | October, 13, 17</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer border-0 text-center font12">
                                    <a href="javascript:void(0)"><i class="fa fa-envelope"></i> <strong>Read All Messages</strong></a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown transform navbar-dropdown mr10">
                        <a class="nav-link" href="#" data-toggle="dropdown" aria-haspopup="" aria-expanded="false">
                            <i class="fa fa-bell-o font19 notifacion-bdge"></i>
                            <sup class="badge badge-danger bdge font10">4</sup>
                        </a>
                        <div class="dropdown-menu border border-gray-light dropdown-menu-right font14" aria-labelledby="dropdownMenuButton">

                            <div class="dropdown-item pt4 pb4"> <a href="">
                                <strong>Jane Doe</strong> and <strong>John Roe</strong> Followed you<br><span class="title font11">2 minutes ago </span></a>
                            </div><hr class="m0 p0">
                            <div class="dropdown-item pt4 pb4"> <a href="">
                                <strong>Jane Doe</strong> and <strong>janie</strong> like your post<br><span class="title font11">5 minutes ago </span></a>
                            </div><hr class="m0 p0">
                            <div class="dropdown-item pt4 pb4"> <a href="">
                                <strong>John Doe</strong> and <strong>jane</strong> Answered on your post<br><span class="title font11">12 minutes ago </span></a>
                            </div><hr class="m0 p0">
                            <div class="dropdown-item pt4 pb4"> <a href="">
                                <strong>Johnny Doe</strong> Asked a Question from you<br><span class="title font11">20 minutes ago </span></a>
                            </div><hr class="m0 p0">

                            <hr class="m0">
                            <div class="pt10 pb4 text-center"> <a href="" class="text-center">See All Notifications </a></div>
                        </div>
                    </li> -->
                <li class="nav-item dropdown transform navbar-dropdown">
                    
                    <a class="nav-link circle-20 border-0 outline-0 p0 pr25 pt2" href="#" data-toggle="dropdown"
                        aria-haspopup="" aria-expanded="false">
                        <img src="{{ asset('assets/imgs/Avatars/avatar12.png') }}" alt="" class="circle-30" style="margin: 0;padding: 0">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right border border-gray-light font14">
                        {{-- <a class="dropdown-item pt5 pb5" href="user-profile.html"><span class="fa fa-user pr10 font14"></span>Profile</a> --}}
                        {{-- <a class="dropdown-item pt5 pb5" href="user-lock-screen.html"><span class="fa fa-lock pr10 font14"></span>Lock
                            Screen</a> --}}
                        <a class="dropdown-item pt5 pb5" href="#"><span class="fa fa-user pr10 font14"></span>{{Auth::user()->name}}</a>
                        <a class="dropdown-item pt5 pb5"  href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                         <span class="fas fa-sign-out-alt pr10 font14"></span>Logout</a>

                         <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Page content -->
        <div class="content container-fluid content">


            @yield('content')

        </div>
    </div>

    <!-- footer -->
    <nav class="footer fixed-bottom text-left">
        <div class="text-muted pr40 font13">
            <span>Copyright <script>
                    document.write(new Date().getFullYear())

                </script> © <a href="http://www.diaresibon.com/" target="_blank">Diaresibon</a></span>
        </div>
    </nav>

    <!-- Theme App -->

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
    @yield('js')

</body>

</html>
