@extends('admin.master',['menu'=>'setting', 'sub_menu'=>'bank'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-9">
                <ul>
                    <li>{{__('Bank Management')}}</li>
                    <li class="active-item">{{ $title }}</li>
                </ul>
            </div>
            <div class="col-sm-3 text-right">
                <a class="add-btn theme-btn" href="{{route('bankAdd')}}">{{__('Add New Bank')}}</a>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management">
        <div class="row">
            <div class="col-12">
                <div class="header-bar p-4">
                    <div class="table-title">
                        <h3>{{__('Bank Management')}}</h3>
                    </div>
                </div>
                <div class="table-area">
                    <div>
                        <table id="table" class="table-responsive table table-borderless custom-table display text-center" width="100%">
                            <thead>
                            <tr>
                                <th scope="col">{{__('Bank Name')}}</th>
                                <th scope="col">{{__('Holder Name')}}</th>
                                <th scope="col">{{__('IBAN')}}</th>
                                <th scope="col">{{__('Country')}}</th>
                                <th scope="col">{{__('Created At')}}</th>
                                <th scope="col">{{__('Status')}}</th>
                                <th scope="col">{{__('Activity')}}</th>
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
            ajax: '{{route('bankList')}}',
            order: [4, 'desc'],
            autoWidth: false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "bank_name","orderable": false},
                {"data": "account_holder_name","orderable": false},
                {"data": "iban","orderable": false},
                {"data": "country","orderable": false},
                {"data": "created_at","orderable": false},
                {"data": "status","orderable": false},
                {"data": "activity","orderable": false}
            ],
        });

    </script>
@endsection
