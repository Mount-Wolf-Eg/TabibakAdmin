<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{route('dashboard')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="26">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-dark.png') }}" alt="" height="26">
            </span>
        </a>
        <a href="{{route('dashboard')}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="24">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-light.png') }}" alt="" height="24">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item {{app()->getLocale() == 'ar' ? 'float-start' : 'float-end'}} btn-vertical-sm-hover"
                id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">{{ __('t-menu') }}</span></li>
                <li class="nav-item">
                    <a href="{{route('dashboard')}}" @class(['nav-link', 'menu-link', 'active' => request()->routeIs('dashboard')])>
                        <i class="bi bi-speedometer2"></i>
                        <span data-key="t-dashboard">{{ __('t-dashboard') }}</span>
                        <span class="badge badge-pill badge-soft-danger" data-key="t-hot">{{ __('t-hot') }}</span>
                    </a>
                </li>

                @if(auth()->user()->can('read-role') || auth()->user()->can('view-all-role'))
                <li class="nav-item">
                    <a href="{{route('roles.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('roles.index', 'roles.show', 'roles.create', 'roles.edit')])>
                        <i class="bi bi-sign-stop"></i>
                        <span data-key="t-dashboard">{{ __('messages.roles') }}</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('read-user') || auth()->user()->can('view-all-user'))
                <li class="nav-item">
                    <a href="{{route('users.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('users.index', 'users.show', 'users.create', 'users.edit')])>
                        <i class="bi bi-person"></i>
                        <span data-key="t-dashboard">{{ __('messages.users') }}</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('read-academic-degree') || auth()->user()->can('view-all-academic-degree'))
                <li class="nav-item">
                    <a href="{{route('academic-degrees.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('academic-degrees.index', 'academic-degrees.show', 'academic-degrees.create',
                     'academic-degrees.edit')])>
                        <i class="bi bi-book"></i>
                        <span data-key="t-dashboard">{{ __('messages.academic_degrees') }}</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('read-medical-speciality') || auth()->user()->can('view-all-medical-speciality'))
                    <li class="nav-item">
                        <a href="{{route('medical-specialities.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('medical-specialities.index', 'medical-specialities.show', 'medical-specialities.create',
                     'medical-specialities.edit')])>
                            <i class="bi bi-heart-pulse-fill"></i>
                            <span data-key="t-dashboard">{{ __('messages.medical_specialities') }}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-vendor-service') || auth()->user()->can('view-all-vendor-service'))
                    <li class="nav-item">
                        <a href="{{route('vendor-services.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('vendor-services.index', 'vendor-services.show', 'vendor-services.create',
                     'vendor-services.edit')])>
                            <i class="bi bi-box-seam-fill"></i>
                            <span data-key="t-dashboard">{{ __('messages.vendor_services') }}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-disease') || auth()->user()->can('view-all-disease'))
                    <li class="nav-item">
                        <a href="{{route('diseases.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('diseases.index', 'diseases.show', 'diseases.create',
                     'diseases.edit')])>
                            <i class="bi bi-activity"></i>
                            <span data-key="t-dashboard">{{ __('messages.disease_list') }}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-patient') || auth()->user()->can('view-all-patient'))
                    <li class="nav-item">
                        <a href="{{route('patients.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('patients.index', 'patients.show', 'patients.create',
                     'patients.edit')])>
                            <i class="bi bi-person-badge"></i>
                            <span data-key="t-dashboard">{{ __('messages.patients') }}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-doctor') || auth()->user()->can('view-all-doctor'))
                    <li class="nav-item">
                        <a href="{{route('doctors.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('doctors.index', 'doctors.show', 'doctors.create',
                     'doctors.edit')])>
                            <i class="bi bi-journal-plus"></i>
                            <span data-key="t-dashboard">{{ __('messages.doctors') }}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-vendor') || auth()->user()->can('view-all-vendor'))
                    <li class="nav-item">
                        <a href="{{route('vendors.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('vendors.index', 'vendors.show', 'vendors.create',
                     'vendors.edit')])>
                            <i class="bi bi-houses"></i>
                            <span data-key="t-dashboard">{{ __('messages.vendors') }}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-article') || auth()->user()->can('view-all-article'))
                    <li class="nav-item">
                        <a href="{{route('articles.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('articles.index', 'articles.show', 'articles.create', 'articles.edit')])>
                            <i class="bi bi-postcard-fill"></i>
                            <span data-key="t-dashboard">{{ __('messages.articles') }}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-faq-subject') || auth()->user()->can('view-all-faq-subject'))
                    <li class="nav-item">
                        <a href="{{route('faq-subjects.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('faq-subjects.index', 'faq-subjects.show', 'faq-subjects.create',
                     'faq-subjects.edit')])>
                            <i class="bi bi-patch-question-fill"></i>
                            <span data-key="t-dashboard">{{ __('messages.faq_subjects') }}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-faq') || auth()->user()->can('view-all-faq'))
                    <li class="nav-item">
                        <a href="{{route('faqs.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('faqs.index', 'faqs.show', 'faqs.create', 'faqs.edit')])>
                            <i class="bi bi-question-octagon"></i>
                            <span data-key="t-dashboard">{{ __('messages.faqs') }}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-coupon') || auth()->user()->can('view-all-coupon'))
                    <li class="nav-item">
                        <a href="{{route('coupons.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('coupons.index', 'coupons.show', 'coupons.create',
                     'coupons.edit')])>
                            <i class="bi bi-card-text"></i>
                            <span data-key="t-dashboard">{{ __('messages.coupons') }}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-consultation') || auth()->user()->can('view-all-consultation'))
                    <li class="nav-item">
                        <a href="{{route('consultations.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('consultations.index', 'consultations.show')])>
                            <i class="bi bi-tv"></i>
                            <span data-key="t-dashboard">{{ __('messages.referrals') }}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-payment') || auth()->user()->can('view-all-payment'))
                    <li class="nav-item">
                        <a href="{{route('payments.index')}}" @class(['nav-link', 'menu-link',
                    'active' => request()->routeIs('payments.index', 'payments.show')])>
                            <i class="bi bi-tv"></i>
                            <span data-key="t-dashboard">{{ __('messages.payments') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
