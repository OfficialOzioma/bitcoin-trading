@extends('admin.master',['menu'=>'buy_coin','sub_menu'=>'give_coin_history'])
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
                    <li>{{__('Coin')}}</li>
                    <li class="active-item">{{__('Give Coin History')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management padding-30">
        <div class="row">
            <div class="col-12">
                <div class="header-bar mt-5">
                    <div class="table-title">
                        <h3>{{__('Give Coin History')}}</h3>
                    </div>
                </div>
                <div class="table-area">
                    <div>
                        <table id="table" class="table table-borderless custom-table display text-center" width="100%">
                            <thead>
                            <tr>
                                <th class="text-left">{{__('User')}}</th>
                                <th>{{__('Wallet Name')}}</th>
                                <th>{{__('Given Coin Amount')}}</th>
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
            ajax: '{{route('giveCoinHistory')}}',
            order: [3, 'desc'],
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
                {"data": "amount","orderable": false},
                {"data": "created_at","orderable": true}
            ],
        });

    </script>
@endsection
