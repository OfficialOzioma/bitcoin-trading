<!DOCTYPE HTML>
<html class="no-js" lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:type" content="article" />
    {{--    <meta property="og:title" content="{{allsetting('app_title')}}"/>--}}
    <meta property="og:image" content="{{asset('assets/admin/images/logo.svg')}}">
    <meta property="og:site_name" content="Cpoket"/>
    <meta property="og:url" content="{{url()->current()}}"/>
    <meta itemprop="image" content="{{asset('assets/admin/images/logo.svg')}}" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap.min.css')}}">
    <!-- metismenu CSS -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/metisMenu.min.css')}}">
    <!-- fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/font-awesome.min.css')}}">
    {{--for toast message--}}
    <link href="{{asset('assets/toast/vanillatoasts.css')}}" rel="stylesheet" >
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{asset('assets/admin/style.css')}}">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/responsive.css')}}">
    @yield('style')
    <title>@yield('title') Cpockate</title>
</head>

<body class="body-bg">

    @yield('content')

<!-- js file start -->

<!-- JavaScript -->
<script src="{{asset('assets/admin/js/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="{{asset('assets/admin/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/admin/js/metisMenu.min.js')}}"></script>
{{--toast message--}}
<script src="{{asset('assets/toast/vanillatoasts.js')}}"></script>

<script src="{{asset('assets/admin/js/main.js')}}"></script>


    @if(session()->has('success'))
        <script>
            window.onload = function () {
                VanillaToasts.create({
                    //  title: 'Message Title',
                    text: '{{session('success')}}',
                    backgroundColor: "linear-gradient(135deg, #73a5ff, #5477f5)",
                    type: 'success',
                    timeout: 10000
                });
            }

        </script>

    @elseif(session()->has('dismiss'))
        <script>
            window.onload = function () {

                VanillaToasts.create({
                    // title: 'Message Title',
                    text: '{{session('dismiss')}}',
                    type: 'warning',
                    timeout: 10000

                });
            }
        </script>

    @elseif($errors->any())
        @foreach($errors->getMessages() as $error)
            <script>
                window.onload = function () {
                    VanillaToasts.create({
                        // title: 'Message Title',
                        text: '{{ $error[0] }}',
                        type: 'warning',
                        timeout: 10000

                    });
                }
            </script>

            @break
        @endforeach

    @endif
@yield('script')
<!-- End js file -->

</body>
</html>

