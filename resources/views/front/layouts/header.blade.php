<!-- header start -->
<header>
    <div class="header-top-bar theme-bg d-none d-lg-block">
        <div class="container xs-full">
            <div class="row">
                <div class="col-sm-8"></div>
                <div class="col-sm-4">
                    <div class="social-icon text-end">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-google"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="menu-area ">
        <div class="container md-full xs-full">
            <div class="row align-items-center">
                <div class="col-lg-3 col-6">
                    <div class="logo">
                        <a href="index.html"><img src="{{ URL::asset('assets/images/favicon.ico') }}" alt="" style="max-width: 40px"/></a>
                    </div>
                </div>
                <div class="col-lg-9 col-6">
                    <div class="main-menu text-end">
                        <div class="basic-menu">
                            <nav id="mobile-nav">
                                <ul>
                                    <li><a href="{{route('front.home')}}">Home</a></li>
                                    <li><a href="{{route('front.about')}}">about us</a></li>
                                    <li><a href="doctor.html">Doctors</a>
                                        <ul>
                                            <li><a href="doctor.html">doctor style 1</a></li>
                                            <li><a href="doctor-2.html">doctor style 2</a></li>
                                            <li><a href="doctor-single.html">doctor Details</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="blog-left-sidebar.html">blog</a>
                                        <ul>
                                            <li><a href="blog-2-col.html">blog 2 col</a></li>
                                            <li><a href="blog-3-col.html">blog 3 col</a></li>
                                            <li><a href="blog-no-sidebar.html">blog no sidebar</a></li>
                                            <li><a href="blog-right-sidebar.html">blog right sidebar</a></li>
                                            <li><a href="blog-details.html">blog Details 1</a></li>
                                            <li><a href="blog-details-right-sidebar.html">blog Details 2</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="{{route('front.contact')}}">contact us</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="nav-bar text-white d-lg-none text-end">
                        <button class="nav-bar"><i class="fa fa-bars"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>
<!-- header end -->
