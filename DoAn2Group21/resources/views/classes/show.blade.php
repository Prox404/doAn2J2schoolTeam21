@extends('layout.master')
@push('title')
    <title>Classes</title>
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
            <div class="row">
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Thông tin lớp học</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-lg">
                                    <thead>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-bold"><strong>Tên lớp:</strong></td>
                                            <td class="text-bold-500">{{ $class_info->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold"><strong>Tên môn:</strong></td>
                                            <td class="text-bold-500">{{ $class_info->subjects->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold"><strong>Giảng viên:</strong></td>
                                            <td class="text-bold-500">{{ $class_info->teacher }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold"><strong>Số buổi học:</strong></td>
                                            <td class="text-bold-500">{{ $class_info->subjects->class_sessions }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold"><strong>Ca:</strong></td>
                                            <td class="text-bold-500">
                                                @if ($class_info->shift == 1)
                                                    7:00 - 9:00
                                                @elseif($class_info->shift == 2)
                                                    9:00 - 11:00
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold"><strong>Buổi học:</strong></td>
                                            <td class="text-bold-500">
                                                @php
                                                    $weekdayName = [];
                                                    foreach ($class_info->weekdays as $weekday) {
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
                                                    $weekdays = implode(', ', $weekdayName);
                                                @endphp
                                                {{ $weekdays }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <span class="d-flex justify-content-end">{{ $students->links() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Thành viên lớp</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-lg">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên</th>
                                            <th>Birthday</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td>{{ $student->id }}</td>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->birthday }}</td>
                                                <td>{{ $student->email }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row">
                                    <span class="d-flex justify-content-end">{{ $students->links() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>

@stop
@push('js')
    {{-- js start --}}
    {{-- js end --}}
@endpush
