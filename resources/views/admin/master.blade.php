<!DOCTYPE HTML>
<html class="no-js" lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="The Highly Secured Bitcoin Wallet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{allsetting('app_title')}}"/>
    <meta property="og:image" content="{{asset('assets/admin/images/logo.svg')}}">
    <meta property="og:site_name" content="Cpoket"/>
    <meta property="og:url" content="{{url()->current()}}"/>
{{--    <meta property="og:type" content="{{allsetting('app_title')}}"/>--}}
{{--    <meta itemscope itemtype="{{ url()->current() }}/{{allsetting('app_title')}}" />--}}
{{--    <meta itemprop="headline" content="{{allsetting('app_title')}}" />--}}
    <meta itemprop="image" content="{{asset('assets/admin/images/logo.svg')}}" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap.min.css')}}">
    <!-- metismenu CSS -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/metisMenu.min.css')}}">
    <!-- fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/font-awesome.min.css')}}">
    {{--for toast message--}}
    <link href="{{asset('assets/toast/vanillatoasts.css')}}" rel="stylesheet" >
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{asset('assets/user/css/datatable/datatables.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/user/css/datatable/dataTables.bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/user/css/datatable/dataTables.jqueryui.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/user/css/datatable/dataTables.responsive.css')}}">
    <link rel="stylesheet" href="{{asset('assets/user/css/datatable/jquery.dataTables.min.css')}}">

    {{--    dropify css  --}}
    <link rel="stylesheet" href="{{asset('assets/dropify/dropify.css')}}">
    {{-- summernote --}}
    <link rel="stylesheet" href="{{asset('assets/summernote/summernote.min.css')}}">
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{asset('assets/admin/style.css')}}">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/responsive.css')}}">
    @yield('style')
    <title>@yield('title')</title>
</head>

