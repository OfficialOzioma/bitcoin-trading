@extends('admin.master',['menu'=>'buy_coin','sub_menu'=>'give_coin'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Coin ')}}</li>
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
                <div class="profile-info-form">
                    <div class="card-body">
                        <form action="{{route('giveCoinToUserProcess')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mt-20">
                                    <div class="form-group">
                                        <label for="firstname">{{__('Coin Amount')}}</label>
                                        <input type="text" name="amount" class="form-control" id="firstname" placeholder="{{__('Coin Amount')}}" value="{{old('amount')}}" >
                                        <span class="text-danger"><strong>{{ $errors->first('amount') }}</strong></span>
                                    </div>

                                    <button class="button-primary theme-btn"> {{__('Send')}} </button>
                                </div>
                                <div class="col-md-6 mt-20">
                                    <div class="form-group">
                                        <label for="">{{__('Select User')}}</label>
                                    <ul class="user-list-checkbox">
                                        @if(isset($users[0]))
                                            <li>
{{--                                                <input name="" type="checkbox"> <span>{{__('Select All')}}</span>--}}
                                                <div class="form-group m-0">
                                                    <input name="" type="checkbox" id="select-all">
                                                    <label class="m-0" for="select-all">{{__('Select All')}}</label>
                                                </div>
                                            </li>
                                            @foreach($users as $user)
                                                <li>
{{--                                                    <input name="user_id[]" value="{{$user->id}}" type="checkbox">--}}
{{--                                                    <span>{{$user->first_name.' '.$user->last_name}}</span>--}}
                                                    <div class="form-group m-0">
                                                        <input name="user_id[]" value="{{$user->id}}" id="{{$user->id}}" type="checkbox">
                                                        <label class="m-0" for="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /User Management -->
@endsection

@section('script')

@endsection
