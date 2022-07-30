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
                {{!! session()->get('message') !!}}
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
                            @method('PUT')
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="hidden" name="id" value="{{ $class->id }}">
                                            <label for="name-vertical">Class Name</label>
                                            <input type="text" id="name-vertical" class="form-control" name="name"
                                                value="{{ $class->name }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="subject">Môn học</label>
                                            <select class="choices form-select" id="subject" name="subject">
                                                @foreach ($subject as $data)
                                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="weekday">Buổi học</label>
                                            <select id="weekday" name="weekday[]"
                                                class="choices form-select multiple-remove" multiple="multiple">
                                                <option value="1" 
                                                    @if (in_array(1, $weekdays))
                                                        selected
                                                    @endif
                                                >Thứ 2</option>
                                                <option value="2"
                                                    @if (in_array(2, $weekdays))
                                                        selected
                                                    @endif
                                                >Thứ 3</option>
                                                <option value="3"
                                                    @if (in_array(3, $weekdays))
                                                        selected
                                                    @endif
                                                >Thứ 4</option>
                                                <option value="4" 
                                                    @if (in_array(4, $weekdays))
                                                        selected
                                                    @endif
                                                >Thứ 5</option>
                                                <option value="5"
                                                    @if (in_array(5, $weekdays))
                                                        selected
                                                    @endif
                                                >Thứ 6</option>
                                                <option value="6" 
                                                    @if (in_array(6, $weekdays))
                                                        selected
                                                    @endif
                                                >Thứ 7</option>
                                                <option value="7"
                                                    @if (in_array(7, $weekdays))
                                                        selected
                                                    @endif
                                                >Chủ nhật</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="teacher">Giảng viên</label>
                                            <select class="choices form-select" id="teacher" name="teacher">
                                                @foreach ($teachers as $data)
                                                    <option value="{{ $data->user_id }}" 
                                                     @if(!empty($current_teacher))
                                                        @if ($current_teacher->teacher_id == $data->user_id)
                                                                selected
                                                        @endif
                                                    @endif
                                                    >{{ $data->user_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- {{$current_teacher}} --}}
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
                    <h4 class="card-title">Danh sách học sinh lớp {{$class->name}}</h4>
                </div>
                <div class="card-body">
                    <div class="col-12 d-flex justify-content-start mb-3">
                        <div class="input-group">
                            <form action="{{ route('classStudent.import', $class->id) }}" method="POST" enctype="multipart/form-data" class="d-flex justify-content-start">
                                @csrf
                                <div class="input-group me-2">
                                    <label class="input-group-text" for="user-file"><i class="bi bi-upload"></i></label>
                                    <input type="file" class="form-control" id="user-file" name="user_file" accept=".xlsx, .xls, .csv, .ods">
                                </div>
                                <button type="submit" class="btn btn-info" OnClick="return confirm('Are u sủe ?')">Import</button>
                            </form>
                        </div>
                    </div>
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
