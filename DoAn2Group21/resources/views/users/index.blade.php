@extends('layout.master')
@push('title')
    <title>User</title>
@endpush
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
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <section class="row">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Quản lý người dùng</h4>
                </div>
                <div class="card-body">
                    <div class="col-12 d-flex justify-content-start mb-3">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#import-user" 
                        class="btn btn-primary me-1 ">Thêm sinh viên</button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#add-user-modal"
                         class="btn btn-success me-1 ">Thêm thủ công</button>
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

            <div class="modal fade text-left modal-borderless" id="import-user" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm sinh viên</h5>
                            <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                                <i data-feather="x"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <center>
                                <a href="{{ asset('files/excel/user-import-example.xlsx') }}"
                                    class="btn btn-primary mb-5">Tải file mẫu</a>
                                <img class="mx-auto w-32 mb-5"
                                    src="https://user-images.githubusercontent.com/507615/54591670-ac0a0180-4a65-11e9-846c-e55ffce0fe7b.png"
                                    alt="no data" />
                                <div class="input-group mb-3 d-flex justify-content-center">
                                    <div class="input-group">
                                        <form action="{{ route('user.import') }}" method="POST"
                                            enctype="multipart/form-data" class="d-flex justify-content-start">
                                            @csrf
                                            <div class="input-group me-2">
                                                <label class="input-group-text" for="user-file"><i
                                                        class="bi bi-upload"></i></label>
                                                <input type="file" class="form-control" id="user-file" name="user_file"
                                                    accept=".xlsx, .xls, .csv, .ods">
                                            </div>
                                            <button type="submit" class="btn btn-info"
                                                OnClick="return confirm('Are u sủe ?')">Import</button>
                                        </form>

                                    </div>
                                </div>
                            </center>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="add-user-modal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">Thêm sinh viên</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="form form-vertical" action="{{ Route('user.store') }}" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="name-vertical">Họ và tên</label>
                                                <input type="text" id="name" class="form-control" name="name"
                                                    placeholder="Họ và tên">
                                            </div>
                                            <div class="form-group">
                                                <label for="name-vertical">Ngày sinh</label>
                                                <input type="date" id="birthday" class="form-control" name="birthday"
                                                    placeholder="Ngày sinh">
                                            </div>
                                            <div class="form-group">
                                                <label for="name-vertical">Email</label>
                                                <input type="text" id="email" class="form-control" name="email"
                                                    placeholder="Email">
                                            </div>
                                            <div class="form-group">
                                                <label for="subject">Chức vụ</label>
                                                <select class="choices form-select" id="level" name="level">
                                                    <option value="1">Sinh viên</option>
                                                    <option value="2">Giảng viên</option>
                                                    <option value="3">Giáo vụ</option>
                                                    <option value="4">Super Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" onclick="return validate()" class="btn btn-primary me-1 mb-1">Submit</button>
                                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@stop
@push('js')
    {{-- js start --}}
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/sl-1.4.0/datatables.min.js"></script>
    
    <script>
        $(document).ready(function() {
            "use strict";
            let table = $("#basic-datatable").DataTable({
                keys: !0,
                processing: true,
                serverSide: true,
                ajax: '{!! route('user.api') !!}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'level',
                        name: 'level'
                    },
                    {
                        data: 'edit',
                        targets: 3,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
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
    <script>

        const dateRegex = new RegExp('^(?:(?:1[6-9]|[2-9]\\d)?\\d{2})(?:(?:(\\/|-|\\.)(?:0?[13578]|1[02])\\1(?:31))|(?:(\\/|-|\\.)(?:0?[13-9]|1[0-2])\\2(?:29|30)))$|^(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\\/|-|\\.)0?2\\3(?:29)$|^(?:(?:1[6-9]|[2-9]\\d)?\\d{2})(\\/|-|\\.)(?:(?:0?[1-9])|(?:1[0-2]))\\4(?:0?[1-9]|1\\d|2[0-8])$', 'gm');
        const emailRegex = new RegExp('^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$', 'gm');

        function validate() {
            let name = $('#name').val();
            let email = $('#email').val();
            let birthday = $('#birthday').val();
            let level = $('#level').val();
            if (name == '' || name.length <= 1) {
                alert('Name is required');
                return false;
            } else if (email == '' || !emailRegex.test(email)) {
                alert('Email is required');
                return false;
            } else if (birthday == '' || !dateRegex.test(birthday)) {
                alert('Birthday is required');
                return false;
            } else if (level == '') {
                alert('Level is required');
                return false;
            } else {
                return true;
            }
        }
    </script>
    {{-- js end --}}
@endpush
