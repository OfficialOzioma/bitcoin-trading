@extends('user.master',['menu'=>'pocket'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <div class="card cp-user-custom-card cp-user-deposit-card">
        <div class="row">
            <div class="col-sm-12">
                <div class="wallet-inner">
                    <div class="wallet-content card-body">
                        <div class="wallet-top cp-user-card-header-area">
                            <div class="title">
                                <div class="wallet-title text-center">
                                    <h4>{{$wallet->name}}</h4>
                                </div>
                            </div>
                            <div class="tab-navbar">
                                <div class="tabe-menu">
                                    <ul class="nav cp-user-profile-nav mb-0" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link wallet {{($active == 'deposit') ? 'active' : ''}}"
                                               id="diposite-tab"
                                               href="{{route('walletDetails',$wallet->id)}}?q=deposit"
                                               aria-controls="diposite" aria-selected="true">
                                                <i class="flaticon-wallet"></i> {{__('Deposit')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link send  {{($active == 'withdraw') ? 'active' : ''}}"
                                               id="withdraw-tab"
                                               href="{{route('walletDetails',$wallet->id)}}?q=withdraw"
                                               aria-controls="withdraw" aria-selected="false">
                                                <i class="flaticon-send"> </i> {{__('Withdraw')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link share  {{($active == 'activity') ? 'active' : ''}}"
                                               id="activity-tab"
                                               href="{{route('walletDetails',$wallet->id)}}?q=activity"
                                               aria-controls="activity" aria-selected="false">
                                                <i class="flaticon-share"> </i> {{__('Activity log')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade   {{($active == 'deposit') ? 'show active' : ''}} in"
                                 id="diposite" role="tabpanel"
                                 aria-labelledby="diposite-tab">
                                <div class="row">
                                    <div class="col-lg-4 offset-lg-1">
                                        <div class="qr-img text-center">
                                            @if(!empty($address))  {!! QrCode::size(300)->generate($address); !!}
                                            @else
                                                {!! QrCode::size(300)->generate(0); !!}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="cp-user-copy tabcontent-right">
                                            <form action="#" class="px-3">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="copy_to_clip btn">{{__('Copy')}}</button>
                                                    </div>
                                                    <input readonly value="{{isset($address) ? $address : 0}}"
                                                           type="text" class="form-control" id="address">
                                                </div>
                                            </form>
                                            <div class="aenerate-address">
                                                <a class="btn cp-user-buy-btn"
                                                   href="{{route('generateNewAddress')}}?wallet_id={{$wallet_id}}">
                                                    {{__('Generate a new address')}}
                                                </a>
                                            </div>
                                            <div class="show-post">
                                                <button class="btn cp-user-buy-btn"
                                                        onclick="$('.address-list').toggleClass('show');">Show past
                                                    address
                                                </button>
                                                <div class="address-list">
                                                    <div class="cp-user-wallet-table table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <th>Address</th>
                                                                <th>Created At</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($address_histories as $address_history)
                                                                <tr>
                                                                    <td>{{$address_history->address}}</td>
                                                                    <td>{{$address_history->created_at}}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade {{($active == 'withdraw') ? 'show active' : ''}} in" id="withdraw"
                                 role="tabpanel" aria-labelledby="withdraw-tab">
                                <div class="row">
                                    <div class="col-lg-6 offset-lg-3">
                                        <div class="form-area cp-user-profile-info withdraw-form">
                                            <form action="{{route('WithdrawBalance')}}" method="post"
                                                  id="withdrawFormData">
                                                @csrf
                                                <input type="hidden" name="wallet_id" value="{{$wallet_id}}">
                                                <div class="form-group">
                                                    <label for="to">To</label>
                                                    <input name="address" type="text" class="form-control" id="to"
                                                           placeholder="{{__('Address')}}">
                                                    <span class="flaticon-wallet icon"></span>
                                                    <span class="text-warning">{{__('Note : Please input here your ')}} {{allsetting('base_coin_type')}} {{__(' Coin address for withdrawal')}}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="amount">{{__('Amount')}}</label>
                                                    <input name="amount" type="text" class="form-control" id="amount"
                                                           placeholder="Amount">
                                                    <p class="text-warning" id="equ_btc"><span class="totalBTC"></span> <span class="coinType"></span></p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="note">{{__('Note')}}</label>
                                                    <textarea class="form-control" name="message" id="note"
                                                              placeholder="{{__('Type your message here(Optional)')}}"></textarea>
                                                </div>
                                                <button onclick="withDrawBalance()" type="button"
                                                        class="btn profile-edit-btn">{{__('Submit')}}</button>
                                                <div class="modal fade" id="g2fcheck" tabindex="-1" role="dialog"
                                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="exampleModalLabel">{{__('Google Authentication')}}</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p>{{__('Open your Google Authenticator app and enter the 6-digit code from the app into the input field to remove the google secret key')}}</p>
                                                                        <input placeholder="{{__('Code')}}" required
                                                                               type="text" class="form-control"
                                                                               name="code">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">{{__('Close')}}</button>
                                                                <button type="submit"
                                                                        class="btn btn-primary">{{__('Verify')}}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade  {{($active == 'activity') ? 'show active' : ''}} in"
                                 id="activity" role="tabpanel" aria-labelledby="activity-tab">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="activity-area">
                                            <div class="activity-top-area">
                                                <div class="cp-user-card-header-area">
                                                    <div class="title">
                                                        <h4 id="list_title">{{__('All Deposit List')}}</h4>
                                                    </div>
                                                    <div class="deposite-tabs cp-user-deposit-card">
                                                        <div class="activity-right text-right">
                                                            <ul class="nav cp-user-profile-nav mb-0">
                                                                <li class="nav-item">
                                                                    <a class="nav-link active" data-toggle="tab" onclick="$('#list_title').html('All Deposit List')" data-title="" href="#Deposit">{{__('Deposit')}}</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" data-toggle="tab" onclick="$('#list_title').html('All Withdrawal List')" href="#Withdraw">{{__('Withdraw')}}</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="activity-list">
                                                <div class="tab-content">
                                                    <div id="Deposit" class="tab-pane fade show in active">

                                                        <div class="cp-user-wallet-table table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <th>{{__('Address')}}</th>
                                                                    <th>{{__('Amount')}}</th>
                                                                    <th>{{__('Transaction Hash')}}</th>
                                                                    <th>{{__('Status')}}</th>
                                                                    <th>{{__('Created At')}}t</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @if(isset($histories[0]))
                                                                    @foreach($histories as $history)
                                                                        <tr>
                                                                            <td>{{$history->address}}</td>
                                                                            <td>{{$history->amount}}</td>
                                                                            <td>{{$history->transaction_id}}</td>
                                                                            <td>{{deposit_status($history->status)}}</td>
                                                                            <td>{{$history->created_at}}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="5"
                                                                            class="text-center">{{__('No data available')}}</td>
                                                                    </tr>
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div id="Withdraw" class="tab-pane fade in ">

                                                        <div class="cp-user-wallet-table table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <th>{{__('Address')}}</th>
                                                                    <th>{{__('Amount')}}</th>
                                                                    <th>{{__('Transaction Hash')}}</th>
                                                                    <th>{{__('Status')}}t</th>
                                                                    <th>{{__('Created At')}}t</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @if(isset($withdraws[0]))
                                                                    @foreach($withdraws as $withdraw)
                                                                        <tr>
                                                                            <td>{{$withdraw->address}}</td>
                                                                            <td>{{$withdraw->amount}}</td>
                                                                            <td>{{$withdraw->transaction_hash}}</td>
                                                                            <td>{{deposit_status($withdraw->status)}}</td>
                                                                            <td>{{$withdraw->created_at}}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="5"
                                                                            class="text-center">{{__('No data available')}}</td>
                                                                    </tr>
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function withDrawBalance() {
            var g2fCheck = '{{\Illuminate\Support\Facades\Auth::user()->google2fa_secret}}';


            if (g2fCheck.length > 1) {
                var frm = $('#withdrawFormData');

                $.ajax({
                    type: frm.attr('method'),
                    url: frm.attr('action'),
                    data: frm.serialize(),
                    success: function (data) {
                        console.log(data.success);
                        if (data.success == true) {
                            $('#g2fcheck').modal('show');

                        } else {
                            VanillaToasts.create({
                                // title: 'Message Title',
                                text: data.message,
                                type: 'warning',
                                timeout: 3000

                            });
                        }

                    },
                    error: function (data) {

                    },
                });
            } else {
                VanillaToasts.create({
                    // title: 'Message Title',
                    text: "{{__('Your google authentication is disabled,please enable it')}}",
                    type: 'warning',
                    timeout: 3000

                });
            }

        }
    </script>
    <script>


        document.querySelector('button').addEventListener('click', function (event) {

            var copyTextarea = document.querySelector('#address');
            copyTextarea.focus();
            copyTextarea.select();

            try {
                var successful = document.execCommand('copy');
                VanillaToasts.create({
                    // title: 'Message Title',
                    text: '{{__('Address copied successfully')}}',
                    type: 'success',

                });
            } catch (err) {

            }
        });

        function generateNewAddress() {
            $.ajax({
                type: "GET",
                enctype: 'multipart/form-data',
                url: "{{route('generateNewAddress')}}?wallet_id={{$wallet_id}}",
                success: function (data) {
                    if (data.success == true) {

                        $('#address').val(data.address);
                        var srcVal = "{{route('qrCodeGenerate')}}?address=" + data.address;
                        document.getElementById('qrcode').src = srcVal;
                        VanillaToasts.create({
                            // title: 'Message Title',
                            text: data.message,
                            type: 'success',
                            timeout: 3000

                        });
                        $('#qrcode').src(data.qrcode);
                    } else {

                        VanillaToasts.create({
                            // title: 'Message Title',
                            text: data.message,
                            type: 'warning',
                            timeout: 3000

                        });

                    }
                }
            });
        }
    </script>

    <script>
        function call_coin_rate(amount) {
            $.ajax({
                type: "POST",
                url: "{{ route('withdrawCoinRate') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'amount': amount,
                },
                dataType: 'JSON',

                success: function (data) {
                    console.log(data);
                    $('.totalBTC').text(data.btc_dlr);
                    $('.coinType').text(data.coin_type);
                },
                error: function () {
                }
            });
        }
    </script>

    <script>
        function delay(callback, ms) {
            var timer = 0;
            return function () {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

        function call_coin_payment() {
            var amount = $('input[name=amount]').val();
            call_coin_rate(amount);
        }

        $("#amount").keyup(delay(function (e) {
            var amount = $('input[name=amount]').val();
            call_coin_rate(amount);
            console.log(amount);

        },500));

    </script>
<!-- copy_to_clip -->
    <script>
    $('.copy_to_clip').on('click', function () {
        /* Get the text field */
        var copyFrom = document.getElementById("address");

        /* Select the text field */
        copyFrom.select();

        /* Copy the text inside the text field */
        document.execCommand("copy");

        VanillaToasts.create({
            title: 'Copied the text',
            // text: copyFrom.value,
            type: 'success',
            timeout: 3000,
            positionClass: 'topCenter'
        });
    })
    </script>
@endsection
