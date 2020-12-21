@extends('admin.master',['menu'=>'notification', 'sub_menu'=>'email'])
@section('title', isset($title) ? $title : '')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/summernote/summernote.css')}}">
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-md-6">
                <ul>
                    <li>{{__('Notification Management')}}</li>
                    <li class="active-item">{{ $title }}</li>
                </ul>
            </div>
            <div class="col-md-6">
                <div class="pull-right">
                    <a class="btn btn-primary clear-record ico-add-user-btn mb-md-0 mb-3 text-right"
                       href="{{ route('clearEmailRecord') }}">
                        {{ __('Clear Record') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management management-card-area">
        <div class="row">
            <div class="col-12">
                <div class="profile-info-form">
                    <form action="{{route('sendEmailProcess')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mt-20">
                                <div class="card mail-card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="firstname">{{__('Header Text')}}</label>
                                            <textarea name="email_headers" id="text-header"
                                                      placeholder="{{__('Email header text')}}"
                                                      class="textarea form-control">{{old('email_headers')}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-20">
                                <div class="card mail-card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="firstname">{{__('Subject')}}</label>
                                            <input type="text" class="form-control" id="exampleInputEmail1"
                                                   value="{{old('subject')}}" name="subject"
                                                   placeholder="{{__('Subject')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-20">
                                <div class="card mail-card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="firstname">{{__('Message Text')}}</label>
                                            <textarea name="email_message" id="text-message"
                                                      placeholder="{{__('Email message text')}}"
                                                      class="textarea form-control">{{old('email_message')}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-20">
                                <div class="card mail-card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="firstname">{{__('Message type')}}</label>
                                            <input type="text" class="form-control" id="exampleInputEmail1"
                                                   value="{{old('email_type')}}" name="email_type"
                                                   placeholder="{{__('Email type')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-20">
                                <div class="card mail-card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="firstname">{{__('Footer Text')}}</label>
                                            <textarea name="footers" id="footer"
                                                      placeholder="{{__('Email footer text')}}"
                                                      class="textarea form-control">{{old('footers')}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card mail-card">
                                    <div class="card-body">
                                        <button class="btn email-submit-btn"> {{__('Submit')}} </button>
                                    </div>
                                </div>
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
    <script src="{{asset('assets/summernote/summernote.js')}}"></script>
    <script>
        $('#text-header').summernote({height: 400});
        $('#text-message').summernote({height: 400});
        $('#footer').summernote({height: 400});
    </script>
@endsection