<body class="body-bg">
<!-- Start sidebar -->
<div class="sidebar">
    <!-- logo -->
    <div class="logo">
        <a href="{{route('adminDashboard')}}">
            <img src="{{show_image(Auth::user()->id,'logo')}}" class="img-fluid" alt="">
        </a>
    </div><!-- /logo -->

    <!-- sidebar menu -->
    <div class="sidebar-menu">
        <nav>
            <ul id="metismenu">
                <li class="@if(isset($menu) && $menu == 'dashboard') active-page @endif">
                    <a href="{{route('adminDashboard')}}">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/dashboard.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Dashboard')}}</span>
                    </a>
                </li>
                <li class="@if(isset($menu) && $menu == 'users') active-page @endif">
                    <a href="#" aria-expanded="true">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/user.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('User Management')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'users')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'user') submenu-active @endif">
                            <a href="{{route('adminUsers')}}">{{__('User')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'pending_id') submenu-active @endif">
                            <a href="{{route('adminUserIdVerificationPending')}}">{{__('Pending ID Verification')}}</a>
                        </li>
                    </ul>
                </li>
                <li class="@if(isset($menu) && $menu == 'pocket') active-page @endif">
                    <a href="{{route('adminWalletList')}}">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/wallet.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Pocket')}}</span>
                    </a>
                </li>
                <li class="@if(isset($menu) && $menu == 'transaction') active-page @endif">
                    <a href="#" aria-expanded="true">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/Transaction-1.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Transaction History')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'transaction')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'transaction_all') submenu-active @endif">
                            <a href="{{route('adminTransactionHistory')}}">{{__('All Transaction')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'transaction_withdrawal') submenu-active @endif">
                            <a href="{{route('adminPendingWithdrawal')}}">{{__('Pending Withdrawal')}}</a>
                        </li>
                    </ul>
                </li>
                <li class="@if(isset($menu) && $menu == 'buy_coin') active-page @endif">
                    <a href="#" aria-expanded="true">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/coin-order-list.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Buy Coin')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'buy_coin')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'buy_coin') submenu-active @endif">
                            <a href="{{route('adminPendingCoinOrder')}}">{{__('Buy coin order list')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'give_coin') submenu-active @endif">
                            <a href="{{route('giveCoinToUser')}}">{{__('Give Coin')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'give_coin_history') submenu-active @endif">
                            <a href="{{route('giveCoinHistory')}}">{{__('Give Coin History')}}</a>
                        </li>
                    </ul>
                </li>

                <li class="@if(isset($menu) && $menu == 'profile') active-page @endif">
                    <a href="{{ route('adminProfile') }}">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/profile.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Profile')}}</span>
                    </a>
                </li>
                <li class="@if(isset($menu) && $menu == 'club') active-page @endif">
                    <a href="#" aria-expanded="true">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/Membership.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Membership Club')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'club')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'member_list') submenu-active @endif">
                            <a href="{{route('membershipList')}}">{{__('Member List')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'plan_list') submenu-active @endif">
                            <a href="{{ route('planList') }}">{{__('Plan List')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'transaction_history') submenu-active @endif">
                            <a href="{{ route('coinTransactionHistory') }}">{{__('Transaction History')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'bonus') submenu-active @endif">
                            <a href="{{ route('clubBonusDistribution') }}">{{__('Bonus Distribution')}}</a>
                        </li>
                    </ul>
                </li>
                <li class="@if(isset($menu) && $menu == 'setting') active-page @endif">
                    <a href="#" aria-expanded="true">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/settings.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Settings')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'setting')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'general') submenu-active @endif">
                            <a href="{{route('adminSettings')}}">{{__('General Settings')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'payment-method') submenu-active @endif">
                            <a href="{{route('adminPaymentSetting')}}">{{__('Payment Method')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'bank') submenu-active @endif">
                            <a href="{{ route('bankList') }}">{{__('Bank Management')}}</a>
                        </li>
                    </ul>
                </li>
                <li class="@if(isset($menu) && $menu == 'notification') active-page @endif">
                    <a href="#" aria-expanded="true">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/Notification.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Notification')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'notification')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'notify') submenu-active @endif">
                            <a href="{{route('sendNotification')}}">{{__('Notification')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'email') submenu-active @endif">
                            <a href="{{route('sendEmail')}}">{{__('Bulk Email')}}</a>
                        </li>
                    </ul>
                </li>
                <li class="@if(isset($menu) && $menu == 'faq') active-page @endif">
                    <a href="{{ route('adminFaqList') }}">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/FAQ.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('FAQs')}}</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div><!-- /sidebar menu -->

</div>
<!-- End sidebar -->
<!-- top bar -->
<div class="top-bar">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between">
            <div class="col-xl-1 col-md-2 col-3 top-bar-logo top-bar-logo-hide">
                <div class="logo">
                    <a href="{{route('adminDashboard')}}"><img src="{{show_image(Auth::user()->id,'logo')}}" class="img-fluid logo-large" alt=""></a>
                    <a href="{{route('adminDashboard')}}"><img src="{{show_image(Auth::user()->id,'logo')}}" class="img-fluid logo-small" alt=""></a>
                </div>
            </div>
            <div class="col-xl-1 col-md-2 col-3">
                <div class="menu-bars">
                    <img src="{{asset('assets/admin/images/sidebar-icons/menu.svg')}}" class="img-fluid" alt="">
                </div>
            </div>
            <div class="col-xl-10 col-md-8 col-6">
                <div class="top-bar-right">
                    <ul>
                        <li>
{{--                            <div class="btn-group">--}}
{{--                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                                    <span class="avatar">--}}
{{--                                        <img src="{{show_image(Auth::user()->id,'user')}}" class="img-fluid" alt="">--}}
{{--                                        <span class="name">{{Auth::user()->first_name.' '.Auth::user()->last_name}}</span>--}}
{{--                                    </span>--}}
{{--                                </button>--}}
{{--                                <div class="dropdown-menu dropdown-menu-right">--}}
{{--                                    <ul class="user-profile">--}}
{{--                                        <li><a href="{{route('adminProfile')}}">{{__('Profile')}}</a></li>--}}
{{--                                        <li><a href="{{route('logOut')}}"><i class="fa fa-sign-out"></i>{{__('Logout')}}</a></li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="btn-group profile-dropdown">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="cp-user-avater">
                                        <span class="cp-user-img">
                                            <img src="{{show_image(Auth::user()->id,'user')}}" class="img-fluid" alt="">
                                        </span>
                                        <span class="name">{{Auth::user()->first_name.' '.Auth::user()->last_name}}</span>
                                    </span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <span class="big-user-thumb">
                                        <img src="{{show_image(Auth::user()->id,'user')}}" class="img-fluid" alt="">
                                    </span>
                                    <div class="user-name">
                                        <p>{{Auth::user()->first_name.' '.Auth::user()->last_name}}</p>
                                    </div>
                                    <button class="dropdown-item" type="button"><a href="{{route('adminProfile')}}"><i class="fa fa-user-circle-o"></i> {{__('Profile')}}</a></button>
                                    <button class="dropdown-item" type="button"><a href="{{route('logOut')}}"><i class="fa fa-sign-out"></i> {{__('Logout')}}</a></button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /top bar -->

<!-- main wrapper -->
<div class="main-wrapper">
    <div class="container-fluid">
        @yield('content')
    </div>
</div>
<!-- /main wrapper -->

<!-- js file start -->

<!-- JavaScript -->
<script src="{{asset('assets/admin/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/admin/js/popper.min.js')}}"></script>
<script src="{{asset('assets/admin/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/admin/js/metisMenu.min.js')}}"></script>

{{--<script src="https://www.amcharts.com/lib/4/core.js"></script>--}}
{{--<script src="https://www.amcharts.com/lib/4/charts.js"></script>--}}
{{--<script src="https://www.amcharts.com/lib/4/themes/dark.js"></script>--}}
{{--<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>--}}

<script src="{{asset('assets/admin/js/apexcharts.min.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.circlechart.js')}}"></script>

{{--toast message--}}
<script src="{{asset('assets/toast/vanillatoasts.js')}}"></script>
<!-- Datatable -->
<script src="{{asset('assets/user/js/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/user/js/datatable/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('assets/user/js/datatable/dataTables.jqueryui.min.js')}}"></script>
<script src="{{asset('assets/user/js/datatable/dataTables.responsive.js')}}"></script>
<script src="{{asset('assets/user/js/datatable/jquery.dataTables.min.js')}}"></script>

{{-- summernote --}}
<script src="{{asset('assets/summernote/summernote.min.js')}}"></script>

{{--dropify--}}
<script src="{{asset('assets/dropify/dropify.js')}}"></script>
<script src="{{asset('assets/dropify/form-file-uploads.js')}}"></script>

<script src="{{asset('assets/admin/js/main.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#table').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering:  true,
            select: false,
            bDestroy: true
        });


    });
</script>

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
                    // title: 'Message Title2',
                    text: '{{ $error[0] }}',
                    type: 'warning',
                    timeout: 10000

                });
            }
        </script>

        @break
    @endforeach

@endif
<script>
    /* Add here all your JS customizations */
    $('.number-only').keypress(function (e) {
        alert(11);
        var regex = /^[+0-9+.\b]+$/;
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        e.preventDefault();
        return false;
    });
    $('.no-regx').keypress(function (e) {
        var regex = /^[a-zA-Z+0-9+\b]+$/;
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        e.preventDefault();
        return false;
    });
</script>
@yield('script')
<!-- End js file -->
</body>
</html>

