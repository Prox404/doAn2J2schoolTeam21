@extends('layout.master')
@push('title')
<title>Classes</title>
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
                    <h4 class="card-title">Sửa lý thông tin lớp</h4>
                </div>
                <div class="card-body">
                    <div class="col-12 d-flex justify-content-start mb-3">
                        <form class="form form-vertical" action="{{ Route('class.update', $class) }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="name-vertical">Class Name</label>
                                            <input type="text" id="name-vertical" class="form-control" name="name"
                                                placeholder="Class Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="subject">Môn học</label>
                                            <select class="choices form-select" id="subject" name="subject">
                                                @foreach ($subject as $data)
                                                    <option value="{{$data->id}}">{{$data->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="weekday">Buổi học</label>
                                            <select id="weekday" name="weekday[]" class="choices form-select multiple-remove" multiple="multiple">
                                                <option value="1">Thứ 2</option>
                                                <option value="2">Thứ 3</option>
                                                <option value="3">Thứ 4</option>
                                                <option value="4">Thứ 5</option>
                                                <option value="5">Thứ 6</option>
                                                <option value="6">Thứ 7</option>
                                                <option value="7">Chủ nhật</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="shift">Ca</label>
                                            <select class="choices form-select" id="shift" name="shift">
                                                <option value="1">Sáng</option>
                                                <option value="2">Chiều</option>
                                            </select>
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

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Danh sach hoc sinh</h4>
                </div>
                <div class="card-body">
                    
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
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

@stop
@push('js')
    {{-- js start --}}
    <script src="{{ asset('js/pages/localest-all.js') }}"></script>
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
                ajax: '{!! route('class.userApi', $class) !!}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'edit',
                        targets: 2,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return `<a class="btn btn-success" >
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

                            return `<form method="post">
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
