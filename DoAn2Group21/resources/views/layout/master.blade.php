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
    <link rel="stylesheet" href="{{asset('css/widgets/loader.css')}}">
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

<div class="wrapper">
    <div class="loader">
        <img src="{{asset('vendors/svg-loaders/prox.gif')}}" class="me-4" style="width: 4rem" alt="audio">
    </div>
</div>

@stack('js')
<script src="{{asset('vendors/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('vendors/fontawesome/all.min.js')}}"></script>

{{--<script src="{{asset('vendors/apexcharts/apexcharts.js')}}"></script>--}}
{{--<script src="{{asset('js/pages/dashboard.js')}}"></script>--}}

<script src="{{asset('js/main.js')}}"></script>
<script>
   const fadeOut = () => {
       const loaderWrapper = document.querySelector('.wrapper');
         loaderWrapper.classList.add('fade-out');
   } 
   window.addEventListener('load', fadeOut);
</script>
<script>
    $(function () {
        $('#clearAll').click(function () {
            if(confirm('Are you sure you want to clear all notifications?')){
                $.ajax({
                    url: "{{route('user.clearAllNotifications', auth()->user()->id)}}",
                    type: "GET",
                    success: function () {
                        location.reload();
                    },
                    error: function(){
                        alert('error!');
                    }
                });
            }
        });
    });
</script>
</body>

</html>
