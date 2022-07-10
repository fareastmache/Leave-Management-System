@extends('layouts.auth')

@section('content')
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                    
                    <!-- Start Logo --> 
                    <div class="d-flex justify-content-center py-4">
                        <a href="{{ route('login') }}" class="logo d-flex align-items-center w-auto">
                            <img src="{{ asset('img/humanops-logo.svg') }}" alt="logo"/>
                        </a>
                    </div>
                    <!-- End Logo --> 

                    <div class="card mb-3">
                        <div class="card-body">

                            <div class="pt-4 pb-2">
                                <h5 class="card-title text-center pb-0 fs-4">
                                    {{ __('Reset Your Account Password') }} 
                                </h5>
                                <p class="text-center small">
                                    {{ __('Enter Email Address To Send Reset Password Link') }} 
                                </p>
                            </div>

                            <!-- Start Log in form --> 
                            <form method="post" action="{{ route('password.email') }}" class="row g-3" novalidate>
                                @csrf
                                
                                <div class="col-12">
                                    <label for="email" class="form-label">
                                        {{ __('Email') }} 
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" required autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <p class="small mb-0">
                                        {{ __('Back to login?') }}  
                                        <a href="{{ route('login') }}">
                                            {{ __('Click here') }} 
                                        </a>
                                    </p>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100" name="submit">
                                        {{ __('Submit') }} 
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
