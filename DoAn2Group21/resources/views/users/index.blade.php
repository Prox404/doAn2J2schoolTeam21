@extends('layout.master')
@push('title')
<title>User</title>
@endpush
@push('css')
    {{-- css start --}}
    <link href="{{ asset('css/pages/calendar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendors/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/sl-1.4.0/datatables.min.css"/>
    {{-- css end --}}
@endpush
@section('content')
    <div class="page-content">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <section class="row">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Quản lý người dùng</h4>
                </div>
                <div class="card-body">
                    <div class="col-12 d-flex justify-content-start mb-3">
                        <div class="col-md-6 mb-1  me-2">
                            <div class="input-group">
                                <form action="{{ route('user.import') }}" method="POST" enctype="multipart/form-data" class="d-flex justify-content-start">
                                    @csrf
                                    <div class="input-group me-2">
                                        <label class="input-group-text" for="user-file"><i class="bi bi-upload"></i></label>
                                        <input type="file" class="form-control" id="user-file" name="user_file" accept=".xlsx, .xls, .csv, .ods">
                                    </div>
                                    <button type="submit" class="btn btn-info" OnClick="return confirm('Are u sủe ?')">Import</button>
                                </form>

                            </div>
                        </div>
                        <a href="#" type="button" class="btn btn-success me-1 ">Thêm thủ công</a>
                        <button type="button" class="btn btn-primary me-1 ">Tải xuống file mẫu</button>
                    </div>

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Level</th>
                            <th>Edit</th>
                            <th>Destroy</th>
                        </tr>
                        </thead>


                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Schedule</h4>
                </div>
                <div class="card-body">
                    <div id='top'>

                        Locales:
                        <select class="form-select mb-2" id='locale-selector'></select>

                    </div>

                    <div id='calendar'></div>
                </div>
            </div>
        </section>
    </div>
@stop
@push('js')
    {{-- js start --}}
    <script src="{{ asset('js/pages/calendar.js') }}"></script>
    <script src="{{ asset('js/pages/localest-all.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/sl-1.4.0/datatables.min.js"></script>
    <script>

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
                editable: true,
                dayMaxEvents: true, // allow "more" link when too many events
                events: [
                    {
                        title: 'All Day Event',
                        start: '2020-09-01'
                    },
                    {
                        title: 'Long Event',
                        start: '2020-09-07',
                        end: '2020-09-10'
                    },
                    {
                        groupId: 999,
                        title: 'Repeating Event',
                        start: '2020-09-09T16:00:00'
                    },
                    {
                        groupId: 999,
                        title: 'Repeating Event',
                        start: '2020-09-16T16:00:00'
                    },
                    {
                        title: 'Conference',
                        start: '2020-09-11',
                        end: '2020-09-13'
                    },
                    {
                        title: 'Meeting',
                        start: '2020-09-12T10:30:00',
                        end: '2020-09-12T12:30:00'
                    },
                    {
                        title: 'Lunch',
                        start: '2020-09-12T12:00:00'
                    },
                    {
                        title: 'Meeting',
                        start: '2020-09-12T14:30:00'
                    },
                    {
                        title: 'Happy Hour',
                        start: '2020-09-12T17:30:00'
                    },
                    {
                        title: 'Dinner',
                        start: '2020-09-12T20:00:00'
                    },
                    {
                        title: 'Birthday Party',
                        start: '2020-09-13T07:00:00'
                    },
                    {
                        title: 'Click for Google',
                        url: 'http://google.com/',
                        start: '2020-09-28'
                    }
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
    <script>
        $(document).ready(function() {
            "use strict";
            let table = $("#basic-datatable").DataTable({
                keys: !0,
                processing: true,
                serverSide: true,
                ajax: '{!! route('user.api') !!}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'level', name: 'level'},
                    {
                        data: 'edit',
                        targets: 3,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return `<a class="btn btn-success" href="${data}">
                                Edit
                            </a>`;
                        }
                    },
                    {
                        data: 'destroy',
                        targets: 4,
                        orderable: false,
                        searchable: false,
                        render: function (data) {

                            return `<form action="${data}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type='submit' class="btn-delete btn btn-danger">Delete</button>
                        </form>`;
                        }
                    }
                ]
            });

        });
    </script>
    {{-- js end --}}
@endpush
