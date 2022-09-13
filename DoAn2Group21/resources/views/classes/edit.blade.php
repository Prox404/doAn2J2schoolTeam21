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
    <style>
        .hide{
            display: none;
        }
    </style>
    <div class="page-content">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div id= "for-any-message">
            
        </div>
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
                                                value="{{ $class->name }}" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="subject">Môn học</label>
                                            <select class="choices form-select" id="subject" name="subject" disabled>
                                                <option value="{{ $current_subject->id }}">{{ $current_subject->name }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="weekday">Buổi học</label>
                                            <select id="weekday" name="weekday[]"
                                                class="choices form-select multiple-remove" multiple="multiple">
                                                <option value="1"
                                                    @if (isset($weekdays)) 
                                                        @if (in_array(1, $weekdays)) 
                                                            selected 
                                                        @endif
                                                    @endif
                                                    >Thứ 2
                                                </option>
                                                <option value="2"
                                                    @if (isset($weekdays)) 
                                                        @if (in_array(2, $weekdays)) 
                                                            selected 
                                                        @endif
                                                    @endif
                                                    >Thứ 3
                                                </option>
                                                <option value="3"
                                                    @if (isset($weekdays)) 
                                                        @if (in_array(3, $weekdays)) 
                                                            selected 
                                                        @endif
                                                    @endif>
                                                    Thứ 4
                                                </option>
                                                <option value="4"
                                                    @if (isset($weekdays)) 
                                                        @if (in_array(4, $weekdays)) 
                                                            selected 
                                                        @endif
                                                    @endif>
                                                    Thứ 5
                                                </option>
                                                <option value="5"
                                                    @if (isset($weekdays)) 
                                                        @if (in_array(5, $weekdays)) 
                                                            selected 
                                                        @endif
                                                    @endif>
                                                    Thứ 6
                                                </option>
                                                <option value="6"
                                                    @if (isset($weekdays)) 
                                                        @if (in_array(6, $weekdays)) 
                                                            selected 
                                                        @endif
                                                    @endif>
                                                    Thứ 7
                                                </option>
                                                <option value="7"
                                                    @if (isset($weekdays)) 
                                                        @if (in_array(7, $weekdays)) 
                                                            selected 
                                                        @endif
                                                    @endif>
                                                    Chủ nhật
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="shift">Ca</label>
                                            <select class="choices form-select" id="shift" name="shift">
                                                <option placeholder>Chưa có ca</option>
                                                <option value="1" @if ($class->shift == 1) selected @endif>Sáng</option>
                                                <option value="2" @if ($class->shift == 2) selected @endif>
                                                    Chiều</option>
                                            </select>
                                        </div>
                                        <div class="form-group" id="teacherForm">
                                            <label for="teacher">Giảng viên</label>
                                            <select id="teacher" class="choices form-select" id="teacher" name="teacher">
                                                <option placeholder>Chưa có giảng viên</option>
                                                @if (!empty($current_teacher)) 
                                                    <option value="{{ $current_teacher->id }}" selected>{{ $current_teacher->name }}</option>
                                                @endif
                                                @foreach ($teachers as $data)
                                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                    
                                                @endforeach
                                                
                                            </select>
                                        </div>
                                       
                                    </div>
                                    @if (!isset($class->schedule()->first()->id))

                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" onclick=" return validate()" class="btn btn-primary me-1 mb-1">Submit</button>
                                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                        </div>
                                    @else
                                        Lớp này đã có lịch, không thể thay đổi
                                    @endif


                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card" id="editClassStudent">
                <div class="card-header">
                    <h4 class="card-title">Danh sách học sinh lớp {{ $class->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="col-12 d-flex justify-content-start mb-3">
                        @if($numberOfStudents >= 15)
                            Lớp đã đủ số lượng sinh viên
                        @else
                            <button type="button" data-bs-toggle="modal" data-bs-target="#import-class-modal" 
                            class="btn btn-primary me-1 "><i class="fas fa-file-upload"> </i> Tải lên sinh viên</button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#add-students-modal" 
                            class="btn btn-success me-1 "><i class="fas fa-plus"> </i> Thêm sinh viên</button>
                        @endif
                    </div>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
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
    @if($numberOfStudents < 15)
        <div class="modal fade text-left modal-borderless" id="import-class-modal" tabindex="-1" role="dialog"
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
                            <a href="{{asset('files/excel/class-import-example.xlsx')}}" class="btn btn-primary mb-5"><i class="fas fa-download"></i> Tải file mẫu</a>
                            <img class="mx-auto w-32 mb-5" src="https://user-images.githubusercontent.com/507615/54591670-ac0a0180-4a65-11e9-846c-e55ffce0fe7b.png" alt="no data" />
                            <div class="input-group mb-3 d-flex justify-content-center">
                                <form action="{{ route('classStudent.import', $class->id) }}" method="POST"
                                    enctype="multipart/form-data" class="d-flex justify-content-start">
                                    @csrf
                                    <div class="input-group me-2">
                                        <label class="input-group-text" for="user-file"><i class="bi bi-upload"></i></label>
                                        <input type="file" class="form-control" id="user-file" name="user_file"
                                            accept=".xlsx, .xls, .csv, .ods">
                                    </div>
                                    <button type="submit" class="btn btn-info"
                                        OnClick="return confirm('Are u sủe ?')">Import</button>
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


        <div class="modal fade text-left modal-borderless" id="add-students-modal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-full modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm sinh viên</h5>
                        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form action="" method="POST"
                                enctype="multipart/form-data" class="d-flex justify-content-start">

                                @csrf

                                <div class="col-md-4 me-2">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">ID</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="student-id" class="form-control" name="student-id"
                                                placeholder="ID">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 me-2">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Name</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="student-name" class="form-control" name="student-name"
                                                placeholder="Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Email</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="student-email" class="form-control" name="student-email"
                                                placeholder="Email">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mb-2">
                            <p class="text-muted" id="numberOfStudents"></p>
                        </div>
                        <table id="students-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Birthdate</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-submit" class="btn btn-primary">
                            Thêm
                        </button>
                        <button type="button" class="btn btn-light-primary" id="btn-close" data-bs-dismiss="modal">
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
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/sl-1.4.0/datatables.min.js"></script>
    <script src="{{ asset('vendors/choices.js/choices.min.js') }}"></script>
    <script>
        checkShedule();
        function checkShedule(){
            let weekdays = $('#weekday').val();
            let shift = $('#shift').val();

            if (weekdays.length == 0 || weekdays == "Chưa có ngày"|| shift == null || shift.length == 'Chưa có ca') {
                $('#btn-submit').addClass('hide');
                $('#editClassStudent').addClass('hide');
                $('#teacherForm').addClass('hide');
            } else {
                $('#btn-submit').removeClass('hide');
                $('#editClassStudent').removeClass('hide');
                $('#teacher').removeClass('hide');  
            }
        }

        $('#weekday').change(function() {
            checkShedule();
        });
        $('#shift').change(function() {
            checkShedule();
        });
        

    </script>
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
                        data: 'destroy',
                        targets: 2,
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
        $(document).ready(function() {
            "use strict";
            let name = $("#student-name").val();
            let id = $("#student-id").val();
            let email = $("#student-email").val();

            let url = '{!! route('user.get20Student', $class) !!}'+'?name='+name+'&id='+id+'&email='+email;

            let studentTable = $("#students-datatable").DataTable({
                keys: !0,
                lengthMenu: [20, 50, 100, 200, 500],
                searching: false,
                processing: true,
                serverSide: true,
                ajax: url,
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'birthday',
                        name: 'birthday'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    }
                ]
            });

            $('#students-datatable tbody').on('click', 'tr', function () {
                let numberElementSelected = studentTable.rows('.selected').data().length + 1;
                console.log(numberElementSelected);
                $(this).toggleClass('selected');
                $('#numberOfStudents').text(numberElementSelected + ' students selected');
            });

            $('#student-name').on('keyup', function () {
                name = $(this).val();
                let url = '{!! route('user.get20Student', $class) !!}'+'?name='+name+'&id='+id+'&email='+email;
                studentTable.ajax.url( url ).load();
            });

            $('#student-email').on('keyup', function () {
                email = $(this).val();
                let url = '{!! route('user.get20Student', $class) !!}'+'?name='+name+'&id='+id+'&email='+email;
                studentTable.ajax.url( url ).load();
            });

            $('#student-id').on('keyup', function () {
                id = $(this).val();
                let url = '{!! route('user.get20Student', $class) !!}'+'?name='+name+'&id='+id+'&email='+email;
                studentTable.ajax.url( url ).load();

            });

            $('#btn-submit').click(function () {
                if(studentTable.rows('.selected').data().length > 15){
                    alert('You can only select 15 students');
                }else{
                    $.ajax({
                        type: "POST",
                        url: "{!! route('classStudent.store', $class) !!}",
                        data: {
                            '_token': $('input[name=_token]').val(),
                            'students': studentTable.rows('.selected').data().toArray(),
                        },
                        success: function (response) {
                            if (response.status == 'success') {
                                $('#students-datatable').DataTable().rows('.selected').remove().draw();
                                studentTable.ajax.reload();
                                $('#student-name').val('');
                                $('#student-id').val('');
                                $('#student-email').val('');
                                alert(response.message);
                            }else{
                                alert('error' + response.message);
                            }
                        },
                        error: function () {
                            console.log('fail');
                        }
                    });
                }
            });

            $('#btn-close').click(function () {
                window.location.reload();
            });
        });
    </script>
    <script>

        function validate(){
            let shift = $('#shift').val();
            let weekdays = $('#weekday').val();
            let teacher = $('#teacher').val();
            
            if (weekdays.length == 0 || weekdays == "Chưa có ngày") {
                alert('Chưa có buổi học');
                return false;
            }
            if (teacher == null || teacher == "Chưa có giảng viên") {
                alert('Chưa có giảng viên');
                return false;
            }
            if (shift == null || shift == "Chưa có ca") {
                alert('Chưa có ca');
                return false;
            }
            return true;
        }

        function showMessage(message, id){
            const error = document.createElement("div");
            let idMessage = id + 'error';
            error.innerText = message;
            error.classList.add('alert');
            error.classList.add('alert-danger');
            error.classList.add('alert-dismissible');
            error.classList.add('fade');
            error.classList.add('show');

            error.setAttribute('id', idMessage);
            error.setAttribute('role', 'alert');

            const closeButton = document.createElement("button");
            closeButton.classList.add('btn-close');
            closeButton.setAttribute('type', 'button');
            closeButton.setAttribute('data-bs-dismiss', 'alert');
            closeButton.setAttribute('aria-label', 'Close');

            error.appendChild(closeButton);

            // console.log(message + " "+ id);
            let messageDiv = document.getElementById('for-any-message');
            messageDiv.appendChild(error);
        }

        $(function () {
            let shift = $('#shift').val();
            let weekdays = $('#weekday').val();
            let teacher = $('#teacher').val();

            // console.log(shift + " " + weekdays + " " + teacher);

            if (shift == null || shift == "Chưa có ca") {
                showMessage('Chưa có ca', 'shift');
                console.log('Chưa có ca');
            }
            if (weekdays.length == 0 || weekdays == "Chưa có ngày") {
                showMessage('Chưa có buổi học', 'weekday');
                console.log('Chưa có ngày');
            }
            if (teacher == null || teacher == "Chưa có giảng viên") {
                showMessage('Chưa có giảng viên', 'teacher');
                console.log('Chưa có giảng viên');
            }

            $('#shift').on('change', function () {
                let shift = $('#shift').val();
                if (shift == null || shift == "Chưa có ca") {
                    showMessage('Chưa có ca', 'shift');
                }else{
                    document.getElementById('shifterror').remove();
                }
            });

            $('#weekday').on('change', function () {
                let weekdays = $('#weekday').val();
                if (weekdays.length == 0 || weekdays == "Chưa có ngày") {
                    showMessage('Chưa có buổi học', 'weekday');
                }else{
                    document.getElementById('weekdayerror').remove();
                }
            });
            $('#teacher').on('change', function () {
                let teacher = $('#teacher').val();
                if (teacher == null || teacher == "Chưa có giảng viên") {
                    showMessage('Chưa có giảng viên', 'teacher');
                }else{
                    document.getElementById('teachererror').remove();
                }
            });

            
        });
    </script>
    {{-- js end --}}
@endpush