@extends('layout.index')
@section('title', 'Trends Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">Transaction Trends</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Dashboard</a></li>
                        <li class="breadcrumb-item active">Inbound vs Return</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0 fw-bold">Commercial Activity Analysis</h5>
                        <p class="text-muted mb-0 small text-uppercase fw-medium">Monthly Inbound vs Outbound Performance
                        </p>
                    </div>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <span class="badge bg-soft-primary text-primary px-3 py-2"><i
                                class="mdi mdi-circle-medium me-1"></i> Inbound</span>
                        <span class="badge bg-soft-success text-success px-3 py-2"><i
                                class="mdi mdi-circle-medium me-1"></i> Outbound</span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="w-100">
                        <div id="customer_impression_charts" data-colors='["--vz-primary", "--vz-success"]'
                            class="apex-charts" dir="ltr"></div>
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
                            "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(e[0]) + "," + e[
                                1] + ")" :
                            t
                    });
                }
            }
        }

        var linechartcustomerColors = getChartColorsArray("customer_impression_charts");

        if (linechartcustomerColors) {
            var options = {
                series: [{
                        name: "Items Received (Inbound)",
                        type: "bar",
                        data: {!! json_encode($dataInbound) !!}
                    },
                    {
                        name: "Items Shipped (Outbound)",
                        type: "area",
                        data: {!! json_encode($dataOutbound) !!}
                    }
                ],
                chart: {
                    height: 450,
                    type: "line",
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    },
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.1
                    },
                },
                stroke: {
                    curve: "smooth",
                    width: [0, 3]
                },
                fill: {
                    type: ['solid', 'gradient'],
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [20, 100, 100, 100]
                    }
                },
                markers: {
                    size: [0, 5],
                    strokeWidth: 2,
                    hover: {
                        size: 7
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
                yaxis: {
                    title: {
                        text: 'Total Quantum',
                        style: {
                            color: '#adb5bd',
                            fontWeight: 500
                        }
                    }
                },
                grid: {
                    show: true,
                    borderColor: '#f1f1f1',
                    strokeDashArray: 3,
                    padding: {
                        top: 0,
                        right: 30,
                        bottom: 0,
                        left: 10
                    }
                },
                legend: {
                    show: false
                },
                plotOptions: {
                    bar: {
                        columnWidth: "25%",
                        borderRadius: 4
                    }
                },
                colors: linechartcustomerColors,
                tooltip: {
                    shared: true,
                    intersect: false,
                    theme: 'dark'
                }
            };

            var chart = new ApexCharts(document.querySelector("#customer_impression_charts"), options);
            chart.render();
        }
    </script>
@endsection
