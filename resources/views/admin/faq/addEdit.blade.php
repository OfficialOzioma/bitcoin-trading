@extends('admin.master',['menu'=>'faq', 'sub_menu'=>'faq'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('FAQs')}}</li>
                    <li class="active-item">{{$title}}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management add-custom-page">
        <div class="row">
            <div class="col-12">
                <div class="header-bar">
                    <div class="table-title">
                        <h3>{{$title}}</h3>
                    </div>
                </div>
                <div class="profile-info-form">
                    <form action="{{route('adminFaqSave')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-xl-6 mb-xl-0 mb-4">
                                <div class="form-group">
                                    <label>{{__('Question')}}</label>
                                    <input type="text" name="question" class="form-control" @if(isset($item)) value="{{$item->question}}" @else value="{{old('question')}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label>{{__('Activation Status')}}</label>
                                    <select name="status" class="form-control wide" >
                                        @foreach(status() as $key => $value)
                                            <option @if(isset($item) && ($item->status == $key)) selected
                                                    @elseif((old('status') != null) && (old('status') == $key)) @endif value="{{ $key }}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 mb-xl-0 mb-4">
                                <div class="form-group">
                                    <label>{{__('Answer')}}</label>
                                    <textarea class="form-control textarea" name="answer">@if(isset($item)){{$item->answer}}@else{{old('answer')}}@endif</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    @if(isset($item))
                                        <input type="hidden" name="edit_id" value="{{$item->id}}">
                                    @endif
                                    <button type="submit" class="btn add-faq-btn">{{__('Save')}}</button>
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
    <script>

    </script>
@endsection
