@extends('admin.__layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    {{-- <h1>Simple Tables</h1> --}}
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            {{-- <h3 class="card-title">Bordered Table</h3> --}}
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="chart-total-sales-amount-grouped-by-status-and-month-this-year"></div>
                        </div>
                        <!-- /.card-body -->

                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            {{-- <h3 class="card-title">Bordered Table</h3> --}}
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="chart-total-sales-amount-this-year"></div>
                        </div>
                        <!-- /.card-body -->

                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            {{-- <h3 class="card-title">Bordered Table</h3> --}}
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="chart-total-number-of-order-this-year"></div>
                        </div>
                        <!-- /.card-body -->

                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            {{-- <h3 class="card-title">Bordered Table</h3> --}}
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="chart-active-inactive"></div>
                        </div>
                        <!-- /.card-body -->

                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@push('before_scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    {{-- charts user --}}
    <script>
        Highcharts.chart('chart-active-inactive', {
            chart: {
                type: 'column'
            },
            title: {
                text: '{{ $userActiveVsInactive['title'] }}'
            },
            xAxis: {
                categories: ['Number of Users'],
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Number of Users'
                }
            },
            tooltip: {
                valueSuffix: ' users'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: {!! json_encode($userActiveVsInactive['data']) !!}
        });

        Highcharts.chart('chart-total-number-of-order-this-year', {
            chart: {
                type: 'column'
            },
            title: {
                text: '{{ $totalNumberOfOrderThisYear['title'] }}'
            },
            xAxis: {
                categories: ['Number of Orders'],
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Number of Orders'
                }
            },
            tooltip: {
                valueSuffix: ' orders'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: {!! json_encode($totalNumberOfOrderThisYear['data']) !!}
        });

        Highcharts.chart('chart-total-sales-amount-this-year', {
            chart: {
                type: 'column'
            },
            title: {
                text: '{{ $totalSalesAmountThisYear['title'] }}'
            },
            xAxis: {
                categories: ['Total Sales Amount'],
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total Sales Amount'
                }
            },
            tooltip: {
                valueSuffix: ' Rp'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: {!! json_encode($totalSalesAmountThisYear['data']) !!}
        });

        Highcharts.chart('chart-total-sales-amount-grouped-by-status-and-month-this-year', {
            chart: {
                type: 'column'
            },
            title: {
                text: '{{ $totalSalesAmountGroupedByStatus['title'] }}'
            },
            xAxis: {
                categories: {!! json_encode($totalSalesAmountGroupedByStatus['categories']) !!},
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total Sales Amount'
                }
            },
            tooltip: {
                valueSuffix: ' Rp'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: {!! json_encode($totalSalesAmountGroupedByStatus['data']) !!}
        });



    </script>
@endpush