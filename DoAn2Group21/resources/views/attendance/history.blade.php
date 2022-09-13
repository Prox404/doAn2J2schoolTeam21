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
    <link href="{{ asset('css/pages/calendar.css') }}" rel="stylesheet" type="text/css" />
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
                                    <button data-bs-toggle="modal" data-bs-target="#all-student-modal"
                                        style="text-align: left " class="btn btn-white p-0">
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
                                    <button data-bs-toggle="modal" data-bs-target="#present-student-modal"
                                        style="text-align: left " class="btn btn-white p-0">
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
                                    <button data-bs-toggle="modal" data-bs-target="#absent-student-modal"
                                        style="text-align: left " class="btn btn-white p-0">
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

            @if($class->status == 3)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tổng kết nè bru</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-lg">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Đã học</th>
                                        <th>Điểm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allStudentPresent as $student)
                                        <tr>
                                            <td class="text-bold-500">{{$student['id']}}</td>
                                            <td class="text-bold-500">{{$student['name']}}</td>
                                            <td class="text-bold-500">{{$student['session']}}</td>
                                            <td class="text-bold-500">{{$student['score']}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            

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
                    <div class="badges mb-2">
                        <center>
                            <span class="badge bg-primary">Chưa học</span>
                            <span class="badge bg-success">Đã học</span>
                            <span class="badge bg-danger">Đã học nhưng chưa điểm danh</span>
                        </center>
                    </div>
                    <div id='calendar'></div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade text-left w-100" id="present-student-modal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel16" aria-hidden="true">
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
    <div class="modal fade text-left w-100" id="absent-student-modal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel16" aria-hidden="true">
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
    <div class="modal fade text-left w-100" id="all-student-modal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel16" aria-hidden="true">
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
    <script src="{{ asset('js/pages/calendar.js') }}"></script>
    <script src="{{ asset('js/pages/localest-all.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'title',
                    center: '',
                    right: 'today dayGridMonth,timeGridWeek,timeGridDay,listWeek prev,next'
                },
                themeSystem: 'Sandstone',
                initialView: 'timeGridWeek',
                eventRender: function(info) {
                    var tooltip = new Tooltip(info.el, {
                        title: info.event.extendedProps.description,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body'
                    });
                },
                events: [
                    @foreach ($schedules as $schedule)
                        @php
                            $add_date = 0;
                            if ($schedule->classes['shift'] == 1) {
                                $add_date = 7;
                            } else {
                                $add_date = 13;
                            }
                            $date = new Carbon($schedule->date);
                            $start = $date->addHours($add_date)->format('Y-m-d H:i:s');
                            $end = $date->addHours(2)->format('Y-m-d H:i:s');
                            $now = Carbon::now();
                        @endphp

                        {
                            title: '{{ $schedule->classes['name'] }}',
                            url: '{{ $class->id }}/{{ $schedule->id }}',
                            start: '{{ $start }}',
                            end: '{{ $end }}',
                            description: 'ALoo',
                            allDay: false,
                            textColor: '#fff',
                            color: @if ($date->gt($now))
                                '#435ebe'
                            @else
                                @if (!isset(
                                    $schedule->attendance()->where('schedule_id', $schedule->id)->first()->status))
                                    '#dc3545'
                                @else
                                    '#198754'
                                @endif
                            @endif
                        },
                    @endforeach
                ]
                
            });

            calendar.render();
        });
    </script>
    {{-- js end --}}
@endpush
