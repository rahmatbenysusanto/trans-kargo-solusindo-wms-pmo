@extends('layout.index')
@section('title', 'Dashboard Stock Availability')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Stock Availability</h4>
            </div>
        </div>

        <div class="col-4">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Available Stock</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $stock }}">{{ number_format($stock) }}</span></h4>
                            <a href="#" class="text-decoration-underline">View all stock</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info rounded fs-3">
                                <i class="bx bx-shopping-bag"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Client Stock Availability</h4>
                </div>

                <div class="card-body">
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
                                "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(t[0]) + "," + t[1] + ")" :
                                e
                    })
            }
        }

        var chartBarColors = getChartColorsArray("bar_chart");

        if (chartBarColors) {
            options = {
                chart: {
                    height: 350,
                    type: "bar",
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    name: "Stock",
                    data: {!! json_encode($dataStock) !!}
                }],
                colors: chartBarColors,
                grid: {
                    borderColor: "#f1f1f1"
                },
                xaxis: {
                    categories: {!! json_encode($dataClient) !!}
                }
            };

            let chart = new ApexCharts(document.querySelector("#bar_chart"), options);
            chart.render();
        }
    </script>
@endsection
