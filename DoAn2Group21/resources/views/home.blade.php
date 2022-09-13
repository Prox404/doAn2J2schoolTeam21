@extends('layout.master')
@push('title')
    <title>Home</title>
@endpush
@push('css')
    {{-- css start --}}
    <link rel="stylesheet" href="{{ asset('css\widgets\prox-calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    {{-- css end --}}
@endpush
@section('content')
    <div class="page-content">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="page-heading">
            <h3>Dashboard</h3>
        </div>

        @if (auth()->user()->level == 1)
            <section class="row">
                <div class="row">
                    {{-- Welcome --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-7 col-lg-7 ">
                                        <h3 class="font-semibold">Chào {{ auth()->user()->name }} !,</h3>
                                        <h6 class="font-semibold text-muted mb-0 mt-2">Chào mừng đến với J2School</h6>
                                    </div>

                                    <div class="col-12 col-md-5 col-lg-5 d-flex justify-content-center">
                                        <img style="width: 300px" src="{{ asset('images\samples\school-fluid.png') }}"
                                            alt="logo" class="img-fluid">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Thong ke --}}
                    <div class="col-12 col-lg-12">
                        <div class="row">
                            <div class="page-heading">
                                <h4>Thống kê</h4>
                            </div>
                            <div class="col-12 col-lg-6 col-md-6">
                                <div class="card">
                                    <button data-bs-toggle="modal" data-bs-target="#numberOfClass" style="text-align: left "
                                        class="btn btn-white p-0">
                                        <div class="card-body px-3 py-4-5">
                                            <div class="row">
                                                <div class="col-2 col-md-4">
                                                    <div class="stats-icon green">
                                                        <i class="fas fa-smile text-light"></i>
                                                    </div>
                                                </div>
                                                <div class="col-10 col-md-8">
                                                    <h6 class="text-muted font-semibold">Số lớp đã/đang học</h6>
                                                    <h6 class="font-extrabold mb-0">{{ count($classes) }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 col-md-6">
                                <div class="card">
                                    <button data-bs-toggle="modal" data-bs-target="#numberOfSessions"
                                        style="text-align: left " class="btn btn-white p-0">
                                        <div class="card-body px-3 py-4-5">
                                            <div class="row">
                                                <div class="col-2 col-md-4">
                                                    <div class="stats-icon green">
                                                        <i class="fas fa-smile text-light"></i>
                                                    </div>
                                                </div>
                                                <div class="col-10 col-md-8">
                                                    <h6 class="text-muted font-semibold">Số buổi đã học</h6>
                                                    <h6 class="font-extrabold mb-0">{{ count($sessions) }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Lich --}}
                    <div class="row">
                        <div class="page-heading">
                            <h4>Lịch</h4>
                        </div>
                        <div class="col-12 col-lg-9">
                            <div class="row">

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Lịch cá nhân</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="content w-100">
                                            <div class="calendar-container">
                                                <div class="calendar">
                                                    <div class="year-header">
                                                        <span class="left-button fa fa-chevron-left" id="prev"> </span>
                                                        <span class="year" id="label"></span>
                                                        <span class="right-button fa fa-chevron-right" id="next">
                                                        </span>
                                                    </div>
                                                    <table class="months-table w-100">
                                                        <tbody>
                                                            <tr class="months-row">
                                                                <td class="month">Jan</td>
                                                                <td class="month">Feb</td>
                                                                <td class="month">Mar</td>
                                                                <td class="month">Apr</td>
                                                                <td class="month">May</td>
                                                                <td class="month">Jun</td>
                                                                <td class="month">Jul</td>
                                                                <td class="month">Aug</td>
                                                                <td class="month">Sep</td>
                                                                <td class="month">Oct</td>
                                                                <td class="month">Nov</td>
                                                                <td class="month">Dec</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <table class="days-table w-100">
                                                        <td class="day">Sun</td>
                                                        <td class="day">Mon</td>
                                                        <td class="day">Tue</td>
                                                        <td class="day">Wed</td>
                                                        <td class="day">Thu</td>
                                                        <td class="day">Fri</td>
                                                        <td class="day">Sat</td>
                                                    </table>
                                                    <div class="frame">
                                                        <table class="dates-table w-100">
                                                            <tbody class="tbody">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <button class="button" id="add-button">Thêm lịch</button>
                                                </div>
                                            </div>
                                            <div class="events-container">
                                            </div>
                                            <div class="dialog" id="dialog">
                                                <h2 class="dialog-header"> Thêm lịch </h2>
                                                <form class="form" id="form">
                                                    <div class="form-container" align="center">
                                                        <label class="form-label" id="valueFromMyButton"
                                                            for="name">Tên sự
                                                            kiện</label>
                                                        <input class="input" type="text" id="name"
                                                            maxlength="36">
                                                        <input type="button" value="Cancel" class="button"
                                                            id="cancel-button">
                                                        <input type="button" value="OK" class="button button-white"
                                                            id="ok-button">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3">
                            <div class="card pb-4">
                                <div class="card-header">
                                    <h4 class="card-title">Lich học tuần</h4>
                                </div>
                                <div class="card-body" style="max-height: 539px; overflow-y: scroll;">
                                    @foreach ($scheduleOfWeek as $schedule)
                                        <div class="alert alert-primary">
                                            <strong>{{ $schedule['class'] }}</strong> <br>
                                            {{ $schedule['date'] }} <br>
                                            @if ($schedule['shift'] == 1)
                                                07:00 - 09:00
                                            @elseif($schedule['shift'] == 2)
                                                13:00 - 15:00
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Giang vien --}}
                    <div class="row">
                        <div class="page-heading">
                            <h4>Thông tin giảng viên</h4>
                        </div>
                        @foreach ($teachers as $teacher)
                            <div class="col-12 col-lg-4 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-12 col-md-12">
                                                <center>
                                                    <div class="avatar img-thumbnail">
                                                        <img src="{{ asset('images/faces/3.jpg') }}" alt=""
                                                            srcset="" style="width: 120px; height:120px;">
                                                    </div>
                                                </center>
                                            </div>
                                            <div class="col-12 col-md-12 mt-3">
                                                <center>
                                                    <h5 class="font-bold">{{ $teacher->name }}</h5>
                                                    <h6 class="text-muted font-semibold">{{ $teacher->email }}</h6>
                                                    <h6 class="font-extrabold mb-0">{{ $teacher->subject }}</h6>
                                                    <a href="mailto:{{ $teacher->email }}"
                                                        class="btn btn-primary mt-3">Liên
                                                        hệ</a>
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
                <div class="col-12 col-lg-3">
                    <div class="card">
                    </div>
                </div>
            </section>

            {{-- Modal lop --}}
            <div class="modal fade text-left w-100" id="numberOfClass" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel16" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel16">Danh sách các lớp đang học</h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i data-feather="x"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-lg">
                                    <thead>
                                        <tr>
                                            <th>Mã lớp</th>
                                            <th>Tên lớp</th>
                                            <th>Ca</th>
                                            <th>Buổi học</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classes as $class)
                                            <tr>
                                                <td>{{ $class['id'] }}</td>
                                                <td>{{ $class['name'] }}</td>
                                                <td>
                                                    @if ($class['shift'] == 1)
                                                        07:00 - 09:00
                                                    @elseif($class['shift'] == 2)
                                                        13:00 - 15:00
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $weekdayName = [];
                                                        foreach ($class['weekdays'] as $weekday) {
                                                            switch ($weekday) {
                                                                case 1:
                                                                    $weekdayName[] = 'T2';
                                                                    break;
                                                                case 2:
                                                                    $weekdayName[] = 'T3';
                                                                    break;
                                                                case 3:
                                                                    $weekdayName[] = 'T4';
                                                                    break;
                                                                case 4:
                                                                    $weekdayName[] = 'T5';
                                                                    break;
                                                                case 5:
                                                                    $weekdayName[] = 'T6';
                                                                    break;
                                                                case 6:
                                                                    $weekdayName[] = 'T7';
                                                                    break;
                                                                case 7:
                                                                    $weekdayName[] = 'CN';
                                                                    break;
                                                            }
                                                        }
                                                        echo implode(', ', $weekdayName);
                                                    @endphp
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Modal buoi hoc --}}
            <div class="modal fade text-left w-100" id="numberOfSessions" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel16" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel16">Danh sách các lớp đang học</h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i data-feather="x"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-lg">
                                    <thead>
                                        <tr>
                                            <th>Tên môn học</th>
                                            <th>Lớp học</th>
                                            <th>Ngày học</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sessions as $session)
                                            <tr>
                                                <td>{{ $session['subject'] }}</td>
                                                <td>{{ $session['class'] }}</td>
                                                <td>{{ $session['date'] }}</td>
                                                <td>
                                                    @switch ($session['status'])
                                                        @case(1)
                                                            Đi học
                                                        @break

                                                        @case(2)
                                                            Vắng học
                                                        @break

                                                        @case(3)
                                                            Có phép
                                                        @break

                                                        @default
                                                            Chưa học
                                                    @endswitch
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (auth()->user()->level == 2)
            <section class="row">

                <div class="row">
                    {{-- Welcome --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-7 col-lg-7 ">
                                        <h3 class="font-semibold">Chào {{ auth()->user()->name }} !,</h3>
                                        <h6 class="font-semibold text-muted mb-0 mt-2">Chào mừng đến với J2School</h6>
                                    </div>

                                    <div class="col-12 col-md-5 col-lg-5 d-flex justify-content-center">
                                        <img style="width: 300px" src="{{ asset('images\samples\school-fluid.png') }}"
                                            alt="logo" class="img-fluid">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">

                        <div class="row">
                            <div class="page-heading">
                                <h4>Lớp học</h4>
                            </div>
                            @foreach ($classes as $class)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card">
                                        <div class="card-header rand-color">
                                            <h4 class="card-title">{{ $class['name'] }}</h4>
                                        </div>
                                        <div class="card-body">
                                            <p class="mt-2">Môn: {{ $class['subject_name'] }}</p>
                                            <p class="mt-0">Ca:
                                                @if ($class['shift'] == 1)
                                                    Sáng
                                                @elseif($class['shift'] == 2)
                                                    Chiều
                                                @endif
                                            </p>
                                            <p class="mt-0">Buổi:
                                                @php
                                                    $weekdayName = [];
                                                    foreach ($class['weekdays'] as $weekday) {
                                                        switch ($weekday) {
                                                            case 1:
                                                                $weekdayName[] = 'T2';
                                                                break;
                                                            case 2:
                                                                $weekdayName[] = 'T3';
                                                                break;
                                                            case 3:
                                                                $weekdayName[] = 'T4';
                                                                break;
                                                            case 4:
                                                                $weekdayName[] = 'T5';
                                                                break;
                                                            case 5:
                                                                $weekdayName[] = 'T6';
                                                                break;
                                                            case 6:
                                                                $weekdayName[] = 'T7';
                                                                break;
                                                            case 7:
                                                                $weekdayName[] = 'CN';
                                                                break;
                                                        }
                                                    }
                                                    echo implode(', ', $weekdayName);
                                                @endphp
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            @endforeach

                        </div>
                    </div>
                    {{-- Lich --}}
                    <div class="row">
                        <div class="page-heading">
                            <h4>Lịch</h4>
                        </div>
                        <div class="col-12 col-lg-9">
                            <div class="row">

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Lịch cá nhân</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="content w-100">
                                            <div class="calendar-container">
                                                <div class="calendar">
                                                    <div class="year-header">
                                                        <span class="left-button fa fa-chevron-left" id="prev">
                                                        </span>
                                                        <span class="year" id="label"></span>
                                                        <span class="right-button fa fa-chevron-right" id="next">
                                                        </span>
                                                    </div>
                                                    <table class="months-table w-100">
                                                        <tbody>
                                                            <tr class="months-row">
                                                                <td class="month">Jan</td>
                                                                <td class="month">Feb</td>
                                                                <td class="month">Mar</td>
                                                                <td class="month">Apr</td>
                                                                <td class="month">May</td>
                                                                <td class="month">Jun</td>
                                                                <td class="month">Jul</td>
                                                                <td class="month">Aug</td>
                                                                <td class="month">Sep</td>
                                                                <td class="month">Oct</td>
                                                                <td class="month">Nov</td>
                                                                <td class="month">Dec</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <table class="days-table w-100">
                                                        <td class="day">Sun</td>
                                                        <td class="day">Mon</td>
                                                        <td class="day">Tue</td>
                                                        <td class="day">Wed</td>
                                                        <td class="day">Thu</td>
                                                        <td class="day">Fri</td>
                                                        <td class="day">Sat</td>
                                                    </table>
                                                    <div class="frame">
                                                        <table class="dates-table w-100">
                                                            <tbody class="tbody">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <button class="button" id="add-button">Thêm lịch</button>
                                                </div>
                                            </div>
                                            <div class="events-container">
                                            </div>
                                            <div class="dialog" id="dialog">
                                                <h2 class="dialog-header"> Thêm lịch </h2>
                                                <form class="form" id="form">
                                                    <div class="form-container" align="center">
                                                        <label class="form-label" id="valueFromMyButton"
                                                            for="name">Tên sự
                                                            kiện</label>
                                                        <input class="input" type="text" id="name"
                                                            maxlength="36">
                                                        <input type="button" value="Cancel" class="button"
                                                            id="cancel-button">
                                                        <input type="button" value="OK" class="button button-white"
                                                            id="ok-button">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3">
                            <div class="card pb-4">
                                <div class="card-header">
                                    <h4 class="card-title">Lich học tuần</h4>
                                </div>
                                <div class="card-body" style="max-height: 539px; overflow-y: scroll;">
                                    @foreach ($scheduleOfWeek as $schedule)
                                        <div class="alert alert-primary">
                                            <strong>{{ $schedule['class'] }}</strong> <br>
                                            {{ $schedule['date'] }} <br>
                                            @if ($schedule['shift'] == 1)
                                                07:00 - 09:00
                                            @elseif($schedule['shift'] == 2)
                                                13:00 - 15:00
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Giao vu --}}
                    <div class="row">
                        <div class="page-heading">
                            <h4>Liên hệ giáo vụ</h4>
                        </div>
                        @foreach ($admins as $admin)
                            <div class="col-12 col-lg-4 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-12 col-md-12">
                                                <center>
                                                    <div class="avatar img-thumbnail">
                                                        <img src="{{ asset('images/faces/3.jpg') }}" alt=""
                                                            srcset="" style="width: 120px; height:120px;">
                                                    </div>
                                                </center>
                                            </div>
                                            <div class="col-12 col-md-12 mt-3">
                                                <center>
                                                    <h5 class="font-bold">{{ $admin->name }}</h5>
                                                    <h6 class="text-muted font-semibold">{{ $admin->email }}</h6>
                                                    <a href="mailto:{{ $admin->email }}"
                                                        class="btn btn-primary mt-3">Liên
                                                        hệ</a>
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </section>
        @endif

        @if (auth()->user()->level == 3 || auth()->user()->level == 4)
            <section class="row">
                {{-- Welcome --}}
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-7 col-lg-7 ">
                                    <h3 class="font-semibold">Chào {{ auth()->user()->name }} !,</h3>
                                    <h6 class="font-semibold text-muted mb-0 mt-2">Chào mừng đến với J2School</h6>
                                </div>
                                <div class="col-12 col-md-5 col-lg-5 d-flex justify-content-center">
                                    <img style="width: 300px" src="{{ asset('images\samples\school-fluid.png') }}"
                                        alt="logo" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Thong ke --}}
                <div class="col-12 col-lg-12">
                    <div class="page-heading">
                        <h4>Thống kê</h4>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-3 ">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body rand-color">
                                        <h4 class="card-title">Số lượng sinh viên</h4>
                                        <h2 class="text-center font-semibold mt-3">
                                            {{ $number_student }}
                                        </h2>
                                    </div>
                                </div>
                                <div class="card-footer border-top-0 text-right pt-2 pb-2">
                                    <a href="{{ route('user.index') }}"><button class="btn btn-light-primary">Xem chi
                                            tiết nè</button></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 ">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body rand-color">
                                        <h4 class="card-title">Số lượng giảng viên</h4>
                                        <h2 class="text-center font-semibold mt-3">
                                            {{ $number_teacher }}
                                        </h2>
                                    </div>
                                </div>
                                <div class="card-footer border-top-0 text-right pt-2 pb-2">
                                    <a href="{{ route('user.index') }}"><button class="btn btn-light-primary">Xem chi
                                            tiết nè</button></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body rand-color">
                                        <h4 class="card-title">Số lượng lớp</h4>
                                        <h2 class="text-center font-semibold mt-3">
                                            {{ $number_class }}
                                        </h2>
                                    </div>
                                </div>
                                <div class="card-footer border-top-0 text-right pt-2 pb-2">
                                    <a href="{{ route('class.index') }}"><button class="btn btn-light-primary">Xem
                                            chi tiết nè</button></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body rand-color">
                                        <h4 class="card-title">Số lượng môn học</h4>
                                        <h2 class="text-center font-semibold mt-3">
                                            {{ $number_subject }}
                                        </h2>
                                    </div>
                                </div>
                                <div class="card-footer border-top-0 text-right pt-2 pb-2">
                                    <a href="{{ route('user.index') }}"><button class="btn btn-light-primary">Xem chi
                                            tiết nè</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Lich --}}
                <div class="row">
                    <div class="page-heading">
                        <h4>Lịch</h4>
                    </div>
                    <div class="col-12">
                        <div class="row">

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Lịch cá nhân</h4>
                                </div>
                                <div class="card-body">
                                    <div class="content w-100">
                                        <div class="calendar-container">
                                            <div class="calendar">
                                                <div class="year-header">
                                                    <span class="left-button fa fa-chevron-left" id="prev"> </span>
                                                    <span class="year" id="label"></span>
                                                    <span class="right-button fa fa-chevron-right" id="next">
                                                    </span>
                                                </div>
                                                <table class="months-table w-100">
                                                    <tbody>
                                                        <tr class="months-row">
                                                            <td class="month">Jan</td>
                                                            <td class="month">Feb</td>
                                                            <td class="month">Mar</td>
                                                            <td class="month">Apr</td>
                                                            <td class="month">May</td>
                                                            <td class="month">Jun</td>
                                                            <td class="month">Jul</td>
                                                            <td class="month">Aug</td>
                                                            <td class="month">Sep</td>
                                                            <td class="month">Oct</td>
                                                            <td class="month">Nov</td>
                                                            <td class="month">Dec</td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <table class="days-table w-100">
                                                    <td class="day">Sun</td>
                                                    <td class="day">Mon</td>
                                                    <td class="day">Tue</td>
                                                    <td class="day">Wed</td>
                                                    <td class="day">Thu</td>
                                                    <td class="day">Fri</td>
                                                    <td class="day">Sat</td>
                                                </table>
                                                <div class="frame">
                                                    <table class="dates-table w-100">
                                                        <tbody class="tbody">
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <button class="button" id="add-button">Thêm lịch</button>
                                            </div>
                                        </div>
                                        <div class="events-container">
                                        </div>
                                        <div class="dialog" id="dialog">
                                            <h2 class="dialog-header"> Thêm lịch </h2>
                                            <form class="form" id="form">
                                                <div class="form-container" align="center">
                                                    <label class="form-label" id="valueFromMyButton" for="name">Tên
                                                        sự
                                                        kiện</label>
                                                    <input class="input" type="text" id="name" maxlength="36">
                                                    <input type="button" value="Cancel" class="button"
                                                        id="cancel-button">
                                                    <input type="button" value="OK" class="button button-white"
                                                        id="ok-button">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div>
@stop
@push('js')
    {{-- js start --}}
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/popper.js') }}"></script>
    <script src="{{ asset('/js/extensions/prox-calendar.js') }}"></script>
    <script>
        $(".rand-color").each(function() {
            var hue = 'rgb(' + (Math.floor((256 - 199) * Math.random()) + 200) + ',' + (Math.floor((256 - 199) *
                Math.random()) + 200) + ',' + (Math.floor((256 - 199) * Math.random()) + 200) + ')';
            $(this).css("background-color", hue);
        });
    </script>
    {{-- js end --}}
@endpush
