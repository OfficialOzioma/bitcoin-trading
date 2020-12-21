@extends('admin.master',['menu'=>'buy_coin','sub_menu'=>'buy_coin'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Buy Coin Order List')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management">
        <div class="row">
            <div class="col-12">
                <ul class="nav user-management-nav mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-user-tab" data-toggle="pill" href="#pills-user" role="tab"
                           aria-controls="pills-user" aria-selected="true">
                            <span>{{__('Pending List')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-suspended-user-tab" data-toggle="pill"
                           href="#pills-suspended-user" role="tab" aria-controls="pills-suspended-user"
                           aria-selected="true">
                            <span>{{__('Approved List')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-deleted-user-tab" data-toggle="pill" href="#pills-deleted-user"
                           role="tab" aria-controls="pills-deleted-user" aria-selected="true">
                            <span>{{__('Rejected List')}}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane show active" id="pills-user" role="tabpanel" aria-labelledby="pills-user-tab">
                        <div class="header-bar">
                            <div class="table-title">
                                <h3>{{__('Pending Order List')}}</h3>
                            </div>
                        </div>
                        <div class="table-area">
                            <div class="table-responsive">
                                <table id="table"
                                       class="table table-borderless custom-table display text-center dt-responsive nowrap" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="all">{{__('Email')}}</th>
                                        <th scope="col" class="desktop">{{__('Coin amount')}}</th>
                                        <th scope="col" class="tablet">{{__('Payment Type')}}</th>
                                        <th scope="col" class="desktop">{{__('Address')}}</th>
                                        <th scope="col" class="desktop">{{__('Date')}}</th>
                                        <th scope="col" class="all">{{__('Actions')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="pills-suspended-user" role="tabpanel"
                         aria-labelledby="pills-suspended-user-tab">
                        <div class="header-bar">
                            <div class="table-title">
                                <h3>{{__('Approved Order List')}}</h3>
                            </div>
                        </div>
                        <div class="table-area">
                            <div class=" table-responsive">
                                <table id="table-approved"
                                       class="table responsive table-borderless custom-table display text-center"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="all">{{__('Email')}}</th>
                                        <th scope="col" class="desktop">{{__('Coin amount')}}</th>
                                        <th scope="col" class="desktop">{{__('Payment Type')}}</th>
                                        <th scope="col" class="desktop">{{__('Address')}}</th>
                                        <th scope="col" class="all">{{__('Date')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="pills-deleted-user" role="tabpanel"
                         aria-labelledby="pills-deleted-user-tab">
                        <div class="header-bar">
                            <div class="table-title">
                                <h3>{{__('Rejected Order List')}}</h3>
                            </div>
                        </div>
                        <div class="table-area">
                            <div class="table-responsive">
                                <table id="table-rejected"
                                       class="table table-borderless custom-table display text-center" width="100%">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="all">{{__('Email')}}</th>
                                        <th scope="col" class="desktop">{{__('Coin amount')}}</th>
                                        <th scope="col" class="desktop">{{__('Payment Type')}}</th>
                                        <th scope="col" class="desktop">{{__('Address')}}</th>
                                        <th scope="col" class="all">{{__('Date')}}</th>
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
                    pageLength: 25,
                    responsive: true,
                    order: [5, 'desc'],
                    autoWidth: true,
                    ajax: '{{route('adminPendingCoinOrder')}}',
                    language: {
                        paginate: {
                            next: 'Next &#8250;',
                            previous: '&#8249; Previous'
                        }
                    },
                    columns: [
                        {"data": "email"},
                        {"data": "coin"},
                        {"data": "payment_type"},
                        {"data": "address"},
                        {"data": "date"},
                        {"data": "action"}
                    ]
                });

            </script>
            <script>
                $('#table-approved').DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: 25,
                    responsive: true,
                    order: [4, 'desc'],
                    autoWidth: false,
                    ajax: '{{route('adminApprovedOrder')}}',
                    language: {
                        paginate: {
                            next: 'Next &#8250;',
                            previous: '&#8249; Previous'
                        }
                    },
                    columns: [
                        {"data": "email"},
                        {"data": "coin"},
                        {"data": "payment_type"},
                        {"data": "address"},
                        {"data": "date"},
                    ]
                });

            </script>
            <script>
                $('#table-rejected').DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: 25,
                    responsive: true,
                    order: [4, 'desc'],
                    autoWidth: false,
                    ajax: '{{route('adminRejectedOrder')}}',
                    language: {
                        paginate: {
                            next: 'Next &#8250;',
                            previous: '&#8249; Previous'
                        }
                    },
                    columns: [
                        {"data": "email"},
                        {"data": "coin"},
                        {"data": "payment_type"},
                        {"data": "address"},
                        {"data": "date"},
                    ]
                });

            </script>
@endsection
