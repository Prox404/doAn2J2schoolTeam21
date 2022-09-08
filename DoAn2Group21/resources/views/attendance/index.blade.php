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
        @if (auth()->user()->level == 3 || auth()->user()->level == 4 || auth()->user()->level == 2)
            <section class="row">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Quản lý môn học</h4>
                    </div>
                    <div class="card-body">
                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Class Name</th>
                                    <th>Subject Name</th>
                                    <th>History</th>
                                </tr>
                            </thead>


                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif

        @if (auth()->user()->level == 1)
            @foreach ($scheduleByClass as $key => $schedules)
                <section class="row">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ $key }}</h4>
                        </div>

                        <div class="card card-body pb-0">
                            <div class="collapse-hide" id="{{ $key . 'collapse' }}">
                                <div style="min-height: 90vh !important" id='{{ $key }}'></div>
                                <div class="badges mt-2">
                                    <center>
                                        <span class="badge bg-primary">Đi học</span>
                                        <span class="badge bg-success">Nghỉ phép</span>
                                        <span class="badge bg-danger">Vắng học</span>
                                        <span class="badge bg-info">Chưa điểm danh</span>
                                    </center>
                                </div>
                            </div>
                            <a class="btn btn-white mt-3 mb-0" id="{{ $key . 'button' }}">
                                Show more
                            </a>
                        </div>

                    </div>
                </section>
            @endforeach

        @endif
    </div>

@stop
@push('js')
    {{-- js start --}}
    <script src="{{ asset('js/pages/localest-all.js') }}"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/sl-1.4.0/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            "use strict";
            let table = $("#basic-datatable").DataTable({
                keys: !0,
                processing: true,
                serverSide: true,
                ajax: '{!! route('attendance.api') !!}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'subject_name',
                        name: 'subject_name'
                    },
                    {
                        data: 'history',
                        targets: 2,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return `<a class="btn btn-success" href="${data}" >
                                History
                            </a>`;
                        }
                    }
                ]
            });

        });
    </script>
    <script src="{{ asset('js/pages/calendar.js') }}"></script>
    <script>
        @if (auth()->user()->level == 1)
            @foreach ($scheduleByClass as $key => $schedules)
                $(document).ready(function() {
                    var {{ $key . 'calendar' }} = document.getElementById('{{ $key }}');

                    var {{ $key }} = new FullCalendar.Calendar({{ $key . 'calendar' }}, {
                        headerToolbar: {
                            left: 'title',
                            center: '',
                            right: 'today dayGridMonth,timeGridWeek,timeGridDay,listWeek prev,next'
                        },
                        themeSystem: 'Sandstone',
                        initialView: 'timeGridWeek',
                        events: [
                            @foreach ($schedules as $schedule)
                                @php
                                    $add_date = 0;
                                    if ($schedule['shift'] == 1) {
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
                                    title: '{{ $schedule['name'] }}',
                                    start: '{{ $start }}',
                                    end: '{{ $end }}',
                                    description: 'ALoo',
                                    allDay: false,
                                    textColor: '#fff',
                                    color: @if ($schedule['status'] == 'Present')
                                        '#435ebe'
                                    @elseif ($schedule['status'] == 'Absent')
                                        '#f3616d'
                                    @elseif ($schedule['status'] == 'OnLeave')
                                        '#eaca4a'
                                    @elseif ($schedule['status'] == null)
                                        '#56b6f7'
                                    @endif
                                },
                            @endforeach
                        ]

                    });

                    {{ $key }}.render();
                });

                $(document).ready(function() {
                    $('#{{ $key . 'collapse' }}').hide();
                    $('#{{ $key . 'button' }}').on('click', function() {
                        $('#{{ $key . 'collapse' }}').toggle(500);
                        if ($.trim($(this).html()) == "Show More") {
                            $(this).html('Hide');
                        } else {
                            $(this).html('Show More');
                        }
                    });
                });
            @endforeach
        @endif
    </script>
    {{-- js end --}}
@endpush
