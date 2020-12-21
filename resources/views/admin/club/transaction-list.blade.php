@extends('admin.master',['menu'=>'club', 'sub_menu'=>'transaction_history'])
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
                    <li class="active-item">{{__('Transaction History')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management padding-30">
        <div class="row">
            <div class="col-12">
                <div class="header-bar">
                    <div class="table-title">
                        <h3>{{__('Transaction History')}}</h3>
                    </div>
                </div>
                <div class="table-area">
                    <div>
                        <table id="table" class="table table-responsive table-borderless custom-table display text-center" width="100%">
                            <thead>
                            <tr>
                                <th class="text-left">{{__('Member')}}</th>
                                <th>{{__('Wallet Name')}}</th>
                                <th>{{__('Coin Amount')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Created At')}}</th>
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
            ajax: '{{route('coinTransactionHistory')}}',
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
                {"data": "amount","orderable": false},
                {"data": "type","orderable": false},
                {"data": "status","orderable": false},
                {"data": "created_at","orderable": false}
            ],
        });

    </script>
@endsection
