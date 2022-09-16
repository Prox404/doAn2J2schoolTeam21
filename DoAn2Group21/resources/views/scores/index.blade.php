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
        @if (auth()->user()->level >= 2 && auth()->user()->level <= 4)
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
                                    <th>Edit</th>
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
            <section class="row">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Điểm nè bru</h4>
                    </div>
                    <div class="card-body">
                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Class Name</th>
                                    <th>Subject Name</th>
                                    <th>Show</th>
                                </tr>
                            </thead>


                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif
    </div>

@stop
@push('js')
    {{-- js start --}}
    <script src="{{ asset('js/pages/localest-all.js') }}"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/sl-1.4.0/datatables.min.js"></script>
    <script>
        @if (auth()->user()->level >= 2 && auth()->user()->level <= 4)
            $(document).ready(function() {
                "use strict";
                let table = $("#basic-datatable").DataTable({
                    keys: !0,
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('score.classApi') !!}',
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
                        }
                    ]
                });

            });
        @endif
        @if (auth()->user()->level == 1)
            $(document).ready(function() {
                "use strict";
                let table = $("#basic-datatable").DataTable({
                    keys: !0,
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('score.classApi') !!}',
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'subject_name',
                            name: 'subject_name'
                        },
                        {
                            data: 'show',
                            targets: 2,
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return `<a class="btn btn-success" href="${data}" >
                                Show
                            </a>`;
                            }
                        }
                    ]
                });

            });
        @endif
    </script>
    {{-- js end --}}
@endpush
