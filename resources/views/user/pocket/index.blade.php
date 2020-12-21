@extends('user.master',['menu'=>'pocket'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card cp-user-custom-card cp-user-wallet-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <div class="cp-user-title">
                            <h4>{{__('My Pocket')}}</h4>
                        </div>
                        <div class="buttons">
                            <button class="btn cp-user-add-pocket" data-toggle="modal" data-target="#add-pocket">{{__('Add Pocket')}}</button>
{{--                            <button class="btn cp-user-move-coin">Move Coin</button>--}}
                        </div>
                    </div>
                    <div class="cp-user-wallet-table table-responsive">
                        <table class="table table-borderless cp-user-custom-table" width="100%">
                            <thead>
                            <tr>
                                <th class="all">{{__('Name')}}</th>
                                <th class="desktop">{{__('Balance')}}</th>
                                <th class="desktop">{{__('Referral Balance')}}</th>
                                <th class="desktop">{{__('Updated At')}}</th>
                                <th class="all">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($wallets[0]))
                                @foreach($wallets as $wallet)
                                    <tr>
                                        <td>{{ $wallet->name }}</td>
                                        <td>{{ $wallet->balance }}</td>
                                        <td>{{ $wallet->referral_balance }}</td>
                                        <td>{{ $wallet->updated_at }}</td>
                                        <td>
                                            <ul class="d-flex justify-content-center align-items-center">
                                                @if($wallet->is_primary == 0)
                                                    <li>
                                                        <a title="{{__('Make primary')}}" href="{{route('makeDefaultAccount',$wallet->id)}}">
                                                            <img src="{{asset('assets/user/images/wallet-table-icons/Key.svg')}}" class="img-fluid" alt="">
                                                        </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a title="{{__('Deposit')}}" href="{{route('walletDetails',$wallet->id)}}?q=deposit">
                                                        <img src="{{asset('assets/user/images/wallet-table-icons/wallet.svg')}}" class="img-fluid" alt="">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a title="{{__('withdraw')}}" href="{{route('walletDetails',$wallet->id)}}?q=withdraw">
                                                        <img src="{{asset('assets/user/images/wallet-table-icons/send.svg')}}" class="img-fluid" alt="">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a title="{{__('Activity log')}}" href="{{route('walletDetails',$wallet->id)}}?q=activity">
                                                        <img src="{{asset('assets/user/images/wallet-table-icons/share.svg')}}" class="img-fluid" alt="">
                                                    </a>
                                                </li>

                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- move coin modal -->
    <div class="modal fade cp-user-move-coin-modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="images/close.svg" class="img-fluid" alt="">
                </button>
                <div class="text-center">
                    <img src="images/cp-user-move-coin-modal-img.svg" class="img-fluid img-vector" alt="">
                    <h3>Want  To  Move  Coin ?</h3>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label>From</label>
                            <div class="cp-user-select-area">
                                <select class="form-control">
                                    <option>Select Reciever Account</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>To</label>
                            <div class="cp-user-select-area">
                                <select class="form-control">
                                    <option>Select Reciever Account</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="text" class="form-control" placeholder="Enter Coin Amount . . .">
                        </div>
                    </form>
                    <button type="button" class="btn btn-block cp-user-move-btn">Move Coin</button>
                </div>
            </div>
        </div>
    </div>
    <!-- move coin modal -->


    <!-- add pocket modal -->
    <div class="modal fade cp-user-move-coin-modal" id="add-pocket" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{asset('assets/user/images/close.svg')}}" class="img-fluid" alt="">
                </button>
                <div class="text-center">
                    <img src="{{asset('assets/user/images/add-pockaet-vector.svg')}}" class="img-fluid img-vector" alt="">
                    <h3>{{__('Want To Add New Pocket?')}}</h3>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('createWallet')}}" id="walletCreateForm">
                        @csrf
                        <div class="form-group">
                            <label>{{__('Pocket Name')}}</label>
                            <input type="text" name="wallet_name" class="form-control" placeholder="{{__('Write Your Pocket Name')}}">
                        </div>
                        <button type="submit" class="btn btn-block cp-user-move-btn">{{__('Add Pocket')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- move coin modal -->




@endsection

@section('script')

@endsection
