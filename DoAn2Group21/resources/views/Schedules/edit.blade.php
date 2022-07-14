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
                                <div class="col-md-2 d-flex justify-content-end align-self-baseline">
                                    <span class="text-secondary mb-2 float-right">
                                        @switch($schedule->weekday_id)
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

                                    </span>
                                </div>
                                <div class="col-md-10 card rand-color">
                                    <div class="row p-2">
                                        <div class="col-md-10">
                                            <ul class="list-group">
                                                <li class="list-group-item border-0 bg-transparent p-1">
                                                    @if ($schedule->shift == 1)
                                                        <i class="fa fa-sun"> </i> 7:00 - 8:15
                                                    @else
                                                        <i class="fa fa-moon"> </i> 13h - 15:15
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
                                                <button value="{{ $schedule->id }}" class="btn btn-light btn-edit me-2">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('schedule.destroy', $schedule) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-light">
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

        <div class="modal fade text-left modal-borderless" id="editModal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa lịch</h5>
                        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    <form action="{{ route('schedule.update') }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="basicInput">Ngày</label>
                                <input type="date" class="form-control" id="date" name="date">
                            </div>
                            <div class="form-group">
                                <label for="shift">Ca</label>
                                <select class="form-select" id="shift" name="shift">
                                    <option value="1">Sáng</option>
                                    <option value="2">Chiều</option>
                                </select>
                            </div>
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="class_id" id="class_id">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" class="btn btn-primary ml-1">
                                <span>Accept</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@push('js')
    {{-- js start --}}
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
