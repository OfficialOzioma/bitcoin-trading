@extends('admin.master',['menu'=>'club', 'sub_menu'=>'member_list'])
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
                    <li class="active-item">{{__('Member List')}}</li>
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
                        <h3>{{__('Member List')}}</h3>
                    </div>
                </div>
                <div class="table-area">
                    <div>
                        <table id="table" class="table table-responsive table-borderless custom-table display text-center" width="100%">
                            <thead>
                            <tr>
                                <th class="text-left">{{__('Member')}}</th>
                                <th>{{__('Plan Name')}}</th>
                                <th>{{__('Blocked Coin')}}</th>
                                <th>{{__('Bonus Coin')}}</th>
                                <th>{{__('Start Date')}}</th>
                                <th>{{__('End Date')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Updated At')}}</th>
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
            ajax: '{{route('membershipList')}}',
            order: [7, 'desc'],
            autoWidth: false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "email","orderable": false},
                {"data": "plan_name","orderable": false},
                {"data": "amount","orderable": false},
                {"data": "bonus","orderable": false},
                {"data": "start_date","orderable": false},
                {"data": "end_date","orderable": false},
                {"data": "status","orderable": false},
                {"data": "updated_at","orderable": false}
            ],
        });

    </script>
@endsection
