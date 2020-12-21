@extends('user.master',['menu'=>'dashboard'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <marquee class="marquee" direction=”right” onmouseover="stop()" onmouseout="start()">
                <ul>
                    @if(!empty($api_response))
                        @php
                            $counter = 1
                        @endphp
                        @foreach($api_response as $response)
                            @if($counter % 2 == 0)
                                <li><i class="fa fa-arrow-up mr-2 text-danger"></i> <span
                                        class="text-danger">{{$response->rate}}</span> {{$response->currency}}</li>
                            @else
                                <li><i class="fa fa-arrow-up mr-2 text-success"></i> <span
                                        class="text-success">{{$response->rate}}</span> {{$response->currency}}</li>
                            @endif
                            @php
                                $counter++
                            @endphp
                        @endforeach
                    @endif
                </ul>
            </marquee>
        </div>

    </div>
    <div class="row mt-4">
        <div class="col-xl-4 col-md-6 col-12 mb-xl-0 mb-4">
            <div class="card status-card status-card-bg-blue">
                <div class="card-body">
                    <div class="status-card-inner">
                        <div class="content">
                            <p>{{__('Available Coin')}}</p>
                            <h3>{{number_format($balance['available_coin'],2)}} </h3>
                        </div>
                        <div class="icon">
                            <img src="{{asset('assets/user/images/status-icons/money.svg')}}" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12 mb-xl-0 mb-4">
            <div class="card status-card status-card-bg-green">
                <div class="card-body">
                    <div class="status-card-inner">
                        <div class="content">
                            <p>{{__('Total Blocked Coin')}}</p>
                            <h3>{{number_format(get_blocked_coin(Auth::id()),2)}}</h3>
                        </div>
                        <div class="icon">
                            <img src="{{asset('assets/user/images/status-icons/funds.svg')}}" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
            <div class="card status-card status-card-bg-read">
                <div class="card-body">
                    <div class="status-card-inner">
                        <div class="content">
                            <p>{{__('Total Buy Coin')}}</p>
                            <h3>{{number_format($total_buy_coin,2)}}</h3>
                        </div>
                        <div class="icon">
                            <img src="{{asset('assets/user/images/status-icons/money.svg')}}" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- user chart -->
    <div class="row mt-4">
        <div class="col-xl-6">
            <div class="card cp-user-custom-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <div class="cp-user-title">
                            <h4>{{__('Withdrawal')}}</h4>
                        </div>
                    </div>
                    <p class="subtitle">{{__('Current Year')}}</p>
                    <canvas id="withdrawalChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card cp-user-custom-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <div class="cp-user-title">
                            <h4>{{__('Deposit')}}</h4>
                        </div>
                    </div>
                    <p class="subtitle">{{__('Current Year')}}</p>
                    <canvas id="depositChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-xl-12">
            <div class="card cp-user-custom-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <div class="cp-user-title">
                            <h4>{{__('Buy Coin Report')}}</h4>
                        </div>
                    </div>
                    <p class="subtitle">{{__('Current Year')}}</p>
                    <canvas id="myBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-xl-12">
            <div class="card cp-user-custom-card">
                <div class="card-body">
                    <div class="deposite-list-area">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="activity-area">
                                    <div class="activity-top-area">
                                        <div class="row align-items-center">
                                            <div class="col-lg-6">
                                                <div class="cp-user-card-header-area mb-0">
                                                    <div class="cp-user-title">
                                                        <h4>{{__('All Deposit List')}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="tabe-menu">
                                                    <ul class="nav dashboard-tabs" id="myTab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="deposit-tab"
                                                               data-toggle="tab"
                                                               onclick="$('#list_title').html('All Deposit List')"
                                                               href="#deposit" role="tab" aria-controls="deposit"
                                                               aria-selected="true"> Deposit</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link profile" id="withdraw-tab"
                                                               data-toggle="tab"
                                                               onclick="$('#list_title').html('All Withdrawal List')"
                                                               href="#withdraw" role="tab" aria-controls="withdraw"
                                                               aria-selected="false"> Withdraw</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="activity-list">
                                                <div class="tab-content">
                                                    <div id="deposit" class="tab-pane fade in active show text-center">

                                                        <div class="cp-user-transaction-history-table">
                                                            <table class="table table-responsive" id="table">
                                                                <thead>
                                                                <tr>
                                                                    <th>{{__('Address')}}</th>
                                                                    <th>{{__('Amount')}}</th>
                                                                    <th>{{__('Transaction Hash')}}</th>
                                                                    <th>{{__('Status')}}</th>
                                                                    <th>{{__('Created At')}}</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                    <div id="withdraw" class="tab-pane fade in  text-center">

                                                    <!-- withdraw_table -->

                                                    <div class="cp-user-transaction-history-table">
                                                            <table class="table table-responsive" id="withdraw_table">
                                                                <thead>
                                                                <tr>
                                                                    <th>{{__('Address')}}</th>
                                                                    <th>{{__('Amount')}}</th>
                                                                    <th>{{__('Transaction Hash')}}</th>
                                                                    <th>{{__('Status')}}</th>
                                                                    <th>{{__('Created At')}}</th>
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
            <script src="{{asset('assets/chart/chart.min.js')}}"></script>
            <script src="{{asset('assets/chart/anychart-base.min.js')}}"></script>
            <!-- Resources -->
            <script src="{{asset('assets/chart/amchart.core.js')}}"></script>
            <script src="{{asset('assets/chart/amchart.charts.js')}}"></script>
            <script src="{{asset('assets/chart/amchart.animated.js')}}"></script>
            <script>
                anychart.onDocumentReady(function () {
                    var chart = anychart.pie([
                        {x: "Complete", value: {!! $completed_withdraw !!}},
                        {x: "Pending", value: {!! $pending_withdraw !!}},

                    ]);

                    chart.innerRadius("60%");

                    var label = anychart.standalones.label();
                    label.text({!! json_encode($pending_withdraw) !!});
                    label.width("100%");
                    label.height("100%");
                    label.adjustFontSize(true);
                    label.fontColor("#60727b");
                    label.hAlign("center");
                    label.vAlign("middle");

                    // set the label as the center content
                    chart.center().content(label);

                    //  chart.title("Donut Chart: Label in the center");
                    chart.container('circle');
                    chart.draw();
                });
            </script>
            <script>
                am4core.ready(function () {

// Themes begin
                    am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
                    var chart = am4core.create("container", am4charts.XYChart);

// Add percent sign to all numbers
                    //chart.numberFormatter.numberFormat = "#.3";

// Add data
                    chart.data = {!! json_encode($sixmonth_diposites) !!};

// Create axes
                    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                    categoryAxis.dataFields.category = "country";
                    categoryAxis.renderer.grid.template.location = 0;
                    categoryAxis.renderer.minGridDistance = 30;

                    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                    valueAxis.title.text = "Deposit and withdraw ";
                    valueAxis.title.fontWeight = 800;

// Create series
                    var series = chart.series.push(new am4charts.ColumnSeries());
                    series.dataFields.valueY = "year2004";
                    series.dataFields.categoryX = "country";
                    series.clustered = false;
                    series.tooltipText = "Deposit {categoryX}: [bold]{valueY}[/]";

                    var series2 = chart.series.push(new am4charts.ColumnSeries());
                    series2.dataFields.valueY = "year2005";
                    series2.dataFields.categoryX = "country";
                    series2.clustered = false;
                    series2.columns.template.width = am4core.percent(50);
                    series2.tooltipText = "Withdraw {categoryX}: [bold]{valueY}[/]";

                    chart.cursor = new am4charts.XYCursor();
                    chart.cursor.lineX.disabled = true;
                    chart.cursor.lineY.disabled = true;

                }); // end am4core.ready()
            </script>

            <script>
                $(document).ready(function () {
                    $('#withdraw_table').DataTable({
                        processing: true,
                        serverSide: true,
                        pageLength: 10,
                        bLengthChange: true,
                        responsive: false,
                        ajax: '{{route('transactionHistories')}}?type=withdraw',
                        order: [4, 'desc'],
                        autoWidth: false,
                        language: {
                            paginate: {
                                next: 'Next &#8250;',
                                previous: '&#8249; Previous'
                            }
                        },
                        columns: [
                            {"data": "address", "orderable": false},
                            {"data": "amount", "orderable": false},
                            {"data": "hashKey", "orderable": false},
                            {"data": "status", "orderable": false},
                            {"data": "created_at", "orderable": false}
                        ],
                    });
                });
            </script>

            <script>
                $(document).ready(function () {
                    $('#table').DataTable({
                        processing: true,
                        serverSide: true,
                        pageLength: 10,
                        retrieve: true,
                        bLengthChange: true,
                        responsive: false,
                        ajax: '{{route('transactionHistories')}}?type=deposit',
                        order: [4, 'desc'],
                        autoWidth: false,
                        language: {
                            paginate: {
                                next: 'Next &#8250;',
                                previous: '&#8249; Previous'
                            }
                        },
                        columns: [
                            {"data": "address", "orderable": false},
                            {"data": "amount", "orderable": false},
                            {"data": "hashKey", "orderable": false},
                            {"data": "status", "orderable": false},
                            {"data": "created_at", "orderable": false}
                        ],
                    });
                });
            </script>
            <script>
                var ctx = document.getElementById('depositChart').getContext("2d")
                var depositChart = new Chart(ctx, {
                    type: 'line',
                    yaxisname: "Monthly Deposit",

                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug", "Sep", "Oct", "Nov", "Dec"],
                        datasets: [{
                            label: "Monthly Deposit",
                            borderColor: "#1cf676",
                            pointBorderColor: "#1cf676",
                            pointBackgroundColor: "#1cf676",
                            pointHoverBackgroundColor: "#1cf676",
                            pointHoverBorderColor: "#D1D1D1",
                            pointBorderWidth: 4,
                            pointHoverRadius: 2,
                            pointHoverBorderWidth: 1,
                            pointRadius: 3,
                            fill: false,
                            borderWidth: 3,
                            data: {!! json_encode($monthly_deposit) !!}
                        }]
                    },
                    options: {
                        legend: {
                            position: "bottom",
                            display: true,
                            labels: {
                                fontColor: '#928F8F'
                            }
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    fontColor: "#928F8F",
                                    fontStyle: "bold",
                                    beginAtZero: true,
                                    // maxTicksLimit: 5,
                                    padding: 20
                                },
                                gridLines: {
                                    drawTicks: false,
                                    display: false
                                }
                            }],
                            xAxes: [{
                                gridLines: {
                                    zeroLineColor: "transparent",
                                    drawTicks: false,
                                    display: false
                                },
                                ticks: {
                                    padding: 20,
                                    fontColor: "#928F8F",
                                    fontStyle: "bold"
                                }
                            }]
                        }
                    }
                });
            </script>
            <script>
                var ctx = document.getElementById('withdrawalChart').getContext("2d");
                var withdrawalChart = new Chart(ctx, {
                    type: 'line',
                    yaxisname: "Monthly Withdrawal",

                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug", "Sep", "Oct", "Nov", "Dec"],
                        datasets: [{
                            label: "Monthly Withdrawal",
                            borderColor: "#f691be",
                            pointBorderColor: "#f691be",
                            pointBackgroundColor: "#f691be",
                            pointHoverBackgroundColor: "#f691be",
                            pointHoverBorderColor: "#D1D1D1",
                            pointBorderWidth: 4,
                            pointHoverRadius: 2,
                            pointHoverBorderWidth: 1,
                            pointRadius: 3,
                            fill: false,
                            borderWidth: 3,
                            data: {!! json_encode($monthly_withdrawal) !!}
                        }]
                    },
                    options: {
                        legend: {
                            position: "bottom",
                            display: true,
                            labels: {
                                fontColor: '#928F8F'
                            }
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    fontColor: "#928F8F",
                                    fontStyle: "bold",
                                    beginAtZero: false,
                                    // maxTicksLimit: 5,
                                    // padding: 20,
                                    // max: 1000
                                },
                                gridLines: {
                                    drawTicks: false,
                                    display: false
                                }
                            }],
                            xAxes: [{
                                gridLines: {
                                    zeroLineColor: "transparent",
                                    drawTicks: true,
                                    display: false
                                },
                                ticks: {
                                    // padding: 20,
                                    fontColor: "#928F8F",
                                    fontStyle: "bold",
                                    // max: 10000,
                                    autoSkip: false
                                }
                            }]
                        }
                    }
                });
            </script>
            <script>
                var ctx = document.getElementById('myBarChart').getContext("2d")
                var myBarChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug", "Sep", "Oct", "Nov", "Dec"],
                        datasets: [{
                            label: "Monthly Buy Coin ",
                            backgroundColor: "#007bff",
                            borderColor: "#3865f6",
                            pointBorderColor: "#3865f6",
                            pointBackgroundColor: "#3865f6",
                            pointHoverBackgroundColor: "#3865f6",
                            pointHoverBorderColor: "#D1D1D1",
                            pointBorderWidth: 10,
                            pointHoverRadius: 10,
                            pointHoverBorderWidth: 1,
                            pointRadius: 3,
                            fill: true,
                            borderWidth: 1,
                            data: {!! json_encode($monthly_buy_coin) !!}
                        }]
                    },
                    options: {
                        legend: {
                            position: "bottom",
                            display: true,
                            labels: {
                                fontColor: '#928F8F'
                            }
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    fontColor: "#928F8F",
                                    fontStyle: "bold",
                                    beginAtZero: true,
                                    maxTicksLimit: 5,
                                    padding: 20
                                },
                                gridLines: {
                                    drawTicks: false,
                                    display: false
                                }
                            }],
                            xAxes: [{
                                gridLines: {
                                    zeroLineColor: "#3865f6"
                                },
                                ticks: {
                                    padding: 20,
                                    fontColor: "#928F8F",
                                    fontStyle: "bold"
                                }
                            }]
                        }
                    }
                });
            </script>
@endsection
