@extends('layout.master')
@push('title')
    <title>Schedule</title>
@endpush
@push('css')
    {{-- css start --}}
    <link href="{{ asset('css/pages/calendar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendors/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/sl-1.4.0/datatables.min.css" />
    <link rel="stylesheet" href="{{ asset('vendors/choices.js/choices.min.css') }}" />
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
        <section class="row">
            @if (auth()->user()->level == 3 || auth()->user()->level == 4)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách lớp học</h4>
                    </div>
                    <div class="card-body">
                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Class Name</th>
                                    <th>Subject Name</th>
                                    <th>Edit</th>
                                    <th>Destroy</th>
                                    <th>Status</th>
                                </tr>
                            </thead>


                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            @if (auth()->user()->level == 1 || auth()->user()->level == 2)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách lịch học</h4>
                    </div>
                    <div class="card-body">
                        <div id='top'>

                        </div>

                        <div id='calendar'></div>
                    </div>
                </div>
            @endif
        </section>
    </div>
@stop
@push('js')
    {{-- js start --}}
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/sl-1.4.0/datatables.min.js"></script>
    <script src="{{ asset('vendors/choices.js/choices.min.js') }}"></script>

    @if (auth()->user()->level == 3 || auth()->user()->level == 4)
        <script>
            $(document).ready(function() {
                "use strict";

                let table = $("#basic-datatable").DataTable({
                    keys: !0,
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('schedule.classApi') !!}',
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'subject_name',
                            name: 'subject_name'
                        },
                        {
                            data: 'edit',
                            targets: 2,
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return `<a class="btn btn-success" href="${data}" >
                                    Edit
                                </a>`;
                            }
                        },
                        {
                            data: 'destroy',
                            targets: 3,
                            orderable: false,
                            searchable: false,
                            render: function(data) {

                                return `<form action="${data}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type='submit' class="btn-delete btn btn-danger">Delete</button>
                            </form>`;
                            }
                        },
                        {
                            data: 'autoSchedule',
                            targets: 4,
                            orderable: false,
                            searchable: false,
                            render: function(data, type) {
                                if (data.status == 1) {
                                    return `<button class="btn btn-success" disabled>Lớp đã có lịch học</button>`;
                                } else if (data.status == 404) {
                                    return `<a class="btn btn-danger" href="${data.href}" >
                                        Tạo lịch
                                    </a>`;
                                } else if (data.status == 0) {
                                    return `<a class="btn btn-warning" href="${data.href}" >
                                        Chưa thể tạo
                                    </a>`;
                                }
                            }
                        }
                    ]
                });

            });
        </script>
    @endif
    @if (auth()->user()->level == 1 || auth()->user()->level == 2)
        <script src="{{ asset('js/pages/calendar.js') }}"></script>
        <script src="{{ asset('js/pages/localest-all.js') }}"></script>
        <script>
            function randomBackgroundColor() {
                return 'rgb(' + (Math.floor((256 - 199) * Math.random()) + 200) + ',' + (Math.floor((256 - 199) *
                    Math.random()) + 200) + ',' + (Math.floor((256 - 199) * Math.random()) + 200) + ')';
            }

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
                                allDay: false,
                                textColor: '#000',
                                color: randomBackgroundColor()
                            },
                        @endforeach
                    ]
                });

                calendar.render();

            });
        </script>
    @endif
    {{-- js end --}}
@endpush
