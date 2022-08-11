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
                                            <label for="teacher">Giảng viên</label>
                                            <select id="teacher" class="choices form-select" id="teacher" name="teacher">
                                                <option placeholder>Chưa có giảng viên</option>
                                                @foreach ($teachers as $data)
                                                    <option value="{{ $data->user_id }}"
                                                        @if (!empty($current_teacher)) 
                                                            @if ($current_teacher->id == $data->user_id)
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
                                                <option placeholder>Chưa có ca</option>
                                                <option value="1" @if ($class->shift == 1) selected @endif>Sáng</option>
                                                <option value="2" @if ($class->shift == 2) selected @endif>
                                                    Chiều</option>
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

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Danh sách học sinh lớp {{ $class->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="col-12 d-flex justify-content-start mb-3">
                        <div class="input-group">
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