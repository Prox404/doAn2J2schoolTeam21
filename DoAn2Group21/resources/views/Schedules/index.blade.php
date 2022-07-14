@extends('layout.master')
@push('title')
    <title>Schedule</title>
@endpush
@push('css')
    {{-- css start --}}
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
        </section>
    </div>
@stop
@push('js')
    {{-- js start --}}
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/sl-1.4.0/datatables.min.js"></script>
    <script src="{{ asset('vendors/choices.js/choices.min.js') }}"></script>
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
                            if (data === 1) {
                                return `
                                    <button class="btn btn-primary" disabled>
                                        Môn này đã có lịch học
                                    </button>`;
                            } else {
                                return `<a class="btn btn-danger" href="${data}" >
                                    Tạo lịch
                                </a>`;
                            }
                        }
                    }
                ]
            });

        });
    </script>
    {{-- js end --}}
@endpush
