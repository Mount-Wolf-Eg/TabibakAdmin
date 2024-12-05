@extends('dashboard.layouts.master-auth')
@section('title') {{__('auth.sign_in')}} @endsection
@section('content')

    <div class="w-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="auth-card mx-lg-3">
                        <div class="card border-0 mb-0">
                            <div class="card-header bg-primary border-0" style="padding: 0;">
                                <img src="{{ URL::asset('assets/images/LoginBanner.png') }}" alt="" class="img-fluid">
                            </div>
                            <div class="card-body">
                                @if(session()->has('error'))
                                    <div class="alert alert-borderless alert-danger text-center mb-2 mx-2" role="alert">
                                        {{ session('error') }}
                                    </div>
                                @elseif(session()->has('success'))
                                    <div class="alert alert-borderless alert-success text-center mb-2 mx-2" role="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                {{--<p class="text-muted fs-15">Login</p>--}}
                                <div class="p-2">
                                    <form method="POST" action="{{ route('checkCredentials') }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="email" class="form-label">{{__('messages.email')}}</label>
                                            <input id="email" type="email" class="form-control text-{{app()->getLocale() == 'ar' ? 'end' : 'start'}} @error('email') is-invalid @enderror" name="email" required autocomplete="email" autofocus placeholder="{{__('auth.enter_email')}}">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="float-{{app()->getLocale() == 'ar' ? 'start' : 'end'}}">
                                                <a href="{{ route('password.request') }}" class="text-muted">{{__('auth.forget_pass')}}</a>
                                            </div>
                                            <label class="form-label" for="password-input">{{__('messages.password')}}</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input id="password" type="password"
                                                    class="form-control text-{{app()->getLocale() == 'ar' ? 'end' : 'start'}} @error('password') is-invalid @enderror"
                                                    name="password" required autocomplete="current-password" placeholder="{{__('auth.enter_pass')}}">
                                                <button
                                                    class="btn btn-link position-absolute {{app()->getLocale() == 'ar' ? 'start' : 'end'}}-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button" id="password-addon" onclick="togglePasswordVisibility()"><i id="eyeIcon" class="bi bi-eye"></i></button>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="pb-3 {{app()->getLocale() == 'ar' ? 'float-end' : 'float-start'}}">
                                            <input class="form-check-input cursor-pointer mx-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="auth-remember-check">{{__('auth.remember_me')}}</label>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-primary w-100" type="submit">{{__('auth.sign_in')}}</button>
                                        </div>

                                        {{-- <div class="mt-4 pt-2 text-center">
                                            <div class="signin-other-title">
                                                <h5 class="fs-13 mb-4 title">Sign In with</h5>
                                            </div>
                                            <div class="pt-2 hstack gap-2 justify-content-center">
                                                <button type="button" class="btn btn-soft-primary btn-icon"><i
                                                        class="ri-facebook-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-soft-danger btn-icon"><i
                                                        class="ri-google-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-soft-dark btn-icon"><i
                                                        class="ri-github-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-soft-info btn-icon"><i
                                                        class="ri-twitter-fill fs-16"></i></button>
                                            </div>
                                        </div> --}}
                                    </form>

                                    {{--<div class="text-center mt-5">
                                        <p class="mb-0">Forget Password ? <a href="{{ route('register') }}"
                                                class="fw-semibold text-secondary text-decoration-underline"> Sign Up</a>
                                        </p>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->

        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> Tell Doc <i class="mdi mdi-heart text-danger"></i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection
@section('scripts')
    <script src="{{ URL::asset('assets/js/pages/password-addon.init.js') }}"></script>
@endsection
