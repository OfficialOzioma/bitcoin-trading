@extends('user.master',['menu'=>'member', 'sub_menu'=>'coin_transfer'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card cp-user-custom-card cp-user-wallet-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="cp-user-card-header-area">
                                <div class="cp-user-title">
                                    <h4>{{__('Membership Club')}}</h4>
                                </div>
                            </div>
                            <div class="clap-wrap mt-5">
                                <ul class="mb-5">
                                    <li>
                                        <h4>{{__('Become a member you can transfer your coin to your club wallet for some days, then you will get exclusive bonus')}}</h4>
                                    </li>
                                    <li class="mt-4">
                                        <h4>
                                            {{__('If you are not the member, to become a member for the first time please transfer minimum ')}}{{number_format($small_plan->amount,2) }} {{settings('coin_name')}}
                                            {{__(' for ')}} {{$small_plan->duration}} {{__(' days.')}}
                                        </h4>
                                    </li>
                                </ul>

                                <ul class="nav nav-pills transfer-tabs my-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="pills-transfer-1-tab" data-toggle="pill"
                                           href="#pills-transfer-1" role="tab" aria-controls="pills-transfer-1"
                                           aria-selected="true">{{__('Transfer coin to club wallet')}}</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-transfer-2-tab" data-toggle="pill"
                                           href="#pills-transfer-2" role="tab" aria-controls="pills-transfer-2"
                                           aria-selected="false">{{__('Transfer coin to main wallet')}}</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-transfer-1" role="tabpanel"
                                         aria-labelledby="pills-transfer-1-tab">
                                        <div class="cp-user-card-header-area d-block">
                                            <div class="cp-user-title">
                                                <h4>{{__('Transfer coin from your main wallet to club wallet')}}</h4>
                                            </div>
                                            <div class="cp-user-profile-info">
                                                <form class="mt-4" method="POST"
                                                      action="{{route('transferCoinToClub')}}">
                                                    @csrf
                                                    <div class="form-group mt-4">
                                                        <label>{{__('Select From Wallet')}}</label>
                                                        <div class="cp-select-area">
                                                            <select name="wallet_id" class="form-control" id="">
                                                                <option value="">{{__('Select')}}</option>
                                                                @if(isset($wallets[0]))
                                                                    @foreach($wallets as $wallet)
                                                                        <option
                                                                            value="{{$wallet->id}}"> {{$wallet->name}}
                                                                            ({{number_format($wallet->balance,2)}})
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mt-4">
                                                        <label>{{__('Coin Amount')}}</label>
                                                        <input name="amount" type="text" placeholder="{{__('Coin')}}"
                                                               class="form-control number_only">
                                                    </div>
                                                    <div class="form-group m-0">
                                                        <button class="btn theme-btn"
                                                                type="submit">{{__('Transfer')}}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-transfer-2" role="tabpanel"
                                         aria-labelledby="pills-transfer-2-tab">

                                        <div class="cp-user-card-header-area">
                                            <div class="cp-user-title">
                                                <h4>{{__('Transfer coin from your club wallet to main wallet')}}</h4>
                                            </div>
                                        </div>
                                        <div class="cp-user-profile-info">
                                            <form class="mt-4" method="POST" action="{{route('transferCoinToWallet')}}"
                                                  onsubmit="return submitResult();">
                                                @csrf

                                                <div class="form-group mt-4">
                                                    <label>{{__('Coin Amount')}}</label>
                                                    <input name="amount" type="text" placeholder="{{__('Coin')}}"
                                                           class="form-control">
                                                </div>
                                                <div class="form-group mt-4">
                                                    <label>{{__('Select Your Wallet')}}</label>
                                                    <div class="cp-select-area">
                                                        <select name="wallet_id" class="form-control" id="">
                                                            <option value="">{{__('Select')}}</option>
                                                            @if(isset($wallets[0]))
                                                                @foreach($wallets as $wallet)
                                                                    <option value="{{$wallet->id}}"> {{$wallet->name}}
                                                                        ({{number_format($wallet->balance,2)}})
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group m-0">
                                                    <button class="btn btn-info theme-btn"
                                                            type="submit">{{__('Transfer')}}</button>
                                                </div>
                                            </form>
                                            <div class="mt-1">
                                                <ul>
                                                    <li class="mt-4">
                                                        <h4 class=" text-warning">
                                                            <span
                                                                class="text-warning" style="font-weight: 700;">{{__('Warning : ')}}</span>{{__('If you return your coin before the end of your Plan period, you miss the exclusive bonus ')}}
                                                        </h4>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cp-user-card-header-area">
                                <div class="cp-user-title">
                                    <h4>{{__('Membership Plan Details')}}</h4>
                                </div>
                            </div>
                            @if(isset($plans[0]))
                                <div class="row">
                                    @foreach($plans as $plan)
                                        <div class="col-md-12 mt-4">
                                            <ul class="user-plan-table">
                                                <li class="user-t-img">
                                                    <img src="{{show_plan_image($plan->id,$plan->image)}}"
                                                         class="img-fluid cp-user-logo-large" alt="">
                                                </li>
                                                <li>
                                                    <h4>{{__('Plan Name  ')}} <span>{{$plan->plan_name}}</span></h4>
                                                </li>
                                                <li>
                                                    <h4>{{__('Minimum Amount  ')}}
                                                        <span>{{number_format($plan->amount,2)}} {{settings('coin_name')}}</span>
                                                    </h4>
                                                </li>
                                                <li>
                                                    <h4>{{__('Minimum Duration  ')}}
                                                        <span>{{$plan->duration}} {{__(' days')}}</span></h4>
                                                </li>
                                                <li>
                                                    <h4>{{__('Bonus Percentage ')}} <span>{{plan_bonus_percentage($plan->bonus_type,$plan->bonus,$plan->amount) }} %</span>
                                                    </h4>
                                                </li>
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function submitResult() {
            if (confirm("If you return your coin before the end of your Plan period, you miss the exclusive bonus, Are you sure you wish to transfer?") == false) {
                return false;
            } else {
                return true;
            }
        }
    </script>
@endsection
