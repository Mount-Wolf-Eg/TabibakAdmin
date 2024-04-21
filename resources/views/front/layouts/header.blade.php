<!-- header start -->
<header>
    <div class="header-top-bar theme-bg d-none d-lg-block">
        <div class="container xs-full">
            <div class="row">
                <div class="col-sm-8"></div>
                <div class="col-sm-4">
                    <div class="social-icon text-{{app()->getLocale() == 'ar' ? 'start' : 'end'}}">
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
                        <a href="{{route('front.home')}}"><img src="{{ URL::asset('assets/images/favicon.ico') }}" alt=""
                                style="max-width: 40px" /></a>
                    </div>
                </div>
                <div class="col-lg-9 col-6">
                    <div class="main-menu text-{{app()->getLocale() == 'ar' ? 'start' : 'end'}}">
                        <div class="basic-menu">
                            <nav id="mobile-nav">
                                <ul>
                                    <li><a href="{{ route('front.home') }}">{{__('messages.home')}}</a></li>
                                    <li><a href="{{ route('front.about') }}">{{__('messages.aboutUs')}}</a></li>
                                    <li><a href="{{ route('front.doctors') }}">{{__('messages.doctors')}}</a></li>
                                    <li><a href="{{ route('front.contact') }}">{{__('messages.contactUs')}}</a></li>
                                    <li>
                                        <div
                                            class="dropdown topbar-head-dropdown topbar-tag-dropdown justify-content-end">
                                            <button type="button"
                                                class="btn-icon btn-topbar text-reset rounded-circle fs-14 fw-medium"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                @switch(app()->getLocale())
                                                    @case('ar')
                                                        @php($lang = 'العربية')
                                                    @break

                                                    @default
                                                        @php($lang = 'English')
                                                    @break
                                                @endswitch
                                                <img src="{{ URL::asset('assets/images/flags/' . app()->getLocale() . '.svg') }}"
                                                    class="rounded-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"
                                                    alt="" height="18">
                                                <span id="lang-name">{{ $lang }}</span>
                                            </button>
                                            <div
                                                class="dropdown-menu dropdown-menu-end text-{{ app()->getLocale() == 'ar' ? 'end' : 'start' }}">
                                                @foreach (\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                    <!-- item-->
                                                    <a rel="alternate" hreflang="{{ $localeCode }}"
                                                        href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                                        class="dropdown-item notify-item language py-2"
                                                        title="{{ $properties['native'] }}">
                                                        <img src="{{ URL::asset('assets/images/flags/' . $localeCode . '.svg') }}"
                                                            alt="{{ $properties['native'] . '-image' }}"
                                                            class="{{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }} rounded-circle"
                                                            height="18">
                                                        <span class="align-middle">{{ $properties['native'] }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="nav-bar text-white d-lg-none text-{{ app()->getLocale() == 'ar' ? 'start' : 'end' }}">
                        <button class="nav-bar"><i class="fa fa-bars"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>
<!-- header end -->
