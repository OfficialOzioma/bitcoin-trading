@extends('admin.master',['menu'=>'club', 'sub_menu'=>'plan'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Plan Management')}}</li>
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
                <div class="profile-info-form p-4">
                    <form action="{{route('planSave')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mt-20">
                                <div class="form-group">
                                    <label for="firstname">{{__('Plan Name')}}</label>
                                    <input type="text" name="plan_name" class="form-control" id="firstname" placeholder="{{__('Plan name')}}"
                                           @if(isset($item)) value="{{$item->plan_name}}" @else value="{{old('plan_name')}}" @endif>
                                    <span class="text-danger"><strong>{{ $errors->first('plan_name') }}</strong></span>
                                </div>
                            </div>
                            <div class="col-md-6 mt-20">
                                <div class="form-group">
                                    <label for="lastname">{{__('Plan Duration (days)')}}</label>
                                    <input name="duration" type="text" class="form-control" id="lastname"  placeholder="{{__('Duration in days')}}"
                                           @if(isset($item)) value="{{$item->duration}}" @else value="{{old('duration')}}" @endif>
                                    <span class="text-danger"><strong>{{ $errors->first('duration') }}</strong></span>
                                </div>
                            </div>
                            <div class="col-md-6 mt-20">
                                <div class="form-group">
                                    <label for="email">{{__('Plan Minimum Amount')}}</label>
                                    <input type="text" name="amount" class="form-control" id="email" placeholder="{{__('Amount')}}"
                                           @if(isset($item)) value="{{$item->amount}}" @else value="{{old('amount')}}" @endif>
                                    <span class="text-danger"><strong>{{ $errors->first('amount') }}</strong></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12  mt-20">
                                <div class="form-group">
                                    <label for="#">{{__('Withdrawal Fees Method')}}</label>
                                    <select class="form-control" name="bonus_type">
                                        @foreach(sendFeesType() as $key_sft=>$value_sft)
                                            <option value="{{$key_sft}}" @if($item->bonus_type == $key_sft) selected @endif >{{$value_sft}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-20">
                                <div class="form-group">
                                    <label for="email">{{__('Plan Bonus')}}</label>
                                    <input type="text" name="bonus" class="form-control" id="email" placeholder="{{__('Bonus')}}"
                                           @if(isset($item)) value="{{$item->bonus}}" @else value="{{old('bonus')}}" @endif>
                                    <span class="text-danger"><strong>{{ $errors->first('bonus') }}</strong></span>
                                </div>
                            </div>
                            <div class="col-md-6 mt-20">
                                <div class="form-group">
                                    <label>{{__('Activation Status')}}</label>
                                    <select name="status" class="form-control wide" >
                                        @foreach(status() as $key => $value)
                                            <option @if(isset($item) && ($item->status == $key)) selected
                                                    @elseif((old('status') != null) && (old('status') == $key)) @endif value="{{ $key }}">{{$value}}</option>
                                            <span class="text-danger"><strong>{{ $errors->first('status') }}</strong></span>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-20">
                                <div class="form-group">
                                    <label>{{__('Short Note')}}</label>
                                    <textarea name="description" class="form-control textarea">@if(isset($item)){{$item->description}}@else{{old('description')}}@endif</textarea>
                                </div>
                            </div>
{{--                            <div class="col-lg-4 mt-20">--}}
{{--                                <div class="single-uplode">--}}
{{--                                    <div class="uplode-catagory">--}}
{{--                                        <span>{{__('Plan Batch Image')}}</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group buy_coin_address_input ">--}}
{{--                                        <div id="file-upload" class="section-p">--}}
{{--                                            <input type="file" name="image" id="file" ref="file" class="dropify" @if(isset($item) && (!empty($item->image)))  data-default-file="{{asset(path_image().$item->image)}}" @endif />--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="col-md-12">
                                @if(isset($item))
                                    <input type="hidden" name="edit_id" value="{{$item->id}}">
                                @endif
                                <button class="btn ">@if(isset($item)) {{__('Update')}} @else {{__('Save')}} @endif</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /User Management -->

@endsection

@section('script')
    <script>

    </script>
@endsection
