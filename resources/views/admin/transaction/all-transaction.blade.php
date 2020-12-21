@extends('admin.master',['menu'=>'transaction', 'sub_menu'=>'transaction_all'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Transaction')}}</li>
                    <li class="active-item">{{__('All History')}}</li>
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
                            {{__("Transaction History")}}
                        </div>
                    </div>
                    <div class="table-area">
                        <div class="table-responsive">
                            <table id="table" class="table table-borderless custom-table display text-center"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th class="all">{{__('Type')}}</th>
                                    <th class="all">{{__('Sender')}}</th>
                                    <th class="desktop">{{__('Address')}}</th>
                                    <th class="desktop">{{__('Receiver')}}</th>
                                    <th class="desktop">{{__('Amount')}}</th>
                                    <th class="desktop">{{__('Fees')}}</th>
                                    <th class="desktop">{{__('Transaction Id')}}</th>
                                    <th class="desktop">{{__('Status')}}</th>
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
            pageLength: 25,
            responsive: true,
            ajax: '{{route('adminTransactionHistory')}}',
            order: [7, 'desc'],
            autoWidth: false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "address_type"},
                {"data": "sender"},
                {"data": "address"},
                {"data": "receiver"},
                {"data": "amount"},
                {"data": "fees"},
                {"data": "transaction_id"},
                {"data": "status"},
                {"data": "created_at"}
            ]
        });
    </script>
@endsection
