@extends('layout.index')
@section('title', 'Stock Availability')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">Warehouse Inventory Health</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Dashboard</a></li>
                        <li class="breadcrumb-item active">Stock Availability</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <div class="card card-animate bg-primary bg-gradient border-0 shadow-lg overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 end-0 p-3 opacity-25">
                        <i class="mdi mdi-shopping-outline fs-64 text-white"></i>
                    </div>
                    <p class="text-white-50 text-uppercase fw-bold mb-3 fs-13">Current Available Stock</p>
                    <h1 class="text-white fw-bold mb-4 display-5 counter-value" data-target="{{ $stock }}">
                        {{ number_format($stock) }}</h1>

                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <p class="text-white-50 mb-0">Total units ready for dispatch across all storage areas.</p>
                        </div>
                    </div>

                    <a href="{{ route('inbound.inventory.index') }}" class="btn btn-light w-100 fw-bold py-2 shadow-sm">
                        Go to Inventory List <i class="mdi mdi-open-in-new ms-1 align-middle"></i>
                    </a>
                </div>
                <div class="progress progress-sm rounded-0 bg-white-subtle" style="height: 6px;">
                    <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="100"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0 fw-bold">Client Stock Distribution</h5>
                        <p class="text-muted mb-0 small text-uppercase">Analysis of Stock Allocation by Stakeholders</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge bg-success px-3 py-2"><i class="mdi mdi-chart-pie me-1"></i> Live Tracking</span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div id="bar_chart" data-colors='["--vz-success"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function getChartColorsArray(t) {
            if (null !== document.getElementById(t)) {
                return t = document.getElementById(t).getAttribute("data-colors"),
                    (t = JSON.parse(t)).map(function(t) {
                        var e = t.replace(" ", "");
                        return -1 === e.indexOf(",") ?
                            getComputedStyle(document.documentElement).getPropertyValue(e) || e :
                            2 === (t = t.split(",")).length ?
                            "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(t[0]) + "," + t[1] +
                            ")" :
                            e
                    })
            }
        }

        var chartBarColors = getChartColorsArray("bar_chart");

        if (chartBarColors) {
            var options = {
                chart: {
                    height: 380,
                    type: "bar",
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 6,
                        barHeight: '60%',
                        distributed: true
                    }
                },
                dataLabels: {
                    enabled: true,
                    textAnchor: 'start',
                    style: {
                        colors: ['#fff'],
                        fontWeight: 'bold'
                    },
                    formatter: function(val, opt) {
                        return val + " Units"
                    },
                    offsetX: 10,
                    dropShadow: {
                        enabled: true
                    }
                },
                series: [{
                    name: "Stock Level",
                    data: {!! json_encode($dataStock) !!}
                }],
                colors: chartBarColors,
                grid: {
                    borderColor: "#f1f1f1",
                    strokeDashArray: 3,
                    padding: {
                        left: 20,
                        right: 50,
                        top: 0,
                        bottom: 0
                    }
                },
                xaxis: {
                    categories: {!! json_encode($dataClient) !!},
                    labels: {
                        style: {
                            colors: '#74788d',
                            fontWeight: 500
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#74788d',
                            fontWeight: 500,
                            fontSize: '13px'
                        }
                    }
                },
                tooltip: {
                    theme: 'dark'
                }
            };

            var chart = new ApexCharts(document.querySelector("#bar_chart"), options);
            chart.render();
        }
    </script>
@endsection
