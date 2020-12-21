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
    <meta property="og:image" content="{{asset('assets/user/images/logo.svg')}}">
    <meta property="og:site_name" content="Cpoket"/>
    <meta property="og:url" content="{{url()->current()}}"/>
    <meta property="og:type" content="{{allsetting('app_title')}}"/>
    <meta itemscope itemtype="{{ url()->current() }}/{{allsetting('app_title')}}" />
    <meta itemprop="headline" content="{{allsetting('app_title')}}" />
    <meta itemprop="image" content="{{asset('assets/user/images/logo.svg')}}" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('assets/user/css/bootstrap.min.css')}}">
    <!-- metismenu CSS -->
    <link rel="stylesheet" href="{{asset('assets/user/css/metisMenu.min.css')}}">
    {{--for toast message--}}
    <link href="{{asset('assets/toast/vanillatoasts.css')}}" rel="stylesheet" >
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{asset('assets/user/css/datatable/datatables.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/user/css/datatable/dataTables.bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/user/css/datatable/dataTables.jqueryui.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/user/css/datatable/jquery.dataTables.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/user/css/jquery.scrollbar.css')}}">
    <link rel="stylesheet" href="{{asset('assets/user/css/font-awesome.min.css')}}">


    {{--    dropify css  --}}
    <link rel="stylesheet" href="{{asset('assets/dropify/dropify.css')}}">

    <!-- Style CSS -->
    <link rel="stylesheet" href="{{asset('assets/user/style.css')}}">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{asset('assets/user/css/responsive.css')}}">

    @yield('style')
    <title>{{allsetting('app_title')}}::@yield('title')</title>
</head>

