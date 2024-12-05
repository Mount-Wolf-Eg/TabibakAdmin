<div class="top-tagbar">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <div class="col-md-4 col-9">
                <div class="fs-14 fw-medium">
                    <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                        <span class="hamburger-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="col-md-4 col-6 d-none d-xxl-block">
                <div class="d-flex align-items-center justify-content-center gap-3 fs-14 fw-medium">
                    <div>
                        <i class="bi bi-envelope align-middle {{app()->getLocale() == 'ar' ? 'ms-2' : 'me-2'}}"></i> support@tell-doc.com
                    </div>
                    <div>
                        <i class="bi bi-globe align-middle {{app()->getLocale() == 'ar' ? 'ms-2' : 'me-2'}}"></i> www.tell-doc.com
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-3">
                <div class="d-flex align-items-center justify-content-end gap-3 fs-14">
                    <hr class="vr d-none d-lg-block">
                    <div class="dropdown topbar-head-dropdown topbar-tag-dropdown justify-content-end">
                        <button type="button" class="btn btn-icon btn-topbar text-reset rounded-circle fs-14 fw-medium"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @switch(app()->getLocale())
                                @case('ar')
                                    @php($lang = 'العربية')
                                    @break
                                @default
                                    @php($lang = 'English')
                                    @break
                            @endswitch
                            <img src="{{ URL::asset('assets/images/flags/' . app()->getLocale() .'.svg') }}" class="rounded-circle {{app()->getLocale() == 'ar' ? 'ms-2' : 'me-2'}}"
                                 alt="" height="18">
                            <span id="lang-name">{{ $lang }}</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end text-{{app()->getLocale() == 'ar' ? 'end' : 'start'}}">
                            @foreach(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <!-- item-->
                                <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="dropdown-item notify-item language py-2" title="{{ $properties['native'] }}">
                                    <img src="{{ URL::asset('assets/images/flags/' . $localeCode .'.svg') }}" alt="{{ $properties['native'] . '-image' }}"
                                         class="{{app()->getLocale() == 'ar' ? 'ms-2' : 'me-2'}} rounded-circle" height="18">
                                    <span class="align-middle">{{ $properties['native'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <hr class="vr d-none d-lg-block">
                    <div class="dropdown topbar-head-dropdown topbar-tag-dropdown justify-content-end">
                        <button type="button" class="btn btn-icon btn-topbar text-reset rounded-circle fs-14 fw-medium"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{auth()->user()->avatar->asset_url ?? asset('assets/images/users/user-dummy-img.jpg')}}" class="rounded-circle {{app()->getLocale() == 'ar' ? 'ms-2' : 'me-2'}}"
                                 alt="" width="22" height="22">
                            <span id="lang-name">{{ ucfirst(auth()->user()->first_name) }}</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end text-{{app()->getLocale() == 'ar' ? 'end' : 'start'}}">
                            {{--item--}}
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="bi bi-person-circle {{app()->getLocale() == 'ar' ? 'ms-2' : 'me-2'}}"></i>
                                <span class="align-middle">@lang('messages.profile')</span>
                            </a>
                            <a class="dropdown-item" href="{{ route('change-password') }}">
                                <i class="bi bi-gear {{app()->getLocale() == 'ar' ? 'ms-2' : 'me-2'}}"></i>
                                <span class="align-middle">@lang('messages.change_password')</span>
                            </a>
                            <button type="button" id="logout-btn" class="dropdown-item w-100" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                <i class="bi bi-box-arrow-right {{app()->getLocale() == 'ar' ? 'ms-2' : 'me-2'}}"></i>
                                <span class="align-middle">@lang('messages.logout')</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">@lang('messages.logout')</h5>
                <button type="button" class="btn btn-flat-light close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
                </button>
            </div>
            <div class="modal-body text-start">
                @lang('messages.confirm.logout')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('messages.confirm.cancel')</button>
                <a class="btn btn-success" href="{{ route('logout') }}">@lang('messages.logout')</a>
            </div>
        </div>
    </div>
</div>
