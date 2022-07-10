@extends('layouts.auth')

@section('content')
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                    <!-- Start Logo --> 
                    <div class="d-flex justify-content-center py-4">
                        <a href="#" class="logo d-flex align-items-center w-auto">
                            <img src="{{ asset('img/humanops-logo.svg') }}" alt="logo"/>
                        </a>
                    </div>
                    <!-- End Logo --> 
                        
                    <div class="card mb-3">
                        <div class="card-body">

                            <div class="pt-4 pb-2">
                                <h5 class="card-title text-center pb-0 fs-4">
                                    {{ __('Reset Password') }} 
                                </h5>
                                <p class="text-center small">
                                    {{ __('Enter New Password to Reset Password') }} 
                                </p>
                            </div>

                            <!-- Start Log in form --> 
                            <form method="post" action="{{ route('password.update') }}" class="row g-3" novalidate>
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="col-12">
                                    <label for="email" class="form-label">
                                        {{ __('Email') }} 
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            
                                <div class="col-12">
                                    <label for="password" class="form-label">
                                        {{ __('Password') }} 
                                    </label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" required autocomplete="new-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
        
                                <div class="col-12">
                                    <label for="password-confirm" class="form-label">
                                        {{ __('Confirm Password') }} 
                                    </label>
                                    <input type="password" class="form-control" name="password_confirmation" id="password-confirm" required autocomplete="new-password">
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100" name="reset">
                                        {{ __('Reset') }} 
                                    </button>
                                </div>

                            </form>
                            <!-- End Log in form --> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>      
@endsection
