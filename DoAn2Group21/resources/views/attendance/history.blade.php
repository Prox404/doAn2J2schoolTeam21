@extends('layout.master')
@push('title')
    <title>Attendance</title>
@endpush
@push('css')
    {{-- css start --}}
    <link href="{{ asset('vendors/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/sl-1.4.0/datatables.min.css" />
    <link rel="stylesheet" href="{{ asset('vendors/choices.js/choices.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/pages/dripicons.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendors/dripicons/webfont.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendors/dripicons/webfont.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendors/chartjs/Chart.min.css') }}">
    {{-- css end --}}
@endpush
@php
use Illuminate\Support\Carbon;
@endphp
@section('content')
    <div class="page-content">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <section class="row justify-content-center">

            <div class="row">
                <div class="col-12 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon purple">
                                        <i class="fas fa-users text-light"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <button data-bs-toggle="modal"
                                    data-bs-target="#all-student-modal" style="text-align: left " class="btn btn-white p-0">
                                        <h6 class="text-muted font-semibold">Tổng số học sinh</h6>
                                        <h6 class="font-extrabold mb-0">{{ count($allStudent) }}</h6>
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon green">
                                        <i class="fas fa-smile text-light"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <button data-bs-toggle="modal"
                                    data-bs-target="#present-student-modal" style="text-align: left " class="btn btn-white p-0">
                                        <h6 class="text-muted font-semibold">Số học sinh đi học đầy đủ</h6>
                                        <h6 class="font-extrabold mb-0">{{ count($numberStudentPresentFullDay) }}</h6>
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon red">
                                        <i class="fas fa-frown text-light"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <button data-bs-toggle="modal"
                                    data-bs-target="#absent-student-modal" style="text-align: left " class="btn btn-white p-0">
                                        <h6 class="text-muted font-semibold">Số học sinh nghỉ quá 3 buổi</h6>
                                        <h6 class="font-extrabold mb-0">{{ count($numberStudentAbsentMoreThan3Sessions) }}
                                        </h6>
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Biểu đồ nè</h4>
                </div>
                <div class="card-body">
                    <canvas id="line-chart" width="800" height="350"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Số buổi học</h4>
                </div>
                <div class="card-body">
                    @foreach ($schedules as $schedule)
                        <div class="btn-group btn-group-sm mb-2" role="group">
                            @php
                                $date = new Carbon($schedule->date);
                                $now = Carbon::now();
                            @endphp
                            <a href="{{ $class->id }}/{{ $schedule->id }}"
                                class="btn 
                            @if ($date->gt($now)) btn-primary
                            @else
                                btn-light @endif
                            ">

                                @switch($date->isoFormat('E'))
                                    @case(1)
                                        Mon,
                                    @break

                                    @case(2)
                                        Tue,
                                    @break

                                    @case(3)
                                        Wed,
                                    @break

                                    @case(4)
                                        Thu,
                                    @break

                                    @case(5)
                                        Fri,
                                    @break

                                    @case(6)
                                        Sat,
                                    @break

                                    @case(7)
                                        Sun,
                                    @break

                                    @default
                                        Unknown,
                                @endswitch

                                {{ $schedule->date }}

                                @if (!isset(
                                    $schedule->attendance()->where('schedule_id', $schedule->id)->first()->status))
                                    <i title="Chưa điểm danh" class="fas fa-info-circle"></i>
                                @endif
                            </a>
                            <button type="button" class="btn"><i class="icon dripicons-cross"></i></button>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade text-left w-100" id="present-student-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel16">Danh sách học sinh đi học đầy đủ</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-lg">
                            <thead>
                                <tr>
                                    <th>Mã sinh viên</th>
                                    <th>Tên sinh viên</th>
                                    <th>Số buổi học</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($numberStudentPresentFullDay as $student)
                                    <tr>
                                        <td>{{ $student['id'] }}</td>
                                        <td>{{ $student['name'] }}</td>
                                        <td>{{ $student['present'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-left w-100" id="absent-student-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel16">Danh sách học sinh vắng học quá 3 buổi</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-lg">
                            <thead>
                                <tr>
                                    <th>Mã sinh viên</th>
                                    <th>Tên sinh viên</th>
                                    <th>Số buổi nghỉ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($numberStudentAbsentMoreThan3Sessions as $student)
                                    <tr>
                                        <td>{{ $student['id'] }}</td>
                                        <td>{{ $student['name'] }}</td>
                                        <td>{{ $student['absent'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-left w-100" id="all-student-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel16">Danh sách học sinh</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-lg">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allStudent as $key => $name)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ $name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

@stop
@push('js')
    {{-- js start --}}
    <script src="{{ asset('/js/pages/ui-chartjs.js') }}"></script>
    <script src="{{ asset('/vendors/chartjs/Chart.min.js') }}"></script>
    <script type="text/javascript">
        let labels = [
            @foreach ($line_chart_labels as $label)
                "{{ $label }}",
            @endforeach
        ];
        new Chart(document.getElementById("line-chart"), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        data: [{{ implode(' ,', $numberStudentPresent) }}],
                        label: "Có mặt",
                        backgroundColor: "rgba(50, 69, 209,.6)",
                        borderWidth: 3,
                        borderColor: '#0dcaf0',
                        fill: false
                    },
                    {
                        data: [{{ implode(' ,', $numberStudentAbsent) }}],
                        label: "Vắng mặt",
                        backgroundColor: "rgba(253, 183, 90,.6)",
                        borderWidth: 3,
                        borderColor: 'rgba(253, 183, 90,.6)',
                        fill: false
                    },
                    {
                        data: [{{ implode(' ,', $numberStudentOnLeave) }}],
                        label: "Có phép",
                        borderColor: "#ff5263",
                        fill: false
                    },
                ]
            },
            options: {
                title: {
                    display: false
                }
            }
        });
    </script>
    {{-- js end --}}
@endpush
