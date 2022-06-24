@extends('layout.master')
@push('css')
    {{-- css start --}}
    <link href="{{ asset('vendors/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/sl-1.4.0/datatables.min.css" />
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
                    <h4 class="card-title">Quản lý môn học</h4>
                </div>
                <div class="card-body">
                    <div class="col-12 d-flex justify-content-start mb-3">
                        <a href="#" type="button" class="btn btn-success me-1 " data-bs-toggle="modal" data-bs-target="#addSubjectModal" >Thêm môn học</a>
                    </div>

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Number Classes</th>
                                <th>Edit</th>
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

    <!-- Modal -->
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical" action="{{ Route('subject.store') }}" method="post">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name-vertical">Subject Name</label>
                                        <input type="text" id="name-vertical" class="form-control" name="name"
                                            placeholder="Subject Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="start-date-vertical">Start Date</label>
                                        <input type="date" id="start-date-vertical" class="form-control" name="start_date"
                                            placeholder="Start Date">
                                    </div>
                                    <div class="form-group">
                                        <label for="end-date-vertical">End Date</label>
                                        <input type="date" id="end-date-vertical" class="form-control" name="end_date"
                                            placeholder="End Date">
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                ajax: '{!! route('subject.api') !!}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'class_count',
                        name: 'class_count',
                        searchable: false
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
                    }
                ]
            });

        });
    </script>
    {{-- js end --}}
@endpush
