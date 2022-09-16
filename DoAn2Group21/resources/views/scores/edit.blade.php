@extends('layout.master')
@push('title')
    <title>Scores</title>
@endpush
@push('css')
    {{-- css start --}}
    <link rel="stylesheet" href="{{ asset('vendors/chartjs/Chart.min.css') }}">
    {{-- css end --}}
@endpush
@section('content')
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
    <div class="page-content">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <section class="row">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Chấm điểm nè bru</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('score.store', $id) }}">
                        @csrf
                        @method('PUT')
                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Họ và tên</th>
                                    <th>Homework</th>
                                    <th>Quiz 1</th>
                                    <th>Quiz 2</th>
                                    <th>Midterm Exam</th>
                                    <th>Midterm Exam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    <tr>
                                        <td>{{ $student->id }}</td>
                                        <td>{{ $student->name }}</td>

                                        @if (isset(
                                            $student->scores()->where('class_id', $id)->first()->user_id))
                                            <td>
                                                <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                                    <input name="scores[homework][{{ $student->id }}]"
                                                        class="form-control form-control-sm" type="number"
                                                        value="{{ $student->scores()->where('class_id', $id)->first()->homework }}">
                                                </label>
                                            </td>
                                            <td>
                                                <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                                    <input name="scores[quiz1][{{ $student->id }}]"
                                                        class="form-control form-control-sm" type="number"
                                                        value="{{ $student->scores()->where('class_id', $id)->first()->quiz1 }}">
                                                </label>
                                            </td>
                                            <td>
                                                <label class="ml-4 block text-gray-500 font-semibold">
                                                    <input name="scores[quiz2][{{ $student->id }}]"
                                                        class="form-control form-control-sm" type="number"
                                                        value="{{ $student->scores()->where('class_id', $id)->first()->quiz2 }}">
                                                </label>
                                            </td>
                                            <td>
                                                <label class="ml-4 block text-gray-500 font-semibold">
                                                    <input name="scores[midterm][{{ $student->id }}]"
                                                        class="form-control form-control-sm" type="number"
                                                        value="{{ $student->scores()->where('class_id', $id)->first()->midterm }}">
                                                </label>
                                            </td>
                                            <td>
                                                <label class="ml-4 block text-gray-500 font-semibold">
                                                    <input name="scores[final][{{ $student->id }}]"
                                                        class="form-control form-control-sm" type="number"
                                                        value="{{ $student->scores()->where('class_id', $id)->first()->final }}">
                                                </label>
                                            </td>
                                        @else
                                            <td>
                                                <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                                    <input name="scores[homework][{{ $student->id }}]"
                                                        class="form-control form-control-sm" type="number" value="0.0">
                                                </label>
                                            </td>
                                            <td>
                                                <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                                    <input name="scores[quiz1][{{ $student->id }}]"
                                                        class="form-control form-control-sm" type="number" value="0.0">
                                                </label>
                                            </td>
                                            <td>
                                                <label class="ml-4 block text-gray-500 font-semibold">
                                                    <input name="scores[quiz2][{{ $student->id }}]"
                                                        class="form-control form-control-sm" type="number" value="0.0">
                                                </label>
                                            </td>
                                            <td>
                                                <label class="ml-4 block text-gray-500 font-semibold">
                                                    <input name="scores[midterm][{{ $student->id }}]"
                                                        class="form-control form-control-sm" type="number" value="0.0">
                                                </label>
                                            </td>
                                            <td>
                                                <label class="ml-4 block text-gray-500 font-semibold">
                                                    <input name="scores[final][{{ $student->id }}]"
                                                        class="form-control form-control-sm" type="number" value="0.0">
                                                </label>
                                            </td>
                                        @endif


                                        <input type="hidden" name="student_id[]" value="{{ $student->id }}">
                                    </tr>
                                @endforeach
                        </table>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-1 mb-1">Hoàn thành</button>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

@stop
@push('js')
    {{-- js start --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    {{-- js end --}}
@endpush
