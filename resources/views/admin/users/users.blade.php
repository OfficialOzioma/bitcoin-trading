@extends('admin.master',['menu'=>'users', 'sub_menu'=>'user'])
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
                    <li class="active-item">{{__('User')}}</li>
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
                        <a data-id="active_users" class="nav-link active" id="pills-user-tab" data-toggle="pill" href="#pills-user" role="tab" aria-controls="pills-user" aria-selected="true">
                            <img src="{{asset('assets/admin/images/user-management-icons/user.svg')}}" class="img-fluid" alt="">
                            <span>{{__('User List')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a data-id="profile_tab" class="nav-link add_user" id="pills-add-user-tab" data-toggle="pill" href="#pills-add-user" role="tab" aria-controls="pills-add-user" aria-selected="true">
                            <img src="{{asset('assets/admin/images/user-management-icons/add-user.svg')}}" class="img-fluid" alt="">
                            <span>{{__('Add User')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a data-id="suspend_user" class="nav-link" id="pills-suspended-user-tab" data-toggle="pill" href="#pills-user" role="tab" aria-controls="pills-suspended-user" aria-selected="true">
                            <img src="{{asset('assets/admin/images/user-management-icons/block-user.svg')}}" class="img-fluid" alt="">
                            <span>{{__('Suspended User')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a data-id="deleted_user" class="nav-link" id="pills-deleted-user-tab" data-toggle="pill" href="#pills-user" role="tab" aria-controls="pills-deleted-user" aria-selected="true">
                            <img src="{{asset('assets/admin/images/user-management-icons/delete-user.svg')}}" class="img-fluid" alt="">
                            <span>{{__('Deleted User')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a data-id="email_pending" class="nav-link" id="pills-email-tab" data-toggle="pill" href="#pills-user" role="tab" aria-controls="pills-email" aria-selected="true">
                            <img src="{{asset('assets/admin/images/user-management-icons/email.svg')}}" class="img-fluid" alt="">
                            {{__('Email Pending')}}
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane show active" id="pills-user" role="tabpanel" aria-labelledby="pills-user-tab">
                        <div class="header-bar">
                            <div class="table-title">
                            </div>
                        </div>
                        <div class="table-area">
                            <div class=" table-responsive">
                                <table id="table" class="table table-borderless custom-table display text-center" style="width: 100%;">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="all">{{__('User Name')}}</th>
                                        <th scope="col" class="desktop">{{__('Email ID')}}</th>
                                        <th scope="col" class="all">{{__('Role')}}</th>
                                        <th scope="col" class="desktop">{{__('Status')}}</th>
                                        <th scope="col" class="desktop">{{__('Created At')}}</th>
                                        <th scope="col" class="all">{{__('Activity')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane add_user" id="pills-add-user" role="tabpanel" aria-labelledby="pills-add-user-tab">
                        <div class="header-bar">
                            <div class="table-title">
                                <h3>{{__('Add User')}}</h3>
                            </div>
                        </div>
                        <div class="add-user-form">
                            <form action="{{route('admin.UserAddEdit')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstname">{{__('First Name')}}</label>
                                            <input type="text" name="first_name" class="form-control" id="firstname" value="{{old('first_name')}}"  placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lastname">{{__('Last Name')}}</label>
                                            <input name="last_name" type="text" class="form-control" id="lastname" value="{{old('last_name')}}"  placeholder="Last Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">{{__('Email')}}</label>
                                            <input type="email" name="email" class="form-control" id="email" value="{{old('email')}}" placeholder="Email address">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lastname">{{__('Phone Number')}}</label>
                                            <input type="text" class="form-control" id="phone" name="phone" value="{{old('phone')}}"  placeholder="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('Role')}}</label>
                                            <div class="cp-select-area">
                                            <select name="role" class="wide form-control">
                                                <option value="{{USER_ROLE_ADMIN}}">{{__('Admin')}}</option>
                                                <option data-display="User" value="{{USER_ROLE_USER}}">{{__('User')}}</option>
                                            </select>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button class="button-primary theme-btn">{{__('Save')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane" id="pills-suspended-user" role="tabpanel" aria-labelledby="pills-suspended-user-tab">
                        <div class="header-bar">
                            <div class="table-title">
                                <h3>Suspended User</h3>
                            </div>
                            <div class="search">
                                <form>
                                    <div class="form-group">
                                        <input type="search" class="form-control" placeholder="Search">
                                        <button class="btn btn-search"><img src="images/search.svg" class="img-fluid" alt=""></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-area">
                            <div class="table-responsive">
                                <table class="table table-borderless custom-table display text-center" width="100%">
                                    <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email ID</th>
                                        <th>Updated At</th>
                                        <th>Activity</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-pagination">
                                <ul>
                                    <li><a href="javascript:;"><img src="images/angle-left.svg" class="img-fluid" alt=""></a></li>
                                    <li class="active"><a href="javascript:;">1</a></li>
                                    <li><a href="javascript:;">2</a></li>
                                    <li><a href="javascript:;">3</a></li>
                                    <li><a href="javascript:;"><img src="images/angle-right.svg" class="img-fluid" alt=""></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- /User Management -->
@endsection

@section('script')
    @if(isset($errors->all()[0]))
        <script>

            $('.tab-pane').removeClass('active show');
            $('.nav-link').removeClass('active show');
            $('.add_user').addClass('active show');
            $('#profile-tab').addClass('active show');

        </script>
    @endif
    <script>
        function getTable(type) {
            console.log(type)
            var table =  $('#table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                retrieve: true,
                bLengthChange: true,
                responsive: true,
                ajax: '{{route('adminUsers')}}?type='+type,
                order: [3, 'desc'],
                autoWidth: false,
                language: {
                    paginate: {
                        next: 'Next &#8250;',
                        previous: '&#8249; Previous'
                    }
                },
                columns: [
                    {"data": "first_name","orderable": false},
                    {"data": "email","orderable": false},
                    {"data": "type","orderable": false},
                    {"data": "status","orderable": false},
                    {"data": "created_at","orderable": false},
                    {"data": "activity","orderable": false}
                ],
            });

        }
        $(document.body).on('click','.nav-link',function () {
            var id = $(this).data('id');
            if (id != 'undefined'){
                $('#table').DataTable().destroy();
                getTable(id)
                console.log(id)
            }

        });
        getTable('active_users');
    </script>
@endsection
