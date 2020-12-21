@extends('admin.master',['menu'=>'setting', 'sub_menu'=>'payment-method'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-9">
                <ul>
                    <li>{{__('Setting')}}</li>
                    <li class="active-item">{{ $title }}</li>
                </ul>
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
                        <h3>{{ $title }}</h3>
                    </div>
                </div>
                <div class="table-area">
                    <div>
                        <table id="table" class=" table table-borderless custom-table display text-center" width="100%">
                            <thead>
                            <tr>
                                <th scope="col">{{__('Method Name')}}</th>
                                <th scope="col">{{__('Status')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($payment_methods as $key => $value)
                                <tr>
                                    <td>
                                        {{$value}}
                                    </td>
                                    <td>
                                        @if($key == BTC)
                                            <div>
                                                <label class="switch">
                                                    <input type="checkbox" onclick="return processForm('payment_method_coin_payment')"
                                                           id="notification" name="security" @if(isset($settings['payment_method_coin_payment']) && ($settings['payment_method_coin_payment'] == 1)) checked @endif>
                                                    <span class="slider" for="status"></span>
                                                </label>
                                            </div>
                                        @endif
                                        @if($key == BANK_DEPOSIT)
                                            <div>
                                                <label class="switch">
                                                    <input type="checkbox" onclick="return processForm('payment_method_bank_deposit')"
                                                           id="notification" name="security" @if(isset($settings['payment_method_bank_deposit']) && ($settings['payment_method_bank_deposit'] == 1)) checked @endif>
                                                    <span class="slider" for="status"></span>
                                                </label>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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
        function processForm(active_id) {
            console.log(active_id)
            $.ajax({
                type: "POST",
                url: "{{ route('changePaymentMethodStatus') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'active_id': active_id
                },
                success: function (data) {
                    console.log(data);
                }
            });
        }

    </script>
@endsection
