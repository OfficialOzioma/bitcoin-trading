@extends('admin.master',['menu'=>'pocket'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Pocket Management')}}</li>
                    <li class="active-item">{{__('Pocket List')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management">
        <div class="row">
            <div class="col-12">
                <div class="card-body">
                    <div class="header-bar">
                        <div class="table-title">
                            {{__("User Pocket List")}}
                        </div>
                    </div>
                    <div class="table-area">
                        <div class="table-responsive">
                            <table id="table" class="table table-borderless custom-table display text-center" width="100%">
                                <thead>
                                <tr>
                                    <th class="all">{{__('Wallet Name')}}</th>
                                    <th class="desktop">{{__('User Name')}}</th>
                                    <th class="desktop">{{__('User Email')}}</th>
                                    <th class="desktop">{{__('Balance')}}</th>
                                    <th class="desktop">{{__('Referral Balance')}}</th>
                                    <th class="all">{{__('Update Date')}}</th>
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
    <!-- /User Management -->
@endsection

@section('script')
    <script>
        $('#table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            bLengthChange: true,
            responsive:true,
            ajax: '{{route('adminWalletList')}}',
            order:[4,'desc'],
            autoWidth:false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "name"},
                {"data": "user_name"},
                {"data": "email",name: 'users.email'},
                {"data": "balance"},
                {"data": "referral_balance"},
                {"data": "created_at"}
            ]
        });

    </script>
@endsection
