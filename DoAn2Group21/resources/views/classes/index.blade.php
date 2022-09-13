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
                    <h4 class="card-title">
                        @if (auth()->user()->level >= 3 && auth()->user()->level <= 4)
                            Quản lý lớp học
                        @else
                            Lớp học
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @if (auth()->user()->level >= 3 && auth()->user()->level <= 4)
                        <div class="col-12 d-flex justify-content-start mb-3">
                            <button type="button" class="btn btn-primary me-1 " data-bs-toggle="modal"
                                data-bs-target="#auto-upload-class"><i class="fas fa-file-upload"> </i> Tải lên lớp
                                học</button>
                            <a href="#" type="button" class="btn btn-success me-1 " data-bs-toggle="modal"
                                data-bs-target="#addSubjectModal"><i class="fas fa-plus"> </i> Thêm lớp học</a>
                        </div>

                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Tên lớp</th>
                                    <th>Tên môn</th>
                                    <th>Buổi học</th>
                                    <th>Ca</th>
                                    <th>Đã học</th>
                                    <th>K.thúc dự kiến</th>
                                    <th>Giảng viên</th>
                                    <th style="width: 40px;">Action</th>
                                    <th style="width: 40px;"></th>
                                    <th style="width: 40px;"></th>
                                </tr>
                            </thead>


                            <tbody>

                            </tbody>
                        </table>
                    @endif
                    @if (auth()->user()->level >= 1 && auth()->user()->level <= 2)
                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Tên lớp</th>
                                    <th>Tên môn</th>
                                    <th>Buổi học</th>
                                    <th>Ca</th>
                                    <th>Đã học</th>
                                    <th>K.thúc dự kiến</th>
                                    <th>Giảng viên</th>
                                    <th style="width: 40px;">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    @endif
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    @if (auth()->user()->level >= 3 && auth()->user()->level <= 4)
        <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Thêm lớp</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form form-vertical" action="{{ Route('class.store') }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="subject">Môn học</label>
                                            <select class="choices form-select" id="subject" name="subject">
                                                <option placeholder>Chưa có môn học</option>
                                                @foreach ($subject as $data)
                                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="name-vertical">Class Name</label>
                                            <input type="text" id="class-name" class="form-control" name="name"
                                                placeholder="Class Name" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="weekday">Buổi học</label>
                                            <select id="weekday" name="weekday[]"
                                                class="choices form-select multiple-remove" multiple="multiple">
                                                <option placeholder>Chưa có buổi học</option>
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
                                                <option placeholder>Chưa có ca</option>
                                                <option value="1">Sáng</option>
                                                <option value="2">Chiều</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" onclick="return validate()"
                                            class="btn btn-primary me-1 mb-1">Submit</button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade text-left modal-borderless" id="auto-upload-class" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm lớp tự động</h5>
                        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <center>
                            <a href="{{ asset('files/excel/auto-upload-class-example.xlsx') }}"
                                class="btn btn-primary mb-5"><i class="fas fa-file-download"></i> Tải file mẫu</a>
                            <img class="mx-auto w-32 mb-5"
                                src="https://user-images.githubusercontent.com/507615/54591670-ac0a0180-4a65-11e9-846c-e55ffce0fe7b.png"
                                alt="no data" />
                            <div class="input-group mb-3 d-flex justify-content-center">
                                <form action="{{ route('user.advancedImport') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group me-2">
                                        <input type="file" class="form-control" id="user-file" name="user_file"
                                            accept=".xlsx, .xls, .csv, .ods">

                                        <button type="submit" class="btn btn-info"
                                            OnClick="return confirm('Are u sủe ?')">Import</button>
                                    </div>
                                </form>
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
    @endif

@stop
@push('js')
    {{-- js start --}}
    <script src="{{ asset('js/pages/localest-all.js') }}"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/sl-1.4.0/datatables.min.js"></script>
    <script src="{{ asset('vendors/choices.js/choices.min.js') }}"></script>
    @if (auth()->user()->level >= 3 && auth()->user()->level <= 4)
        <script>
            $(document).ready(function() {
                "use strict";
                let table = $("#basic-datatable").DataTable({
                    keys: !0,
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('class.api') !!}',
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'subject_name',
                            name: 'subject_name'
                        },
                        {
                            data: 'weekdays',
                            name: 'weekdays'
                        },
                        {
                            data: 'shift',
                            name: 'shift'
                        },
                        {
                            data: 'numberOfLessonsLearned',
                            name: 'numberOfLessonsLearned',
                            searchable: false

                        },
                        {
                            data: 'expectedEndDate',
                            name: 'expectedEndDate',
                            searchable: false

                        },
                        {
                            data: 'teacher',
                            targets: 6,
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                console.log(data);
                                if (data.status == 1) {
                                    return data.name.teacher;
                                } else if (data.status == 404) {
                                    return `<a class="btn btn-success" href="${data.href}" >
                                    Thêm giáo viên
                                </a>`;
                                }

                            }
                        },
                        {
                            data: 'edit',
                            targets: 7,
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                if (data.status == 200) {
                                    return `<a class="btn btn-success" href="${data}" >
                                <i class="fas fa-pen"></i>
                            </a>`;
                                }
                                if (data.status == 202) {
                                    return '';
                                }
                            }
                        },
                        {
                            data: 'destroy',
                            targets: 8,
                            orderable: false,
                            searchable: false,
                            render: function(data) {
                                if (data.status == 200) {
                                    return `<form action="${data}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type='submit' class="btn-delete btn btn-danger">
                                             <i class="fas fa-trash"></i>
                                        </button>
                                    </form>`;
                                }
                                if (data.status == 202) {
                                    return '';
                                }
                            }
                        },
                        {
                            data: 'accept',
                            targets: 9,
                            orderable: false,
                            searchable: false,
                            render: function(data) {
                                if (data.status == 200) {
                                    return `<a class="btn btn-success" href="${data.href}" >
                                            <i class="fas fa-check"></i>
                                        </a>`;
                                }
                                if (data.status == 201) {
                                    return `<a class="btn btn-warning" href="${data.href}" >
                                            <i class="fas fa-hand-paper"></i>
                                        </a>`;
                                }
                                if (data.status == 202) {
                                    return '';
                                }
                            }
                        }
                    ]
                });

            });
        </script>
        <script>
            function validate() {
                $className = $('#class-name').val();
                $subject = $('#subject').val();
                $weekdays = $('#weekday').val();
                $shift = $('#shift').val();

                if ($className == '') {
                    alert('Bạn chưa nhập tên lớp');
                    return false;
                } else if ($subject == '' || $subject == 'Chưa có môn học') {
                    alert('Bạn chưa chọn môn học');
                    return false;
                } else if ($weekdays == '' || $weekdays == 'Chưa có buổi học') {
                    alert('Bạn chưa chọn buổi học');
                    return false;
                } else if ($shift == '' || $shift == 'Chưa có ca') {
                    alert('Bạn chưa chọn ca học');
                    return false;
                } else {
                    return true;
                }
            }
        </script>
        <script>
            $(document).ready(function() {
                $('#subject').change(function() {
                    var subject_id = $(this).val();
                    if (subject_id != '') {
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                            url: "{{ route('class.getLatestName') }}",
                            method: "POST",
                            data: {
                                subject_id: subject_id,
                                _token: _token
                            },
                            success: function(result) {
                                $('#class-name').val(result);
                            }
                        })
                    }
                });
            });
        </script>
    @endif
    @if (auth()->user()->level >= 1 && auth()->user()->level <= 2)
        <script>
            $(document).ready(function() {
                "use strict";
                let table = $("#basic-datatable").DataTable({
                    keys: !0,
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('class.api') !!}',
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'subject_name',
                            name: 'subject_name'
                        },
                        {
                            data: 'weekdays',
                            name: 'weekdays'
                        },
                        {
                            data: 'shift',
                            name: 'shift'
                        },
                        {
                            data: 'numberOfLessonsLearned',
                            name: 'numberOfLessonsLearned',
                            searchable: false

                        },
                        {
                            data: 'expectedEndDate',
                            name: 'expectedEndDate',
                            searchable: false

                        },
                        {
                            data: 'teacher',
                            name: 'teacher',
                            searchable: false
                        },
                        {
                            data: 'show',
                            targets: 7,
                            orderable: false,
                            searchable: false,
                            render: function(data) {
                                return `<a class="btn btn-success" href="${data}" >
                                            <i class="fas fa-eye"></i>
                                        </a>`;
                            }
                        }
                    ]
                });

            });
        </script>
    @endif
    {{-- js end --}}
@endpush
