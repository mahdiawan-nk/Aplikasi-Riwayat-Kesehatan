@extends('layouts.app')
@section('style')
    <link href="{{ asset('') }}assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection

@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4">
                <div class="col">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Karyawan</p>
                                    <h4 class="my-1">4805</h4>
                                </div>
                                <div class="widgets-icons bg-light-info text-info ms-auto"><i class='bx bxs-group'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">High Score Kardiovaskuler</p>
                                    <h4 class="my-1">8</h4>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto"><i class='bx bxs-group'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Medium Score Kardiovaskuler</p>
                                    <h4 class="my-1">59</h4>
                                </div>
                                <div class="widgets-icons bg-light-warning text-warning ms-auto"><i
                                        class='bx bxs-binoculars'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Low Score Kardiovaskuler</p>
                                    <h4 class="my-1">59</h4>
                                </div>
                                <div class="widgets-icons bg-light-success text-success ms-auto"><i
                                        class='bx bxs-binoculars'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
            {{-- <div class="row row-cols-1 row-cols-xl-2">
                <div class="col d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="mb-1">Grafik Score Kardiovaskuler</h5>
                                </div>
                                <div class="font-22 ms-auto"><i class='bx bx-dots-horizontal-rounded'></i>
                                </div>
                            </div>
                            <div class="row row-cols-1 row-cols-sm-3 mt-4">
                                <div class="col">
                                    <div class="">
                                        <p class="mb-0 text-danger">High Risk</p>
                                        <h4 class="my-1 text-danger">4</h4>
                                    </div>
                                </div>
                                <div class="col">
                                    <div>
                                        <p class="mb-0 text-warning">Medium Risk</p>
                                        <h4 class="my-1 text-warning">8</h4>
                                    </div>
                                </div>
                                <div class="col">
                                    <div>
                                        <p class="mb-0 text-success">Low Riks</p>
                                        <h4 class="my-1 text-success">5</h4>
                                    </div>
                                </div>
                            </div>
                            <div id="chart4"></div>
                        </div>
                    </div>
                </div>
                <div class="col d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="mb-0">Top Categories</h5>
                                </div>
                                <div class="font-22 ms-auto"><i class='bx bx-dots-horizontal-rounded'></i>
                                </div>
                            </div>
                            <div class="mt-5" id="chart15"></div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                                Kids <span class="badge bg-success rounded-pill">25</span>
                            </li>
                            <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                                Women <span class="badge bg-danger rounded-pill">10</span>
                            </li>
                            <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">Men
                                <span class="badge bg-primary rounded-pill">65</span>
                            </li>
                            <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                                Furniture <span class="badge bg-warning text-dark rounded-pill">14</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div> --}}
            <!--end row-->
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('') }}assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>

    <script>
        e = {
            series: [{
                name: "Total Sales",
                data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
            }, {
                name: "Customers",
                data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
            }, {
                name: "Store Visitores",
                data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
            }],
            chart: {
                foreColor: "#9ba7b2",
                type: "bar",
                height: 300,
                toolbar: {
                    show: !1
                }
            },
            plotOptions: {
                bar: {
                    horizontal: !1,
                    columnWidth: "55%",
                    endingShape: "rounded"
                }
            },
            dataLabels: {
                enabled: !1
            },
            stroke: {
                show: !0,
                width: 2,
                colors: ["transparent"]
            },
            colors: ["#0dcaf0", "#0d6efd", "#e5e7e8"],
            xaxis: {
                categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct"]
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(e) {
                        return "$ " + e + " thousands"
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#chart4"), e).render();
        e = {
            series: [25, 65, 10, 14],
            chart: {
                height: 240,
                type: "donut"
            },
            legend: {
                position: "bottom",
                show: !1
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: "80%"
                    }
                }
            },
            colors: ["#17a00e", "#0d6efd", "#f41127", "#ffc107"],
            dataLabels: {
                enabled: !1
            },
            labels: ["Kids", "Men", "Women", "Furniture"],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 200
                    },
                    legend: {
                        position: "bottom"
                    }
                }
            }]
        };
        new ApexCharts(document.querySelector("#chart15"), e).render();

        const init = () => {
            // fetchData()

        }

        init()
    </script>
@endsection
