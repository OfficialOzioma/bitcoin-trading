@extends('user.master',['menu'=>'referral'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card cp-user-custom-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area cp-user-card-header-bb">
                        <h4>{{__('Invite Your Contact')}}</h4>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="cp-user-referral-content">
                                <div class="form-group">
                                    <label>{{__('Share This Link to Your Contact')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button onclick="CopyUrl()" type="button" class="btn copy-url-btn">{{__('Copy URL')}}</button>
                                        </div>
                                        <input type="url" class="form-control" id="url" value="{{ $url }}" readonly>
                                    </div>
                                </div>
                                <div class="cp-user-content-bottom">
                                    <span class="or">or</span>
                                    <h5 class="cp-user-share-title">{{__('Share Your Code On')}}</h5>
                                    <div class="cp-user-share-buttons">
                                        <ul>
                                            <li><a class="fb" href="https://www.facebook.com/sharer/sharer.php?u={{$url}}" target="_blank"><i class="fa fa-facebook"></i> {{__('Facebook')}}</a></li>
                                            <li><a class="twit" target="_blank" href="http://www.twitter.com/share?url={{$url}}"><i class="fa fa-twitter"></i> {{__('Twitter')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card cp-user-custom-card mt-5">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <h4>{{__('My Referrals')}}</h4>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="cp-user-myreferral">
                                <div class="table-responsive">
                                    <table class="table dataTable cp-user-custom-table table-borderless text-center" width="100%">
                                        <thead>
                                        <tr>
                                            @for($i = 1; $i <= 3; $i++)
                                                <th class="referral-level" rowspan="1" colspan="1" aria-label="{{__('Level'). ' '. $i }}">
                                                    {{__('Level'). ' '. $i }}
                                                </th>
                                            @endfor
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="" role="" class="odd">
                                            @for($i = 1; $i <= 3; $i++)
                                                <td>{{$referralLevel[$i]}}</td>
                                            @endfor
                                        </tr>
                                        <tr>
                                            <td colspan="{{$max_referral_level}}"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card cp-user-custom-card mt-5">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <h4>{{__('My References')}}</h4>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="cp-user-myreferral">
                                <div class="table-responsive">
                                    <table class="table dataTable cp-user-custom-table table-borderless text-center" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="">{{ __('Full Name') }}</th>
                                                <th class="">{{ __('Email') }}</th>
                                                <th class="">{{ __('Level') }}</th>
                                                <th class="">{{ __('Joining Date') }}</th>
                                                <th class="">{{ __('Balance') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($referrals))
                                                @foreach($referrals as $data)
                                                    <tr>
                                                        <td>{{ $data['full_name'] }}</td>
                                                        <td class="email-case">{{ $data['email'] }}</td>
                                                        <td>{{ $data['level'] }}</td>
                                                        <td>{{ $data['joining_date'] }}</td>
                                                        <td>{{ user_balance($data['id']) }}</td>
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
            </div>

            <div class="card cp-user-custom-card mt-5">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <h4>{{__('My Earnings')}}</h4>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="cp-user-myreferral">
                                <div class="table-responsive">
                                    <table class="table dataTable cp-user-custom-table table-borderless text-center" width="100%">
                                        <thead>
                                            <tr>
                                                <th>{{__('Period')}}</th>
                                                <th>{{__('Commissions')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total = 0;
                                            @endphp
                                            @if(!empty($monthArray))

                                                @foreach($monthArray as $key=> $month)
                                                    <tr>
                                                        @php
                                                            $referral_bonus = isset($monthlyEarningHistories[$key]['total_amount']) ? $monthlyEarningHistories[$key]['total_amount'] : 0;
                                                        @endphp
                                                        <td>{{date('M Y', strtotime($key))}}</td>
                                                        <td>
                                                            {{ visual_number_format($referral_bonus) }}
                                                            {{settings('coin_name')}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center"><b>{{__('No Data available')}}</b></td>
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
@endsection

@section('script')
    <script>
        function copy_clipboard() {
            var copyText = document.getElementById("myInput");
            copyText.select();
            document.execCommand("Copy");
        }
    </script>
    <script>
        function CopyUrl() {

            /* Get the text field */
            var copyText = document.getElementById("url");

            /* Select the text field */
            copyText.select();

            /* Copy the text inside the text field */
            document.execCommand("copy");
            VanillaToasts.create({
                // title: 'Message Title',
                text: '{{__('URL copied successfully')}}',
                type: 'success',
                timeout: 3000

            });
        }
    </script>
@endsection
