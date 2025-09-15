<!DOCTYPE html>

<html lang="en">

<head>
    <style>

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
    <meta name="author" content="NobleUI">
    <meta name="keywords"
        content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <title> {{ isset($data['page_title']) ? $data['page_title'] : 'Cause-List' }} </title>
    <link rel="shortcut icon" href="{{ asset('assets/images/icon_habib.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&amp;display=swap"
        rel="stylesheet">
    <!-- End fonts -->

    <!-- core:css start -->
    <link rel="stylesheet" href="{{ asset('backend_assets/vendors/core/core.css') }}">
    <!-- core:css end -->
    <!--icon start-->
    <link rel="stylesheet" href="{{ asset('backend_assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <!--icon end-->


    <!--  data table ck editor start-->
    <link rel="stylesheet" href="{{ asset('backend_assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css') }}">
    <!--  data table end-->
    <!--date picker start -->
    <link rel="stylesheet" href="{{ asset('backend_assets/vendors/flatpickr/flatpickr.min.css') }}">
    <!--date picker end-->
    <link rel="stylesheet" href="{{ asset('backend_assets/vendors/select2/select2.min.css') }}">
    @stack('css')

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('backend_assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/css/ckeditor.css') }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/css/custom.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('backend_assets/css/cause_list.css') }}">  --}}
    
</head>

<body class="sidebar-dark ">
    <script src="{{ asset('assets/js/loader.js') }}"></script>

    <div class="main-wrapper">
        @include('backend.includes.sidebar')
        <div class="page-wrapper">
            @include('backend.includes.header')
            @yield('content')
            @include('backend.includes.footer')
        </div>

    </div>


    <!-- core:js -->
    <script src="{{ asset('backend_assets/vendors/core/core.js') }}"></script>
    <script src="{{ asset('backend_assets/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('backend_assets/js/bootstrap-maxlength.js') }}"></script>

    <!-- ckEditor: -->
    <script src="{{ asset('backend_assets/js/ckeditor.js') }}"></script>
    <script src="{{ asset('backend_assets/js/ckeditor_custom.js') }}"></script>


    <!--icon start-->
    <script src="{{ asset('backend_assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('backend_assets/js/template.js') }}"></script>
    <!--icon end-->

    <!-- date picker start -->
    <script src="{{ asset('backend_assets/vendors/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('backend_assets/js/flatpickr.js') }}"></script>
    <!-- date picker end-->
    <!--data table start -->
    <script src="{{ asset('backend_assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('backend_assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('backend_assets/js/data-table.js') }}"></script>
    <!--data table end -->
    {{-- select 2  --}}
    <script src="{{ asset('backend_assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('backend_assets/js/select2.js') }}"></script>
    @stack('js')



</body>

</html>
