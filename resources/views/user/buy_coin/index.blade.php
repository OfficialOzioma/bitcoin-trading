@extends('user.master',['menu'=>'coin', 'sub_menu'=>'buy_coin'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-6 mb-xl-0 mb-4">
            <div class="card cp-user-custom-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <h4>{{__('Buy Our Coin From Here')}}</h4>
                    </div>
                    <div class="cp-user-buy-coin-content-area">
{{--                        <p>Curabitur at metus vel risus posuere fringilla sit amet vel tortor. Praesent blandit dolor in mi sodales, ut dictum metus efficitur. Nulla pulvinar enim ligula.</p>--}}
                        <div class="cp-user-coin-info">
                            <form action="{{route('buyCoinProcess')}}" method="POST" enctype="multipart/form-data" id="buy_coin">
                                @csrf
                                <div class="form-group">
                                    <label>{{__('Coin Amount')}}</label>
                                    <input oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                           name="coin" autocomplete="off" id="amount" class="form-control" placeholder="{{__('Your Amount')}}">
                                    <ul class="coin_price">
                                        <li>{{settings('coin_price')}} x <span class="coinAmount">1</span> = <span class="CoinInDoller">{{settings('coin_price')}} </span> USD</li>
                                        <li>$<span class="CoinInDoller">{{settings('coin_price')}} USD</span> = <span class="totalBTC">{{$btc_dlr}}</span> <span class="coinType"> BTC</span></li>
                                    </ul>
                                </div>
                                <div class="cp-user-payment-type">
                                    <h3>{{__('Payment Type')}}</h3>
                                    @if(isset($settings['payment_method_coin_payment']) && $settings['payment_method_coin_payment'] == 1)
                                        <div class="form-group">
                                            <input type="radio" onclick="call_coin_payment();" onchange="$('.payment_method').addClass('d-none');$('.bank-details').addClass('d-none');$('.bank-details').removeClass('d-block');$('.btc_payment').toggleClass('d-none');" value="{{BTC}}" id="coin-option" name="payment_type">
                                            <label for="coin-option">{{__('Coin Payment')}}</label>
                                        </div>
                                    @endif
                                    @if(isset($settings['payment_method_bank_deposit']) && $settings['payment_method_bank_deposit'] == 1)
                                        <div class="form-group">
                                            <input type="radio" value="{{BANK_DEPOSIT}}"  onchange="$('.payment_method').addClass('d-none');$('.bank-details').addClass('d-block');$('.bank-details').removeClass('d-none');$('.bank_payment').toggleClass('d-none');" id="f-option" name="payment_type">
                                            <label for="f-option">{{__('Bank Deposit')}}</label>
                                        </div>
                                    @endif
                                </div>
                                <div class="check-box-list btc_payment payment_method d-none">

                                    <div class="form-group buy_coin_address_input ">
                                        <p>
                                            <span id="coinpayment_address"></span>
                                        </p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">{{__('Payable Coin')}}</label>
                                                <input class="form-control" disabled type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                       readonly name="total_price" id="total_price" placeholder="Amount">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">{{__('Select')}}</label>
                                                <div class="cp-select-area">
                                                <select name="payment_coin_type" class="selet-im vodiapicker form-control " id="payment_type">
                                                    @foreach(paymentTypes() as $key => $paymentType)
                                                        <option
                                                            value="{{$key}}">
                                                            {{$paymentType}}
                                                        </option>

                                                    @endforeach
                                                </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="check-box-list bank_payment payment_method d-none">
                                    <div class="form-group">
                                        <label>{{__('Select Bank')}}</label>
                                        <div class="cp-select-area">
                                        <select name="bank_id" class="bank-id form-control " >
                                            <option value="">{{__('Select')}}</option>
                                            @if(isset($banks[0]))
                                                @foreach($banks as $value)
                                                    <option @if((old('bank_id') != null) && (old('bank_id') == $value->id)) @endif value="{{ $value->id }}">{{$value->bank_name}}</option>
                                                    <span class="text-danger"><strong>{{ $errors->first('bank_id') }}</strong></span>
                                                @endforeach
                                            @endif
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group buy_coin_address_input mt-4">
                                        <div id="file-upload" class="section-p">
                                            <input type="hidden" name="bank_deposit_id" value="">
                                            <input type="file" placeholder="0.00" name="sleep" value="" id="file" ref="file" class="dropify" data-default-file="{{asset('assets/img/placeholder-image.png')}}" />
                                        </div>
                                    </div>

                                </div>

                                <button id="buy_button" type="submit" class="btn theme-btn">{{__('Buy Now')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card cp-user-custom-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <h4>{{__("Today’s Coin Rate")}}</h4>
                    </div>
                    <div class="cp-user-coin-rate">
                        <ul>
                            <li>1 {{ settings('coin_name') }}</li>
                            <li>=</li>
                            <li>{{settings('coin_price')}}  USD</li>
                        </ul>
                        <div class="img" id="r-side-img">
                            <img src="{{ asset('assets/user/images/buy-coin-vector.svg') }}" class="img-fluid" alt="">
                        </div>
                        <div class="bank-details">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        //bank details

        $('.bank-id').change(function () {
            var id = $(this).val();
            $.ajax({
                url: "{{route('bankDetails')}}?val=" + id,
                type: "get",
                success: function (data) {
                    console.log(data);
                    $('div.bank-details').html(data.data_genetare);
                    $('#r-side-img').hide();
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        });
    </script>

    <script>
        //change payment type

        $('#payment_type').change(function () {
            var id = $(this).val();
            var amount = $('input[name=coin]').val();
            var pay_type = document.querySelector('input[name="payment_type"]:checked').value;
            var payment_type = $('#payment_type').val();
            call_coin_rate(amount,pay_type,payment_type);

        });
    </script>

    <script>
        function call_coin_rate(amount,pay_type,payment_type) {
            $.ajax({
                type: "POST",
                url: "{{ route('buyCoinRate') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'amount': amount,
                    'payment_type': payment_type,
                    'pay_type': pay_type,
                },
                dataType: 'JSON',

                success: function (data) {
                    console.log(data);
                    $('.coinAmount').text(data.amount);
                    $('.CoinInDoller').text(data.coin_price);
                    $('.totalBTC').text(data.btc_dlr);
                    $('#total_price').val(data.btc_dlr);
                    $('.coinType').text(data.coin_type);
                },
                error: function () {
                    $('.btc-price').addClass('d-none');
                    $('.private-sell-submit').attr('disabled', false);
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
            var amount = $('input[name=coin]').val();
            var pay_type = document.querySelector('input[name="payment_type"]:checked').value;
            var payment_type = $('#payment_type').val();
            call_coin_rate(amount,pay_type,payment_type);
        }

        $("#amount").keyup(delay(function (e) {
            var amount = $('input[name=coin]').val();
            var pay_type = document.querySelector('input[name="payment_type"]:checked').value;
            var payment_type = $('#payment_type').val();

            call_coin_rate(amount,pay_type,payment_type);

        },500));

    </script>
@endsection
