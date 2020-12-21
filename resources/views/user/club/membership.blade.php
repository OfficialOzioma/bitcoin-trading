@extends('user.master',['menu'=>'member', 'sub_menu'=>'my_membership'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card cp-user-custom-card cp-user-wallet-card">
                <div class="card-body">
                    @if(isset($club))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="cp-user-card-header-area cp-user-card-header-area-c">
                                    <div class="cp-user-title">
                                        <h4>{{__('My Membership Details')}}</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-4">
                                        <ul class="user-plan-table">
                                            <li>
                                                <h4>{{__('Membership Status  ')}} <span>{{ !empty($club->plan_id) ? $club->plan->plan_name. __(' Member') : __('Normal Member')}}</span></h4>
                                            </li>
                                            <li>
                                                <h4>{{__('Blocked Amount  ')}} <span>{{number_format($club->amount,2)}} {{settings('coin_name')}}</span> </h4>
                                            </li>
                                            <li>
                                                <h4>{{__('Member Since  ')}} <span>{{date('d M y', strtotime($club->created_at))}} </span> </h4>
                                            </li>
                                            <li>
                                                <h4>{{__('Earned Bonus ')}} <span>{{ 0 }}{{' '. settings('coin_name')}}</span></h4>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cp-user-card-header-area cp-user-card-header-area-c">
                                    <div class="cp-user-title">
                                        <h4>{{__('My Plan Details')}}</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    @if(!empty($club->plan_id))
                                        <div class="col-md-12 mt-4">
                                            <ul class="user-plan-table">
                                                <li class="user-t-img">
                                                    <img src="{{show_plan_image($club->plan_id,$club->plan->image)}}" class="img-fluid cp-user-logo-large" alt="">
                                                </li>
                                                <li>
                                                    <h4>{{__('Plan Name  ')}} <span>{{$club->plan->plan_name}}</span></h4>
                                                </li>
                                                <li>
                                                    <h4>{{__('Blocked Amount  ')}} <span>{{number_format($club->amount,2)}} {{settings('coin_name')}}</span> </h4>
                                                </li>
                                                <li>
                                                    <h4>{{__('Start Date')}} <span>{{$club->start_date}} </span> </h4>
                                                </li>
                                                <li>
                                                    <h4>{{__('End Date ')}} <span>{{ $club->end_date }}</span></h4>
                                                </li>
                                            </ul>
                                        </div>
                                    @else
                                        <div class="col-md-12">
                                            <h4 class="cp-user-active-notice">{{__('No active plan')}}</h4>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-6">
                                <div class="clap-wrap">
                                    <div class="cp-user-card-header-area mt-5">
                                        <div class="cp-user-title">
                                            <h4>{{__('Currently you are not the member of this membership club')}}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <div class="card cp-user-custom-card cp-user-wallet-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="cp-user-card-header-area cp-user-card-header-area-c">
                                <div class="cp-user-title">
                                    <h4>{{__('My Membership Bonus History')}}</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="cp-user-wallet-table table-responsive">
                                        <table id="table" class="table">
                                            <thead>
                                            <tr>
                                                <th class="text-left">{{__('Member')}}</th>
                                                <th>{{__('Plan Name')}}</th>
                                                <th>{{__('Wallet Name')}}</th>
                                                <th>{{__('Bonus Amount')}}</th>
                                                <th>{{__('Status')}}</th>
                                                <th>{{__('Date')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            retrieve: true,
            bLengthChange: true,
            responsive: true,
            ajax: '{{route('myMembership')}}',
            order: [5, 'desc'],
            autoWidth: false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "email","orderable": false},
                {"data": "plan_id","orderable": false},
                {"data": "wallet_id","orderable": false},
                {"data": "bonus_amount","orderable": false},
                {"data": "status","orderable": false},
                {"data": "distribution_date","orderable": false}
            ],
        });

    </script>
@endsection
