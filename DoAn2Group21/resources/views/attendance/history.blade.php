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
    <link rel="stylesheet" href="{{asset('css/pages/dripicons.css')}}">
    <link rel="stylesheet" href="{{asset('/vendors/dripicons/webfont.css')}}">
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
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Số buổi học</h4>
                </div>
                <div class="card-body">
                    @foreach ($class_sessions as $class_session)
                    <div class="btn-group btn-group-sm mb-2" role="group">
                        <a href="{{$class->id}}/{{$class_session->id}}" class="btn btn-primary">
                            @switch($class_session->weekday_id)
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

                            {{$class_session->date}}
                        </a>
                        <button type="button" class="btn"><i class="icon dripicons-cross"></i></button>
                    </div>
                    @endforeach
                </div>
            </div>
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
                                <th>Destroy</th>
                            </tr>
                        </thead>


                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </section>
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
                    }
                ]
            });

        });
    </script>
    {{-- js end --}}
@endpush