<body class="cp-user-body-bg">
@php $clubInfo = get_plan_info(Auth::id()) @endphp
<!-- top bar -->
<div class="cp-user-top-bar">
    <div class="cp-user-sidebar-toggler">
        <img src="{{asset('assets/user/images/menu.svg')}}" class="img-fluid d-lg-none d-block" alt="">
    </div>
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between">
            <div class="col-xl-2 col-lg-2 d-lg-block d-none">
                <div class="cp-user-logo">
                    <a href="{{route('userDashboard')}}">
                        <img src="{{show_image(Auth::id(),'logo')}}" class="img-fluid cp-user-logo-large" alt="">
                    </a>
                </div>
            </div>
            @php
                $notifications = \App\Model\Notification::where(['user_id'=> Auth::user()->id, 'status' => 0])->orderBy('id', 'desc')->get();
            @endphp
            @php
                $balance = getUserBalance(Auth::id());
                $activity = \App\Model\ActivityLog::where(['user_id' => Auth::id(), 'action' => USER_ACTIVITY_LOGIN])->first();
            @endphp
            <div class="col-xl-8 col-lg-7 col-md-9">
                <ul class="cp-user-top-bar-status-area">
                    <li class="cp-user-date-time">
                        <p class="cp-user-title">{{__('Date & Time')}}</p>
                        <div class="cp-user-content">
                            <p class="cp-user-last-visit"><span>{{__('Last Visit')}} :</span> {{date('F j, Y, g:i a', strtotime($activity->created_at))}}</p>
                            <p class="cp-user-today"><span>{{__('Today')}} :</span> {{date("F j, Y, g:i a")}}</p>
                        </div>
                    </li>
                    <li class="cp-user-available-balance">
                        <p class="cp-user-title">{{__('Available Balance')}}</p>
                        <div class="cp-user-content">
                            <p class="cp-user-btc"><span>{{number_format($balance['available_coin'],2)}}</span> {{allsetting('coin_name')}}</p>
                            <p class="cp-user-usd"><span>{{number_format($balance['available_used'],2)}}</span> {{__('USD')}}</p>
                        </div>
                    </li>
                    <li class="cp-user-available-balance">
                        <p class="cp-user-title">{{__('Blocked Coin')}}</p>
                        <div class="cp-user-content">
                            <p class="cp-user-btc"><span>{{number_format(get_blocked_coin(Auth::id()),2)}}</span> {{allsetting('coin_name')}}</p>
                        </div>
                    </li>
                    <li class="cp-user-pending-withdrawal">
                        <p class="cp-user-title">{{__('Membership Status')}}</p>
                        <div class="cp-user-content">
                            <p class="cp-user-btc">
                                @if(!empty($clubInfo['club_id']))
                                <span>
                                    <img src="{{ $clubInfo['plan_image'] }}" class="img-fluid" alt="">
                                </span>
                                {{ $clubInfo['plan_name'] }}
                                @else
                                    <span class="text-warning">{{__("No Membership yet")}}</span>
                                @endif
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-3">
                <div class="cp-user-top-bar-right">
                    <ul>
                        <li class="hm-notify">
                            <div class="btn-group dropdown">
                                <button type="button" class="btn notification-btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="notify-value hm-notify-number">@if(isset($notifications) && ($notifications ->count() > 0)) {{ $notifications->count() }} @else 0 @endif</span>
                                    <img src="{{ asset('assets/img/icons/notification.png') }}" class="img-fluid" alt="">
                                </button>
                                @if(!empty($notifications))
                                    <div class="dropdown-menu notification-list dropdown-menu-right">
                                        <div class="text-center p-2 border-bottom nt-title">{{__('New Notifications')}}</div>
                                        <ul class="scrollbar-inner">
                                            @foreach($notifications as $item)
                                                <li>
                                                    <a href="javascript:void(0);" data-toggle="modal" data-id="{{$item->id}}" data-target="#notificationShow" class="dropdown-item viewNotice">
                                                        <span class="small d-block">{{ date('d M y', strtotime($item->created_at)) }}</span>
                                                        {{ $item->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </li>
                        <li>
                            <div class="btn-group profile-dropdown">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="cp-user-avater">
                                        <span class="cp-user-img">
                                            <img src="{{show_image(Auth::user()->id,'user')}}" class="img-fluid" alt="">
                                        </span>
                                        <span class="cp-user-avater-info">
{{--                                            <span>{{__('Welcome Back!')}}</span>--}}
                                        </span>
                                    </span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <span class="big-user-thumb">
                                        <img src="{{show_image(Auth::user()->id,'user')}}" class="img-fluid" alt="">
                                    </span>
                                    <div class="user-name">
                                        <p>{{Auth::user()->first_name.' '.Auth::user()->last_name}}</p>
                                    </div>
                                    <button class="dropdown-item" type="button"><a href="{{route('userProfile')}}"><i class="fa fa-user-circle-o"></i> {{__('Profile')}}</a></button>
                                    <button class="dropdown-item" type="button"><a href="{{route('userSetting')}}"><i class="fa fa-cog"></i> {{__('My Settings')}}</a></button>
                                    <button class="dropdown-item" type="button"><a href="{{route('myPocket')}}"><i class="fa fa-credit-card"></i> {{__('My Pocket')}}</a></button>
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

<!-- Start sidebar -->
<div class="cp-user-sidebar">
    <div class="mb-sidebar-toggler">
        <img src="{{asset('assets/user/images/menu.svg')}}" class="img-fluid d-lg-none d-block" alt="">
    </div>
    <!-- logo -->
    <div class="cp-user-logo d-lg-none d-block my-4">
        <a href="i{{route('userDashboard')}}">
            <img src="{{show_image(Auth::user()->id,'logo')}}" class="img-fluid cp-user-logo-large" alt="">
        </a>
    </div>
    <!-- /logo -->
    

    <!-- sidebar menu -->
    <div class="cp-user-sidebar-menu scrollbar-inner">
        <nav>
            <ul id="metismenu">
                <li class="@if(isset($menu) && $menu == 'dashboard') cp-user-active-page @endif">
                    <a href="{{route('userDashboard')}}">
                            <span class="cp-user-icon">
                                <img src="{{asset('assets/user/images/sidebar-icons/dashboard.svg')}}" class="img-fluid cp-user-side-bar-icon" alt="">
                                <img src="{{asset('assets/user/images/sidebar-icons/hover/dashboard.svg')}}" class="img-fluid cp-user-side-bar-icon-hover" alt="">
                            </span>
                        <span class="cp-user-name">{{__('Dashboard')}}</span>
                    </a>
                </li>
                <li class=" @if(isset($menu) && $menu == 'coin') cp-user-active-page mm-active @endif">
                    <a class="arrow-icon" href="#" aria-expanded="true">
                        <span class="cp-user-icon">
                            <img src="{{asset('assets/user/images/sidebar-icons/coin.svg')}}" class="img-fluid cp-user-side-bar-icon" alt="">
                            <img src="{{asset('assets/user/images/sidebar-icons/hover/coin.svg')}}" class="img-fluid cp-user-side-bar-icon-hover" alt="">
                        </span>
                        <span class="cp-user-name">{{__('Buy Coin')}}</span>
                    </a>
                    <ul class=" @if(isset($menu) && $menu == 'coin') mm-show @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'buy_coin') cp-user-submenu-active @endif">
                            <a href="{{route('buyCoin')}}">{{__('Buy Coin')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'buy_coin_history') cp-user-submenu-active @endif">
                            <a href="{{route('buyCoinHistory')}}">{{__('Buy Coin History')}}</a>
                        </li>
                    </ul>
                </li>
                <li class="@if(isset($menu) && $menu == 'pocket') cp-user-active-page @endif">
                    <a href="{{route('myPocket')}}">
                            <span class="cp-user-icon">
                                <img src="{{asset('assets/user/images/sidebar-icons/Wallet.svg')}}" class="img-fluid cp-user-side-bar-icon" alt="">
                                <img src="{{asset('assets/user/images/sidebar-icons/hover/Wallet.svg')}}" class="img-fluid cp-user-side-bar-icon-hover" alt="">
                            </span>
                        <span class="cp-user-name">{{__('My Pocket')}}</span>
                    </a>
                </li>
                <li class="@if(isset($menu) && $menu == 'member') cp-user-active-page mm-active  @endif">
                    <a class="arrow-icon" href="#" aria-expanded="true">
                        <span class="cp-user-icon">
                            <img src="{{asset('assets/user/images/sidebar-icons/Membership.svg')}}" class="img-fluid cp-user-side-bar-icon" alt="">
                            <img src="{{asset('assets/user/images/sidebar-icons/hover/Membership-1.svg')}}" class="img-fluid cp-user-side-bar-icon-hover" alt="">
                        </span>
                        <span class="cp-user-name">{{__('Membership Club')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'member')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'coin_transfer') cp-user-submenu-active @endif"><a href="{{route('membershipClubPlan')}}">{{__('Transfer Coin')}}</a></li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'my_membership') cp-user-submenu-active @endif"><a href="{{route('myMembership')}}">{{__('My Membership')}}</a></li>
                    </ul>
                </li>
                <li class="@if(isset($menu) && $menu == 'profile') cp-user-active-page @endif">
                    <a href="{{route('userProfile')}}">
                            <span class="cp-user-icon">
                                <img src="{{asset('assets/user/images/sidebar-icons/user.svg')}}" class="img-fluid cp-user-side-bar-icon" alt="">
                                <img src="{{asset('assets/user/images/sidebar-icons/hover/user.svg')}}" class="img-fluid cp-user-side-bar-icon-hover" alt="">
                            </span>
                        <span class="cp-user-name">{{__('My Profile')}}</span>
                    </a>
                </li>
                <li class="@if(isset($menu) && $menu == 'referral') cp-user-active-page @endif">
                    <a href="{{route('myReferral')}}">
                            <span class="cp-user-icon">
                                <img src="{{asset('assets/user/images/sidebar-icons/referral.svg')}}" class="img-fluid cp-user-side-bar-icon" alt="">
                                <img src="{{asset('assets/user/images/sidebar-icons/hover/referral.svg')}}" class="img-fluid cp-user-side-bar-icon-hover" alt="">
                            </span>
                        <span class="cp-user-name">{{__('My Referral')}}</span>
                    </a>
                </li>
                <li class="@if(isset($menu) && $menu == 'setting') cp-user-active-page mm-active @endif">
                    <a class="arrow-icon" href="#" aria-expanded="true">
                        <span class="cp-user-icon">
                            <img src="{{asset('assets/user/images/sidebar-icons/settings.svg')}}" class="img-fluid cp-user-side-bar-icon" alt="">
                            <img src="{{asset('assets/user/images/sidebar-icons/hover/settings.svg')}}" class="img-fluid cp-user-side-bar-icon-hover" alt="">
                        </span>
                        <span class="cp-user-name">{{__('Settings')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'setting')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'setting') cp-user-submenu-active @endif">
                            <a href="{{route('userSetting')}}">{{__('My Settings')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'faq') cp-user-submenu-active @endif">
                            <a href="{{route('userFaq')}}">{{__('FAQ')}}</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div class="nav-bottom-img">
            <img src="{{asset('assets/user/images/sidebar-coin-img.svg')}}" alt="">
        </div>
    </div><!-- /sidebar menu -->

</div>
<!-- End sidebar -->

{{--notification modal--}}

<div class="modal fade" id="notificationShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content dark-modal">
            <div class="modal-header align-items-center">
                <h5 class="modal-title" id="exampleModalCenterTitle">{{__('New Notification')}}  </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="hm-form">
                    <div class="row">
                        <div class="col-12">
                            <h6 id="n_title"></h6>
                            <p id="n_date"></p>
                            <p id="n_notice"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main wrapper -->
<div class="cp-user-main-wrapper">
    <div class="container-fluid">
        <div style="color: #155724;background-color: #d4edda;border-color: #c3e6cb;"
             class="alert-float alert alert-success  d-none" id="web_socket_notification">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
            <div class="web_socket_notification_html"></div>
        </div>
        @yield('content')
    </div>
</div>
<!-- /main wrapper -->

<!-- js file start -->
<!-- <script src="{{asset('js/app.js')}}"></script> -->
<!-- JavaScript -->
<script src="{{asset('assets/user/js/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="{{asset('assets/user/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/user/js/metisMenu.min.js')}}"></script>
{{--toast message--}}
<script src="{{asset('assets/toast/vanillatoasts.js')}}"></script>
<!-- Datatable -->
<script src="{{asset('assets/user/js/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/user/js/datatable/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('assets/user/js/datatable/dataTables.jqueryui.min.js')}}"></script>
<script src="{{asset('assets/user/js/datatable/jquery.dataTables.min.js')}}"></script>

<script src="{{asset('assets/user/js/jquery.scrollbar.min.js')}}"></script>


{{--dropify--}}
<script src="{{asset('assets/dropify/dropify.js')}}"></script>
<script src="{{asset('assets/dropify/form-file-uploads.js')}}"></script>

<script src="{{asset('assets/user/js/main.js')}}"></script>
<script>

    Pusher.logToConsole = true;

    Echo.channel('usernotification_' + '{{Auth::id()}}')
        .listen('.receive_notification', (data) => {
            console.log(data);
            if (data.success == true) {
                let message = data.message
                $('#web_socket_notification').removeClass('d-none');
                $('#web_socket_notification').html(message);
            }
        });
</script>
<script>
    $(document).ready(function() {
        $('.cp-user-custom-table').DataTable({
            responsive: true,
            paging: false,
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
    $(document).on('click', '.viewNotice', function (e) {
        var id = $(this).data('id');
        // alert(id);
        $.ajax({
            type: "GET",
            url: '{{ route('showNotification') }}',
            data: {
                '_token': '{{ csrf_token() }}',
                'id': id,
            },
            success: function (data) {
                $("#n_title").text(data['data']['title']);
                $("#n_date").text(data['data']['date']);
                $("#n_notice").text(data['data']['notice']);
            }
        });
    });
</script>
<!-- End js file -->
@yield('script')
</body>
</html>

