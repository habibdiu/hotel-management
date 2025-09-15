<!DOCTYPE html>

<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
    <meta name="author" content="NobleUI">
    <meta name="keywords"
        content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <title>Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&amp;display=swap"
        rel="stylesheet">
    <!-- End fonts -->

    <!-- core:css -->
    <link rel="stylesheet" href="{{ asset('backend_assets/vendors/core/core.css') }}">
    <!-- endinject -->



    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('backend_assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('backend_assets/css/style.min.css') }}">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="{{ asset('backend_assets/images/favicon.png') }}" />
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">

                <div class="row w-100 mx-0 auth-page">
                    <div class="col-md-8 col-xl-6 mx-auto">
                        <div class="card">
                            <div class="row">
                                <div class="col-md-4 pe-md-0">
                                    <div class="auth-side-wrapper">
                                        <img class="w-100" src="{{ asset('backend_assets/images/logo.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-md-8 ps-md-0">
                                    <div class="auth-form-wrapper px-4 py-5">
                                        <a href="" class="noble-ui-logo d-block mb-2">Hotel<span>Book</span></a>
                                        @if(session('error'))
                                            <div class="popupRightBottom text-danger timeout mt-1">
                                                {{ session('error') }}</div>
                                        @endif
                                        <form class="forms-sample" action="{{ route('login') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="userEmail" class="form-label">Email address</label>
                                                <input type="email" value="{{ old('email') }}" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="userEmail" placeholder="Email">
                                            </div>  
                                            <div class="mb-3">
                                                <label for="userPassword" class="form-label">Password</label>
                                                <input type="password" value="" name="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="userPassword" autocomplete="current-password"
                                                    placeholder="Password">
                                                    <input type="checkbox" class="mt-1" onclick="show_password()" >Show Password
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" name="checkbox"
                                                    class="form-check-input @error('checkbox') is-invalid @enderror"
                                                    id="authCheck">

                                                <label class="form-check-label" for="authCheck">
                                                    I am not robot
                                                </label>
                                            </div>
                                            <div>

                                                <input type="submit"
                                                    class="btn btn-primary text-white me-2 mb-2 mb-md-0"
                                                    value="sign In">
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- core:js -->
    <script src="{{ asset('backend_assets/vendors/core/core.js') }}"></script>
    <!-- endinject -->



    <!-- inject:js -->
    <script src="{{ asset('backend_assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('backend_assets/js/template.js') }}"></script>
    <!-- endinject -->


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function show_password(){
            var input  = document.getElementById('userPassword'); 
            if(input.type === 'password'){
                input.type = 'text';
            }else{
                input.type = 'password'
            }
        }
    </script>
</body>


</html>
