@extends('layout.index')
@section('title', 'Dashboard Inbound vs Return Trend')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Inbound vs Return Trend</h4>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Inbound vs Outbound/Return</h4>
                </div>

                <div class="card-body p-0 pb-2">
                    <div class="w-100">
                        <div id="customer_impression_charts" data-colors='["--vz-primary", "--vz-success"]' class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function getChartColorsArray(e) {
            if (null !== document.getElementById(e)) {
                var t = document.getElementById(e).getAttribute("data-colors");
                if (t) {
                    return (t = JSON.parse(t)).map(function(e) {
                        var t = e.replace(" ", "");
                        return -1 === t.indexOf(",") ?
                            getComputedStyle(document.documentElement).getPropertyValue(t) || t :
                            2 == (e = e.split(",")).length ?
                                "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(e[0]) + "," + e[1] + ")" :
                                t
                    });
                }
                console.warn("data-colors atributes not found on", e);
            }
        }

        var linechartcustomerColors = getChartColorsArray("customer_impression_charts");

        if (linechartcustomerColors) {
            options = {
                series: [
                    {
                        name: "Inbound",
                        type: "bar",
                        data: {!! json_encode($dataInbound) !!}
                    },
                    {
                        name: "Outbound",
                        type: "area",
                        data: {!! json_encode($dataOutbound) !!}
                    }
                ],
                chart: {
                    height: 500,
                    type: "line",
                    toolbar: {
                        show: false
                    }
                },
                stroke: {
                    curve: "straight",
                    dashArray: [0, 0, 8],
                    width: [2, 0, 2.2]
                },
                fill: {
                    opacity: [0.9, 0.1]
                },
                markers: {
                    size: [0, 0, 0],
                    strokeWidth: 2,
                    hover: {
                        size: 4
                    }
                },
                xaxis: {
                    categories: {!! json_encode($dataMonths) !!},
                    axisTicks: {
                        show: false
                    },
                    axisBorder: {
                        show: false
                    }
                },
                grid: {
                    show: true,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    },
                    padding: {
                        top: 0,
                        right: -2,
                        bottom: 15,
                        left: 10
                    }
                },
                legend: {
                    show: true,
                    horizontalAlign: "center",
                    offsetX: 0,
                    offsetY: -5,
                    markers: {
                        width: 9,
                        height: 9,
                        radius: 6
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 0
                    }
                },
                plotOptions: {
                    bar: {
                        columnWidth: "30%",
                        barHeight: "70%"
                    }
                },
                colors: linechartcustomerColors,
                tooltip: {
                    shared: true,
                    y: [
                        {
                            formatter: function(e) {
                                return void 0 !== e ? e.toFixed(0) : e
                            }
                        },
                        {
                            formatter: function(e) {
                                return void 0 !== e ? e.toFixed(0) : e
                            }
                        },
                    ]
                }
            };

            var chart = new ApexCharts(document.querySelector("#customer_impression_charts"), options);
            chart.render();
        }
    </script>
@endsection
