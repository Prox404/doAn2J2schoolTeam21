@extends('layout.master')
@push('title')
    <title>Schedule</title>
@endpush
@push('css')
    {{-- css start --}}
    <link rel="stylesheet" href="{{ asset('vendors/fontawesome/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/choices.js/choices.min.css') }}" />
    {{-- css end --}}
@endpush
@php
use Illuminate\Support\Carbon;
@endphp
@section('content')
    <div class="page-content">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <section class="row">
            <div class="card pt-3">
                <div class="card-header">
                    <h4 class="card-title">Lịch học</h4>
                </div>
                <div class="card-body">
                    @foreach ($schedules as $schedule)
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-12 card rand-color">
                                    <div class="row p-2">
                                        <div class="col-md-10">
                                            <ul class="list-group">
                                                <li class="list-group-item border-0 bg-transparent p-1">
                                                    <i class="fas fa-clock"></i>
                                                    @php
                                                        $date = new Carbon($schedule->date);
                                                    @endphp
                                                    @switch($date->isoFormat('E'))
                                                        @case(1)
                                                            {{ 'Thứ hai, ' . $schedule->date }}
                                                        @break

                                                        @case(2)
                                                            {{ 'Thứ ba, ' . $schedule->date }}
                                                        @break

                                                        @case(3)
                                                            {{ 'Thứ tư, ' . $schedule->date }}
                                                        @break

                                                        @case(4)
                                                            {{ 'Thứ năm, ' . $schedule->date }}
                                                        @break

                                                        @case(5)
                                                            {{ 'Thứ sáu, ' . $schedule->date }}
                                                        @break

                                                        @case(6)
                                                            {{ 'Thứ bảy, ' . $schedule->date }}
                                                        @break

                                                        @case(7)
                                                            {{ 'Chủ nhật, ' . $schedule->date }}
                                                        @break

                                                        @default
                                                            Không xác định
                                                    @endswitch
                                                </li>
                                                <li class="list-group-item border-0 bg-transparent p-1">
                                                    @if ($schedule->shift == 1)
                                                        <i class="fa fa-sun"> </i> 7:00 - 9:00
                                                    @else
                                                        <i class="fa fa-moon"> </i> 13h - 15:00
                                                    @endif
                                                </li>
                                                <li class="list-group-item border-0 bg-transparent p-1">
                                                    <i class="fa fa-book"> </i> {{ $schedule->subject_name }}
                                                </li>
                                                <li class="list-group-item border-0 bg-transparent p-1">
                                                    <i class="fas fa-users"> </i> {{ $schedule->class_name }}
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <div class="d-flex justify-content-end">
                                                <a href="{{route('schedule.changeSession', [$class_id, $schedule->id])}}" class="btn btn-light me-2"  data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Dời buổi học">
                                                    <i class="fas fa-retweet"></i>
                                                </a>
                                                
                                                <form action="{{ route('schedule.destroy', $schedule) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-light"  data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Xoá buổi học">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row">
                    <span class="d-flex justify-content-end">{{ $schedules->links() }}</span>
                </div>

            </div>
        </section>
    </div>
@stop
@push('js')
    {{-- js start --}}
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('vendors/choices.js/choices.min.js') }}"></script>
    <script src="{{ asset('js/pages/localest-all.js') }}"></script>
    <script>
        $(".rand-color").each(function() {
            var hue = 'rgb(' + (Math.floor((256 - 199) * Math.random()) + 200) + ',' + (Math.floor((256 - 199) *
                Math.random()) + 200) + ',' + (Math.floor((256 - 199) * Math.random()) + 200) + ')';
            $(this).css("background-color", hue);
        });
    </script>

    <script>
        $(function() {
            $(document).on('click', '.btn-edit', function() {
                let schedule_id = $(this).val();

                $.ajax({
                    type: "GET",
                    url: "getSchedule/" + schedule_id,
                    success: function(response) {
                        console.log(response);
                        $('#date').val(response.date);
                        $('#shift').val(response.shift);
                        $('#id').val(response.id);
                        $('#class_id').val(response.class_id);
                    }
                });

                $('#editModal').modal('show');
            });
        });
    </script>
    {{-- js end --}}
@endpush
