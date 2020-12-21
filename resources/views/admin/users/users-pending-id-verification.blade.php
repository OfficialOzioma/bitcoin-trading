@extends('admin.master',['menu'=>'users','sub_menu'=>'pending_id'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('User management')}}</li>
                    <li class="active-item">{{__('Pending ID verification')}}</li>
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
                            {{__("Pending ID verification")}}
                        </div>
                    </div>
                    <div class="table-area">
                        <div class="table-responsive">
                            <table id="table" class="table table-borderless custom-table display text-center" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th class="all">{{__('First Name')}}</th>
                                    <th class="desktop">{{__('Last Name')}}</th>
                                    <th class="desktop">{{__('Email ID')}}</th>
                                    <th class="all">{{__('Created At')}}</th>
                                    <th class="all">{{__('Actions')}}</th>
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
            processing:true,
            serverSide:true,
            pageLength:10,
            bLengthChange:true,
            responsive: true,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            ajax:'{{route('adminUserIdVerificationPending')}}',
            order:[3,'desc'],
            autoWidth:false,
            columns:[
                {"data":"first_name",searchable:false},
                {"data":"last_name",searchable:false},
                {"data":"email",searchable:false},
                {"data":"updated_at",searchable:false},
                {"data":"actions",orderable:false,searchable:false},
            ]
        });

    </script>
@endsection
