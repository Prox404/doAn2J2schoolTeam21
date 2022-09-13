@extends('layout.master')
@push('title')
    <title>Attendance</title>
@endpush
@push('css')
    {{-- css start --}}
    <link rel="stylesheet" href="{{ asset('vendors/chartjs/Chart.min.css') }}">
    {{-- css end --}}
@endpush
@section('content')
    <style>
        td.min {
            width: 1%;
            white-space: nowrap;
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
                    <h4 class="card-title">Điểm danh nè bru</h4>
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
                                    <input type="date" id="date" class="form-control" name="date"
                                        value="{{ $schedules->date }}">
                                </div>
                            </div>
                        </div>
                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
                                    <th>Có mặt</th>
                                    <th>Vắng</th>
                                    <th>Phép</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    <tr>
                                        <td>{{ $student->id }}</td>
                                        <td>{{ $student->name }}</td>
                                        
                                            @if(isset($student->attendance()->where('schedule_id',$schedules->id)->first()->user_id))
                                            <td class="min">
                                            <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                                    <input name="attendance[{{ $student->id }}]" class="leading-tight form-check-input "
                                                        type="radio" value="1" checked>
                                                </label>
                                            </td>
                                            <td class="min">
                                                <label class="ml-4 block text-gray-500 font-semibold">
                                                    <input name="attendance[{{ $student->id }}]" class="leading-tight form-check-input "
                                                        type="radio" value="2"
                                                        {{ $student->attendance()->where('schedule_id', $schedules->id)->first()->status == 2 ? 'checked' : '' }}>
                                                </label>
                                            </td>
                                            <td class="min">
                                                <label class="ml-4 block text-gray-500 font-semibold">
                                                    <input name="attendance[{{ $student->id }}]" class="leading-tight form-check-input "
                                                        type="radio" value="3"
                                                        {{ $student->attendance()->where('schedule_id', $schedules->id)->first()->status == 3 ? 'checked' : '' }}>
                                                </label>
                                            </td>
            
                                            @else
                                            <td class="min">
                                                <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                                    <input name="attendance[{{ $student->id }}]" class="leading-tight form-check-input "
                                                        type="radio" value="1" checked>
                                                </label>
                                            </td>
                                            <td class="min">
                                                <label class="ml-4 block text-gray-500 font-semibold">
                                                    <input name="attendance[{{ $student->id }}]" class="leading-tight form-check-input "
                                                        type="radio" value="2">
                                                </label>
                                            </td>
                                            <td class="min">
                                                <label class="ml-4 block text-gray-500 font-semibold">
                                                    <input name="attendance[{{ $student->id }}]" class="leading-tight form-check-input "
                                                        type="radio" value="3">
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

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tổng quan nè</h4>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="chart" style="width:100%; height:200px;"></canvas>
                    </div>
                </div>
            </div>
        </section>
    </div>

@stop
@push('js')
    {{-- js start --}}
    <script src="{{ asset('js/pages/ui-chartjs.js') }}"></script>
    <script src="{{ asset('vendors/chartjs/Chart.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        var present = 0;
        var absent = 0;
        var licensed = 0;
        const ctx = document.getElementById('chart').getContext('2d');

        $('input[type=radio]').each(function() {
            if ($(this).is(':checked')) {
                if ($(this).val() == "1") {
                    present++;
                }
                if ($(this).val() == "2") {
                    absent++;
                }
                if ($(this).val() == "3") {
                    licensed++;
                }
            }
        });

        data = {
            datasets: [{
                data: [present, absent, licensed],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56'
                ],
            }],
            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Có mặt',
                'Vắng',
                'Có phép'
            ]
        };


        let myChart = new Chart(ctx, {
            type: 'doughnut',
            data: data

        });
    </script>
    <script>
        $(document).ready(function() {
            $('input[type=radio]').change(function() {
                present = 0;
                absent = 0;
                licensed = 0;
                $('input[type=radio]').each(function() {
                    if ($(this).is(':checked')) {
                        if ($(this).val() == "1") {
                            present++;
                        }
                        if ($(this).val() == "2") {
                            absent++;
                        }
                        if ($(this).val() == "3") {
                            licensed++;
                        }
                    }
                });

                console.log(present + " " + absent + " " + licensed);

                myChart.data = {
                    datasets: [{
                        data: [present, absent, licensed],
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56'
                        ],
                    }],
                    // These labels appear in the legend and in the tooltips when hovering different arcs
                    labels: [
                        'Có mặt',
                        'Vắng',
                        'Có phép'
                    ]
                };

                myChart.update();
            });
        });
    </script>

    {{-- js end --}}
@endpush
