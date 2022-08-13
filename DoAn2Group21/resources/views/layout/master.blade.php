<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @stack('title')
    @stack('css')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">

    <link rel="stylesheet" href="{{asset('vendors/iconly/bold.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/fontawesome/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/perfect-scrollbar/perfect-scrollbar.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/bootstrap-icons/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="shortcut icon" href="{{asset('images/favicon.svg" type="image/x-icon')}}">
 
</head>

<body>
<div id="app">

    <!-- Sidebar start --->

    @include('layout.sidebar')

    <!-- Sidebar end --->

    <div id="main">

        <!-- Navbar start --->

        @include('layout.header')

        <!-- Navbar end --->

        <!-- Content start --->

        <div class="page-heading">
            <h3>
                @if (isset($title))
                    {{$title}}
                @endif
            </h3>
        </div>

        @yield('content')

        <!-- Content end --->

        <!-- Footer start --->

        @include('layout.footer')

        <!-- Footer end --->
    </div>
</div>
@stack('js')
<script src="{{asset('vendors/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('vendors/fontawesome/all.min.js')}}"></script>

{{--<script src="{{asset('vendors/apexcharts/apexcharts.js')}}"></script>--}}
{{--<script src="{{asset('js/pages/dashboard.js')}}"></script>--}}

<script src="{{asset('js/main.js')}}"></script>

</body>

</html>
