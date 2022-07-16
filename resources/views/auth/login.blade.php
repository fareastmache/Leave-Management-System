@extends('layouts.auth')

@section('style')
    <style>
        .hide {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
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
                                    {{ __('Login to Your Account') }}
                                </h5>
                                <p class="text-center small">
                                    {{ __('Enter your email & password to login') }}
                                </p>
                            </div>

                            <!-- Start Log in form --> 
                            <form method="post" action="{{ route('login') }}" class="row g-3" novalidate>
                                @csrf

                                <div class="col-12">
                                    <label for="email" class="form-label">
                                        {{ __('Email') }}
                                    </label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" required autocomplete="email" autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="password" class="form-label">
                                        {{ __('Password') }}
                                    </label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-eye-slash" id="togglePassword"></i></span>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" required autocomplete="current-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <p class="small mb-0">
                                        {{ __('Forgot password?') }} 
                                        <a href="{{ route('password.request') }}">
                                            {{ __('Click here') }} 
                                        </a>
                                    </p>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100"  id="login">
                                        <i class="loading-icon fa fa-spinner fa-spin hide"></i>
                                        <span class="btn-txt">
                                            {{ __('Log In') }} 
                                        </span>
                                    </button>
                                </div>

                            </form>
                            <!-- End Log in form --> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">

        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {

            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.classList.toggle("bi-eye");
        });

        const login = document.querySelector("#login");
        const loadingIcon = document.querySelector(".loading-icon");
        const buttonText = document.querySelector(".btn-txt");

        login.addEventListener("click", function () {

            const list = loadingIcon.classList;
            list.remove("hide");
            // login.setAttribute("disabled", "");     Alternate code: login.disabled = true;
            buttonText.textContent = "Please wait";

            /*
            setTimeout(() => {
                list.add("hide");
                login.removeAttribute("disabled");  // Alternate code: login.disabled = false;
                buttonText.textContent = "Log in";
            }, 3000);
            */
        });

    </script>
@endsection