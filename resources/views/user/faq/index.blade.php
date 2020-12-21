@extends('user.master',['menu'=>'setting', 'sub_menu'=>'faq'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card cp-user-custom-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area cp-user-card-header-bb">
                        <h4>{{__('FAQs')}}</h4>
                    </div>
                    <div id="accordion" class="accordion">
                        <div class="row ">
                            @if(isset($items[0]))
                                @php $i=1 @endphp
                                @foreach($items as $item)
                                    <div class="col-lg-6">
                                        <div class="cp-user-referral-content">
                                            <div class="card">
                                                <div class="card-header" id="headingOne">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" data-toggle="collapse"
                                                                data-target="#collapseOne{{$item->id}}"
                                                                aria-expanded="true" aria-controls="collapseOne">
                                                            {{ $item->question }}
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseOne{{$item->id}}" class="collapse @if($i == 1) show @endif" aria-labelledby="headingOne"
                                                     data-parent="#accordion">
                                                    <div class="card-body">
                                                        {{ $item->answer }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php $i++ @endphp
                                @endforeach
                            @else
                                <div class="col-md-12">
                                    <p class="text-center text-danger">{{__('No data found')}}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection
