@extends('admin.master',['menu'=>'setting', 'sub_menu'=>'general'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Settings')}}</li>
                    <li class="active-item">{{ $title }}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management">
        <div class="row">
            <div class="col-12">
                <ul class="nav user-management-nav mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="@if(isset($tab) && $tab=='general') active @endif nav-link " id="pills-user-tab"
                           data-toggle="pill" data-controls="general" href="#general" role="tab"
                           aria-controls="pills-user" aria-selected="true">
                            <span>{{__('General Settings')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="@if(isset($tab) && $tab=='email') active @endif nav-link " id="pills-add-user-tab"
                           data-toggle="pill" data-controls="email" href="#email" role="tab"
                           aria-controls="pills-add-user" aria-selected="true">
                            <span>{{__('Email Settings')}} </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="@if(isset($tab) && $tab=='sms') active @endif nav-link " id="pills-sms-tab"
                           data-toggle="pill" data-controls="sms" href="#sms" role="tab" aria-controls="pills-sms"
                           aria-selected="true">
                            <span>{{__('Twillo Settings')}} </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="@if(isset($tab) && $tab=='withdraw') active @endif nav-link "
                           id="pills-deleted-user-tab" data-toggle="pill" data-controls="withdraw" href="#withdraw"
                           role="tab" aria-controls="pills-deleted-user" aria-selected="true">
                            <span>{{__('Withdrawal Settings')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="@if(isset($tab) && $tab=='referral') active @endif nav-link "
                           id="pills-suspended-user-tab" data-toggle="pill" data-controls="referral" href="#referral"
                           role="tab" aria-controls="pills-suspended-user" aria-selected="true">
                            <span>{{__('Referral Settings')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="@if(isset($tab) && $tab=='payment') active @endif nav-link " id="pills-email-tab"
                           data-toggle="pill" data-controls="payment" href="#payment" role="tab"
                           aria-controls="pills-email" aria-selected="true">
                            <span>{{__('Payment Settings')}}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane show @if(isset($tab) && $tab=='general')  active @endif" id="general"
                         role="tabpanel" aria-labelledby="pills-user-tab">
                        <div class="header-bar">
                            <div class="table-title">
                                <h3>{{__('General Settings')}}</h3>
                            </div>
                        </div>
                        <div class="profile-info-form">
                            <form action="{{route('adminCommonSettings')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-12 mt-20">
                                        <div class="form-group">
                                            <label>{{__('Language')}}</label>
                                            <div class="cp-select-area">
                                                <select name="lang" class="form-control">
                                                    @foreach(language() as $val)
                                                        <option
                                                            @if(isset($settings['lang']) && $settings['lang']==$val) selected
                                                            @endif value="{{$val}}">{{langName($val)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Coin Name')}}</label>
                                            <input class="form-control" type="text" name="coin_name"
                                                   placeholder="{{__('Coin Name')}}" value="{{$settings['coin_name']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Company Name')}}</label>
                                            <input class="form-control" type="text" name="company_name"
                                                   placeholder="{{__('Company Name')}}"
                                                   value="{{$settings['app_title']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Coin Price (in USD)')}}</label>
                                            <input class="form-control number_only" type="text" name="coin_price"
                                                   placeholder="{{__('coin price')}}"
                                                   value="{{isset($settings['coin_price']) ? $settings['coin_price'] : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Company USD Account no.')}}</label>
                                            <input class="form-control" type="text" name="admin_usdt_account_no"
                                                   placeholder="{{__('Company usd account mo.')}}"
                                                   value="{{isset($settings['admin_usdt_account_no']) ? $settings['admin_usdt_account_no'] : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Coin Payment Base Coin Type')}}</label>
                                            <input class="form-control" type="text" name="base_coin_type"
                                                   placeholder="{{__('Coin Type eg. BTC')}}"
                                                   value="{{isset($settings['base_coin_type']) ? $settings['base_coin_type'] : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Copyright Text')}}</label>
                                            <input class="form-control" type="text" name="copyright_text"
                                                   placeholder="{{__('Copyright Text')}}"
                                                   value="{{$settings['copyright_text']}}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-12 mt-20">
                                        <div class="form-group">
                                            <label
                                                for="#">{{__('Number of confirmation for Notifier deposit')}} </label>
                                            <input class="form-control number_only" type="text"
                                                   name="number_of_confirmation" placeholder=""
                                                   value="{{$settings['number_of_confirmation']}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="uplode-img-list">
                                    <div class="row">
                                        <div class="col-lg-4 mt-20">
                                            <div class="single-uplode">
                                                <div class="uplode-catagory">
                                                    <span>{{__('Logo')}}</span>
                                                </div>
                                                <div class="form-group buy_coin_address_input ">
                                                    <div id="file-upload" class="section-p">
                                                        <input type="file" placeholder="0.00" name="logo" value=""
                                                               id="file" ref="file" class="dropify"
                                                               @if(isset($settings['logo']) && (!empty($settings['logo'])))  data-default-file="{{asset(path_image().$settings['logo'])}}" @endif />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mt-20">
                                            <div class="single-uplode">
                                                <div class="uplode-catagory">
                                                    <span>{{__('Login Logo')}}</span>
                                                </div>
                                                <div class="form-group buy_coin_address_input ">
                                                    <div id="file-upload" class="section-p">
                                                        <input type="file" placeholder="0.00" name="login_logo" value=""
                                                               id="file" ref="file" class="dropify"
                                                               @if(isset($settings['login_logo']) && (!empty($settings['login_logo'])))  data-default-file="{{asset(path_image().$settings['login_logo'])}}" @endif />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mt-20">
                                            <div class="single-uplode">
                                                <div class="uplode-catagory">
                                                    <span>{{__('Favicon')}}</span>
                                                </div>
                                                <div class="form-group buy_coin_address_input ">
                                                    <div id="file-upload" class="section-p">
                                                        <input type="file" placeholder="0.00" name="favicon" value=""
                                                               id="file" ref="file" class="dropify"
                                                               @if(isset($settings['favicon']) && (!empty($settings['favicon'])))  data-default-file="{{asset(path_image().$settings['favicon'])}}" @endif />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @if(isset($itech))
                                        <input type="hidden" name="itech" value="{{$itech}}">
                                    @endif
                                    <div class="col-lg-2 col-12 mt-20">
                                        <button class="button-primary theme-btn">{{__('Update')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane @if(isset($tab) && $tab=='email') show active @endif" id="email"
                         role="tabpanel" aria-labelledby="pills-add-user-tab">
                        <div class="header-bar">
                            <div class="table-title">
                                <h3>{{__('Email Setup')}}</h3>
                            </div>
                        </div>
                        <div class="profile-info-form">
                            <form action="{{route('adminSaveEmailSettings')}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Email Host')}}</label>
                                            <input class="form-control" type="text" name="mail_host"
                                                   placeholder="{{__('Host')}}" value="{{$settings['mail_host']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Email Port')}}</label>
                                            <input class="form-control" type="text" name="mail_port"
                                                   placeholder="{{__('Port')}}" value="{{$settings['mail_port']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Email Username')}}</label>
                                            <input class="form-control" type="text" name="mail_username"
                                                   placeholder="{{__('Username')}}"
                                                   value="{{isset($settings['mail_username']) ? $settings['mail_username'] : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Email Password')}}</label>
                                            <input class="form-control" type="password" name="mail_password"
                                                   placeholder="{{__('Password')}}"
                                                   value="{{$settings['mail_password']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Mail Encryption')}}</label>
                                            <input class="form-control" type="text" name="mail_encryption"
                                                   placeholder="{{__('Encryption')}}"
                                                   value="{{isset($settings['mail_encryption']) ? $settings['mail_encryption'] : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Mail Form Address')}}</label>
                                            <input class="form-control" type="text" name="mail_from_address"
                                                   placeholder="{{__('Mail from address')}}"
                                                   value="{{isset($settings['mail_from_address']) ? $settings['mail_from_address'] : ''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-12 mt-20">
                                        <button type="submit" class="button-primary theme-btn">{{__('Update')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane @if(isset($tab) && $tab=='sms') show active @endif" id="sms" role="tabpanel"
                         aria-labelledby="pills-sms-tab">
                        <div class="header-bar">
                            <div class="table-title">
                                <h3>{{__('Twillo Setup')}}</h3>
                            </div>
                        </div>
                        <div class="profile-info-form">
                            <form action="{{route('adminSaveSmsSettings')}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Twillo Secret Key')}}</label>
                                            <input class="form-control" type="text" name="twillo_secret_key"
                                                   placeholder="{{__('Secret Key')}}"
                                                   value="{{$settings['twillo_secret_key']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Auth Token')}}</label>
                                            <input class="form-control" type="text" name="twillo_auth_token"
                                                   placeholder="{{__('Auth Token')}}"
                                                   value="{{$settings['twillo_auth_token']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Twillo Number')}}</label>
                                            <input class="form-control" type="text" name="twillo_number"
                                                   placeholder="{{__('Number')}}"
                                                   value="{{isset($settings['twillo_number']) ? $settings['twillo_number'] : ''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-12 mt-20">
                                        <button type="submit" class="button-primary theme-btn">{{__('Update')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane @if(isset($tab) && $tab=='referral') show active @endif" id="referral"
                         role="tabpanel" aria-labelledby="pills-suspended-user-tab">
                        <div class="header-bar">
                            <div class="table-title">
                                <h3>{{__('Referral Settings')}}</h3>
                            </div>
                        </div>
                        <div class="profile-info-form">
                            <form method="post" action="{{route('adminReferralFeesSettings')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12 col-12  mt-20">
                                        <div class="form-group">
                                            <label
                                                class="">{{__('Maximum Affiliation Level : ') }} {{ settings('max_affiliation_level') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label class="">{{__('Referral reward for signup') }}</label>
                                            <input type="text" class="form-control number_only"
                                                   name="referral_signup_reward" min="0"
                                                   value="{{ old('referral_signup_reward', isset($settings['referral_signup_reward']) ? $settings['referral_signup_reward'] : 100) }}">
                                        </div>
                                    </div>
                                    @for($i = 1; $i <=3 ; $i ++)
                                        <div class="col-lg-6 col-12  mt-20">
                                            <div class="form-group">
                                                <label for="#">{{ __('Level') }} {{$i}} (%)</label>
                                                @php( $slug_name = 'fees_level'.$i)
                                                <p class="fees-wrap">
                                                    <input type="text" class="number_only form-control"
                                                           name="{{$slug_name}}"
                                                           value="{{ old($slug_name, isset($settings[$slug_name]) ? $settings[$slug_name] : 0) }}">
                                                    <span>%</span>
                                                </p>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-12 mt-20">
                                        <button class="button-primary theme-btn">{{__('Update')}}</button>
                                    </div>
                                </div>
                            </form>
                            <!-- Fees Settings end-->
                        </div>
                    </div>
                    <div class="tab-pane @if(isset($tab) && $tab=='withdraw') show active @endif" id="withdraw"
                         role="tabpanel" aria-labelledby="pills-deleted-user-tab">
                        <div class="header-bar">
                            <div class="table-title">
                                <h3>{{__('Withdrawal Settings')}}</h3>
                            </div>
                        </div>
                        <div class="profile-info-form">
                            <form method="post" action="{{route('adminWithdrawalSettings')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-12 mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Minimum Withdrawal Amount')}}</label>
                                            <input type="text" class="form-control" name="minimum_withdrawal_amount"
                                                   placeholder="{{__('Minimum withdrawal amount')}}"
                                                   value="{{$settings['minimum_withdrawal_amount']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12 mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Maximum Withdrawal Amount')}}</label>
                                            <input type="text" class="form-control" name="maximum_withdrawal_amount"
                                                   placeholder="{{__('Maximum withdrawal amount')}}"
                                                   value="{{$settings['maximum_withdrawal_amount']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12 mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Max. Send Limit (per day)')}}</label>
                                            <input type="text" class="form-control" name="max_send_limit"
                                                   placeholder="{{__('Send Limit')}}"
                                                   value="{{$settings['max_send_limit']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Withdrawal Fees Method')}}</label>
                                            <div class="cp-select-area">
                                                <select class="form-control" name="send_fees_type">
                                                    @foreach(sendFeesType() as $key_sft=>$value_sft)
                                                        <option value="{{$key_sft}}"
                                                                @if($settings['send_fees_type']==$key_sft) selected @endif >{{$value_sft}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12 mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Withdrawal Fixed Fees')}}</label>
                                            <input class="form-control" type="text" name="send_fees_fixed"
                                                   placeholder="{{__('Send Coin Fixed Fees')}}"
                                                   value="{{$settings['send_fees_fixed']}}">
                                        </div>
                                    </div>
                                    <div class="col-6 mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Withdrawal Fees Percent')}}</label>
                                            <p class="fees-wrap">
                                                <input class="form-control" type="text" name="send_fees_percentage"
                                                       placeholder="{{__('Currency Deposit Fees in Percent')}}"
                                                       value="{{$settings['send_fees_percentage']}}">
                                                <span>%</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-12 mt-20">
                                        <button type="submit" class="btn">{{__('Update')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane @if(isset($tab) && $tab=='payment') show active @endif" id="payment"
                         role="tabpanel" aria-labelledby="pills-email-tab">
                        <div class="header-bar">
                            <div class="table-title">
                                <h3>{{__('Coin Payment Details')}}</h3>
                            </div>
                        </div>
                        <div class="profile-info-form">
                            <form action="{{route('adminSavePaymentSettings')}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-12 mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('COIN PAYMENT PUBLIC KEY')}}</label>
                                            <input class="form-control" type="text" name="COIN_PAYMENT_PUBLIC_KEY"
                                                   autocomplete="off" placeholder=""
                                                   value="{{settings('COIN_PAYMENT_PUBLIC_KEY')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12 mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('COIN PAYMENT PRIVATE KEY')}}</label>
                                            <input class="form-control" type="text" name="COIN_PAYMENT_PRIVATE_KEY"
                                                   autocomplete="off" placeholder=""
                                                   value="{{settings('COIN_PAYMENT_PRIVATE_KEY')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="form-group">
                                            <label for="#">{{__('Coin Payment Base Coin Type')}}</label>
                                            <input class="form-control" type="text" name="base_coin_type"
                                                   placeholder="{{__('Coin Type eg. BTC')}}"
                                                   value="{{isset($settings['base_coin_type']) ? $settings['base_coin_type'] : ''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-12 mt-20">
                                        <button type="submit" class="button-primary theme-btn">{{__('Update')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /User Management -->

@endsection

@section('script')
    <script>
        $('.nav-link').on('click', function () {
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
            var str = '#' + $(this).data('controls');
            $('.tab-pane').removeClass('show active');
            $(str).addClass('show active');
        });
    </script>
@endsection
