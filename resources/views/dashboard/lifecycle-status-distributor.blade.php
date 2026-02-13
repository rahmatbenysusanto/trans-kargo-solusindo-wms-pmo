@extends('layout.index')
@section('title', 'Lifecycle Status Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">Asset Lifecycle Health</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Dashboard</a></li>
                        <li class="breadcrumb-item active">Lifecycle Status</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate border-0 border-start border-success border-4 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-success text-success rounded-circle fs-3">
                                        <i class="mdi mdi-checkbox-marked-circle-outline"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold text-muted mb-0 fs-12">Active Assets</p>
                                    <h4 class="mb-0 fw-bold counter-value" data-target="{{ $data->active }}">
                                        {{ number_format($data->active) }}</h4>
                                </div>
                            </div>
                            <div class="alert alert-success border-0 px-2 py-1 mb-0 small">
                                <i class="mdi mdi-information-outline me-1"></i> Support > 6 months.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate border-0 border-start border-warning border-4 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-warning text-warning rounded-circle fs-3">
                                        <i class="mdi mdi-alert-circle-outline"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold text-muted mb-0 fs-12">Near EOS</p>
                                    <h4 class="mb-0 fw-bold counter-value" data-target="{{ $data->near_eos }}">
                                        {{ number_format($data->near_eos) }}</h4>
                                </div>
                            </div>
                            <div class="alert alert-warning border-0 px-2 py-1 mb-0 small text-warning">
                                <i class="mdi mdi-alert-outline me-1"></i> Planning required (≤ 6m).
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate border-0 border-start border-danger border-4 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-danger text-danger rounded-circle fs-3">
                                        <i class="mdi mdi-close-circle-outline"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold text-muted mb-0 fs-12">EOL / EOS reached</p>
                                    <h4 class="mb-0 fw-bold counter-value" data-target="{{ $data->eos }}">
                                        {{ number_format($data->eos) }}</h4>
                                </div>
                            </div>
                            <div class="alert alert-danger border-0 px-2 py-1 mb-0 small text-danger">
                                <i class="mdi mdi-skull-outline me-1"></i> Critical Replacement.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate border-0 border-start border-dark border-4 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-dark text-dark rounded-circle fs-3">
                                        <i class="mdi mdi-help-circle-outline"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold text-muted mb-0 fs-12">Unknown Status</p>
                                    <h4 class="mb-0 fw-bold counter-value" data-target="{{ $data->unknown }}">
                                        {{ number_format($data->unknown) }}</h4>
                                </div>
                            </div>
                            <div class="alert alert-dark border-0 px-2 py-1 mb-0 small text-dark">
                                <i class="ri-database-2-line me-1"></i> Data missing date info.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Lifecycle Distribution</h5>
                </div>
                <div class="card-body py-4">
                    <div id="simple_pie_chart" data-colors='["--vz-success", "--vz-warning", "--vz-danger", "--vz-dark"]'
                        class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card bg-primary bg-gradient border-0">
                <div class="card-body p-4 text-white">
                    <h5 class="text-white fw-bold mb-3">Lifecycle Insight</h5>
                    <p class="mb-4 opacity-75">Sistem memantau tanggal *End of Support* (EOS) setiap perangkat untuk
                        memastikan operasional tetap berjalan tanpa hambatan teknis akibat perangkat yang sudah usang.</p>
                    <div class="d-flex gap-3">
                        <div class="flex-grow-1">
                            <div class="p-3 bg-white bg-opacity-10 rounded">
                                <h6 class="text-white mb-2 small fw-bold">Kategori EOS</h6>
                                <p class="mb-0 small opacity-75">Near EOS didefinisikan sebagai perangkat dengan sisa waktu
                                    dukungan kurang dari 6 bulan.</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('assetLifecycle.index') }}" class="btn btn-outline-light btn-sm mt-2">Daftar
                                Lengkap <i class="ri-arrow-right-line ms-1"></i></a>
                        </div>
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

        var chartPieBasicColors = getChartColorsArray("simple_pie_chart");

        if (chartPieBasicColors) {
            var options = {
                series: {!! json_encode($chart) !!},
                chart: {
                    height: 320,
                    type: "donut"
                },
                labels: ["Active Status", "Near EOS Planning", "EOS Replacement", "Data Unknown"],
                stroke: {
                    show: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Devices',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: "bottom",
                    itemMargin: {
                        horizontal: 5,
                        vertical: 10
                    }
                },
                dataLabels: {
                    enabled: false
                },
                colors: chartPieBasicColors
            };

            var chart = new ApexCharts(document.querySelector("#simple_pie_chart"), options);
            chart.render();
        }
    </script>
@endsection
