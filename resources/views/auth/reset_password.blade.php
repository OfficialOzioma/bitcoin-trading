@extends('auth.master',['menu'=>'dashboard'])
@section('title', isset($title) ? $title : '')

@section('content')
<div class="user-content-wrapper">
    <div>
        <div class="user-form">
            <div class="right">
                <div class="form-top">
                    <a class="auth-logo" href="javascript:">
                            <img src="{{show_image(1,'login_logo')}}" class="img-fluid" alt="">
                        </a>
                    <p>{{__('Password Reset')}}</p>
                </div>
                {{Form::open(['route' => 'resetPasswordSave', 'files' => true])}}
                    <div class="form-group">
                        <label>{{__('Verification code')}}</label>
                        <input id="token" autocomplete="off"  type="text" placeholder="{{__('')}}"   class="form-control" autocomplete="off" name="token" value="{{old('token')}}"  >
                    </div>
                    <div class="form-group">
                        <label>{{__('Email address')}}</label>
                        <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="{{__('')}}">
                    </div>
                    <div class="form-group">
                        <label>{{__('New Password')}}</label>
                        <input type="password" name="password" class="form-control" placeholder="{{__('')}}">
                    </div>
                    <div class="form-group">
                        <label>{{__('Confirm Password')}}</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="{{__('')}}">
                    </div>
                    <button type="submit" class="btn btn-primary nimmu-user-sibmit-button">{{__('Submit')}}</button>
                {{ Form::close() }}
                <div class="form-bottom text-center">
                    <p>{{__('Return to sign in')}} <a href="{{route('login')}}">{{__('Sign in')}}</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection
