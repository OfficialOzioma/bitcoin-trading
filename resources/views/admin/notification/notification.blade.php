@extends('admin.master',['menu'=>'notification', 'sub_menu'=>'notify'])
@section('title', isset($title) ? $title : '')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/summernote/summernote.css')}}">
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-md-12">
                <ul>
                    <li>{{__('Notification Management')}}</li>
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
                    <form action="{{route('sendNotificationProcess')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mt-20">
                                <div class="form-group">
                                    <label for="firstname">{{__('Title')}}</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" value="{{old('title')}}"  name="title" placeholder="{{__('Notification Title')}}">
                                </div>
                            </div>
                            <div class="col-md-12 mt-20">
                                <div class="form-group">
                                    <label for="firstname">{{__('Notification Body')}}</label>
                                    <textarea name="notification_body" id="" placeholder="{{__('Notification body')}}" class="textarea form-control">{{old('notification_body')}}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="card mail-card">
                                    <div class="card-body">
                                        <button class="btn email-submit-btn"> {{__('Send')}} </button>
                                    </div>
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
    <script src="{{asset('assets/summernote/summernote.js')}}"></script>
    <script>
        $('#text-header').summernote({height: 400});
        $('#text-message').summernote({height: 400});
        $('#footer').summernote({height: 400});
    </script>
@endsection
