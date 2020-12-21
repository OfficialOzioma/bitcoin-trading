@extends('admin.master',['menu'=>'club', 'sub_menu'=>'bonus'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Membership Club')}}</li>
                    <li class="active-item">{{__('Bonus Distribution')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management padding-30">
        <div class="row">
            <div class="col-12">
                <div>
                    <a href="{{route('adminClubBonusDistribution')}}">
                        <button class="btn btn-success">{{__('Distribute Membership Bonus')}}</button>
                    </a>
                </div>
                <div class="header-bar mt-5">
                    <div class="table-title">
                        <h3>{{__('Bonus Distribution History')}}</h3>
                    </div>
                </div>
                <div class="table-area">
                    <div>
                        <table id="table" class="table table-borderless custom-table display text-center" width="100%">
                            <thead>
                            <tr>
                                <th class="text-left">{{__('Member')}}</th>
                                <th>{{__('Wallet Name')}}</th>
                                <th>{{__('Plan Name')}}</th>
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
    <!-- /User Management -->


@endsection

@section('script')
    <script>
        $('#table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            retrieve: true,
            bLengthChange: true,
            responsive: false,
            ajax: '{{route('clubBonusDistribution')}}',
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
                {"data": "wallet_id","orderable": false},
                {"data": "plan_id","orderable": false},
                {"data": "bonus_amount","orderable": false},
                {"data": "status","orderable": false},
                {"data": "distribution_date","orderable": false}
            ],
        });

    </script>
@endsection
