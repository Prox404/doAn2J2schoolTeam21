@extends('layout.master')
@push('title')
<title>Attendance</title>
@endpush
@push('css')
    {{-- css start --}}
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
                    <form method="post" action="{{ route('attendance.store') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="class_id" value="{{ $class_id }}" />
                        <input type="hidden" name="schedule_id" value="{{ $schedules->id }}" />
                        <div class="col-md-6">
                            <div class="form-group row align-items-center">
                                <div class="col-lg-2 col-3">
                                    <label class="col-form-label">Ngày</label>
                                </div>
                                <div class="col-lg-10 col-9">
                                    <input type="date" id="date" class="form-control" name="date" value="{{$schedules->date}}">
                                </div>
                            </div>
                        </div>
                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
                                    <th style="text-align:right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                <tr>
                                    <td>{{$student->id}}</td>
                                    <td>{{$student->name}}</td>
                                    <td align="right">
                                        <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                            <input name="attendance[{{ $student->id }}]" class="leading-tight" type="radio"
                                                   value="1" checked>
                                            <span class="text-success">Có mặt</span>
                                        </label>
                                        <label class="ml-4 block text-gray-500 font-semibold">
                                            <input name="attendance[{{ $student->id }}]" class="leading-tight" type="radio"
                                                   value="2" {{ $student->attendance()->first()->status == 2 ? 'checked' : '' }}>
                                            <span class="text-danger">Vắng</span>
                                        </label>
                                        <label class="ml-4 block text-gray-500 font-semibold">
                                            <input name="attendance[{{ $student->id }}]" class="leading-tight" type="radio"
                                                   value="3" {{ $student->attendance()->first()->status == 3 ? 'checked' : '' }}>
                                            <span class="text-success">Phép</span>
                                        </label>
                                    </td>
                                    <input type="hidden" name="student_id[]" value="{{ $student->id }}">
                                </tr>
                                @endforeach
                        </table>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                    
                </div>
            </div>
        </section>
    </div>

@stop
@push('js')
    {{-- js start --}}
    {{-- js end --}}
@endpush
