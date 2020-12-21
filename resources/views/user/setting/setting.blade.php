@extends('user.master',['menu'=>'setting', 'sub_menu'=>'setting'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-6 mb-xl-0 mb-4">
            <div class="card cp-user-custom-card cp-user-setting-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <div class="cp-user-title">
                            <h4>{{__('Google Authentication Settings')}}</h4>
                        </div>
                    </div>
                    <div class="cp-user-setting-card-inner">
                        <div class="cp-user-auth-icon">
                            <img src="{{asset('assets/user/images/gauth.svg')}}" class="img-fluid" alt="">
                        </div>
                        <div class="cp-user-content">
                            @if( empty(\Illuminate\Support\Facades\Auth::user()->google2fa_secret))
                                <a href="javascript:" data-toggle="modal" data-target="#exampleModal" class="btn cp-user-setupbtn">{{__('Set up')}}</a>
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form method="post" action="{{route('g2fSecretSave')}}">
                                            <input type="hidden" name="google2fa_secret" value="{{$google2fa_secret}}">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">{{__('Google Authentication')}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row align-items-center">
                                                        <div class="col-4">
                                                            <img src="{{$qrcode}}" class="img-fluid" alt="">
                                                        </div>
                                                        <div class="col-8">
                                                            <p>{{__('Open your Google Authenticator app, and scan Your secret code and enter the 6-digit code from the app into the input field')}}</p>
                                                            <input placeholder="{{__('Code')}}" type="text" class="form-control" name="code">
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                                                    <button type="submit" class="btn btn-primary">{{__('Verify')}}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="javascript:" data-toggle="modal" data-target="#exampleModalRemove" class="primary-btn">{{__('Remove google secret key')}}</a>
                                <div class="modal fade" id="exampleModalRemove" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form method="post" action="{{route('g2fSecretSave')}}">
                                            @csrf
                                            <input type="hidden" name="remove" value="1">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">{{__('Google Authentication')}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">

                                                        <div class="col-12">
                                                            <p>{{__('Open your Google Authenticator app and enter the 6-digit code from the app into the input field to remove the google secret key')}}</p>
                                                            <input placeholder="{{__('Code')}}" type="text" class="form-control" name="code">
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                                                    <button type="submit" class="btn btn-primary">{{__('Verify')}}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="cp-user-content">
                            <h5>{{__('Security')}}</h5>
                            <p>{{__('Please on this option to enable two factor authentication at log In.')}}</p>
                            <form method="post" action="{{route('googleLoginEnable')}}">
                                @csrf
                                <label class="switch">
                                    <input {{(\Illuminate\Support\Facades\Auth::user()->g2f_enabled == 1) ? 'checked' : ''}} onclick="$(this).closest('form').submit();" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card cp-user-custom-card cp-user-setting-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <div class="cp-user-title">
                            <h4>{{__('Preference Settings')}}</h4>
                        </div>
                    </div>
                    <div class="cp-user-setting-card-inner cp-user-setting-card-inner-preference">
                        <div class="cp-user-content">
                            <form method="post" action="{{route('savePreference')}}">
                                @csrf
                                <div class="form-group">
                                    <label>{{__('Language')}}</label>
                                    <div class="cp-user-preferance-setting">
                                        <select name="lang" class="form-control">
                                            @foreach(language() as $val)
                                                <option @if(Auth::user()->language == $val) selected @endif value="{{$val}}">{{langName($val)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
{{--                                <div class="form-group">--}}
{{--                                    <label>Currency</label>--}}
{{--                                    <div class="cp-user-preferance-setting">--}}
{{--                                        <select class="form-control">--}}
{{--                                            <option value="">USD</option>--}}
{{--                                            <option value="">BDT</option>--}}
{{--                                            <option value="">GBP</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="form-group">
                                    <button class="btn cp-user-setupbtn">{{__('Update')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')

@endsection
