@extends('layouts.auth')

@section('style')
    <style>
        .hide {
            display: none;
        }
    </style>
@endsection

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
                                    {{ __('Verify Your Email Address') }} 
                                </h5>
                            </div>

                            @if (session('resent'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ __('A fresh verification link has been sent to your email address.') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <h6 class="text-center">
                                {{ __('Before proceeding, please check your email for a verification link.') }}
                                {{ __('If you did not receive the email, click button below.') }}
                            </h6>

                            <p class="small mb-0">
                                {{ __('Back to login?') }}  
                                <a href="{{ route('login') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                >
                                    {{ __('Click here') }} 
                                </a>
                                <form method="post" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                </form>
                            </p>

                            <form method="post" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100" id="submit">
                                    <i class="loading-icon fa fa-spinner fa-spin hide"></i>
                                    <span class="btn-txt">
                                        {{ __('Send Verification Link') }}
                                    </span>
                                </button>.
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>     
@endsection

@section('script')
    <script type="text/javascript">

        const submit = document.querySelector("#submit");
        const loadingIcon = document.querySelector(".loading-icon");
        const buttonText = document.querySelector(".btn-txt");

        submit.addEventListener("click", function () {

            const list = loadingIcon.classList;
            list.remove("hide");
            // submit.setAttribute("disabled", "");     Alternate code: submit.disabled = true;
            buttonText.textContent = "Please wait";

            /*
            setTimeout(() => {
                list.add("hide");
                submit.removeAttribute("disabled");  // Alternate code: submit.disabled = false;
                buttonText.textContent = "Log in";
            }, 3000);
            */
        });

    </script>
@endsection