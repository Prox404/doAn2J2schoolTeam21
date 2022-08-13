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
            function randomBackgroundColor(){
                return 'rgb(' + (Math.floor((256 - 199) * Math.random()) + 200) + ',' + (Math.floor((256 - 199) *
                Math.random()) + 200) + ',' + (Math.floor((256 - 199) * Math.random()) + 200) + ')';
            }

            document.addEventListener('DOMContentLoaded', function() {
                var initialLocaleCode = 'vi';
                var localeSelectorEl = document.getElementById('locale-selector');
                var calendarEl = document.getElementById('calendar');

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                    },
                    // initialDate: '2020-09-12',
                    locale: initialLocaleCode,
                    buttonIcons: false, // show the prev/next text
                    weekNumbers: true,
                    navLinks: true, // can click day/week names to navigate views
                    editable: false,
                    dayMaxEvents: true, // allow "more" link when too many events
                    events: 
                    [   
                        @foreach ($schedules as $schedule)
                            {
                                title: '{{ $schedule->name }} | {{ $schedule->shift == 1 ? "Sáng 7:00 - 9:00" : "Chiều 13:00 - 15:00" }}',
                                start: '{{ $schedule->date }}',
                                color: randomBackgroundColor(),
                                textColor: '#000',
                            },
                        @endforeach
                    ]
                });

                calendar.render();

                // build the locale selector's options
                calendar.getAvailableLocaleCodes().forEach(function(localeCode) {
                    var optionEl = document.createElement('option');
                    optionEl.value = localeCode;
                    optionEl.selected = localeCode == initialLocaleCode;
                    optionEl.innerText = localeCode;
                    localeSelectorEl.appendChild(optionEl);
                });

                // when the selected option changes, dynamically change the calendar option
                localeSelectorEl.addEventListener('change', function() {
                    if (this.value) {
                        calendar.setOption('locale', this.value);
                    }
                });

            });
        </script>
        
    @endif
    {{-- js end --}}
@endpush
