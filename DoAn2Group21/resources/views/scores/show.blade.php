@extends('layout.master')
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
                    <h4 class="card-title">Điểm</h4>
                </div>
                <div class="card-body">
                    @if (auth()->user()->level == 1)
                        @if (empty($scores))
                            Chưa có điểm đâu bru, lần sau quay lại nhé 🧡
                        @else
                            <table class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Hệ số</th>
                                        <th>Điểm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Điểm chuyên cần</td>
                                        <td>1</td>
                                        <td>{{ $scores->diligence ? $scores->diligence : 'Chưa cập nhật' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Quiz 1</td>
                                        <td>1</td>
                                        <td>{{ $scores->quiz1 ? $scores->quiz1 : 'Chưa cập nhật' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Quiz 2</td>
                                        <td>1</td>
                                        <td>{{ $scores->quiz2 ? $scores->quiz2 : 'Chưa cập nhật' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Midterm Test</td>
                                        <td>1.5</td>
                                        <td>{{ $scores->midterm ? $scores->midterm : 'Chưa cập nhật' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Final Test</td>
                                        <td>2</td>
                                        <td>{{ $scores->final ? $scores->final : 'Chưa cập nhật' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td></td>
                                        <td>{{ $scores->total ? $scores->total : 'Chưa cập nhật' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    @endif

                    @if (auth()->user()->level <= 4 && auth()->user()->level >= 2)
                        @if (empty($scores))
                            Chưa có điểm đâu bru, lần sau quay lại nhé 🧡
                        @else
                            <table class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Điểm chuyên cần</th>
                                        <th>Bài về nhà</th>
                                        <th>Quiz 1</th>
                                        <th>Quiz 2</th>
                                        <th>Midterm Test</th>
                                        <th>Final Test</th>
                                        <th>Tổng kết</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($scores as $score)
                                        <tr>
                                            <th>
                                                {{ $score['user_id'] }}
                                            </th>
                                            <th>
                                                {{ $score['name'] }}
                                            </th>
                                            <th>
                                                {{ $score['homework'] }}
                                            </th>
                                            <th>
                                                {{ $score['diligence'] }}
                                            </th>
                                            <th>
                                                {{ $score['quiz1'] }}
                                            </th>
                                            <th>
                                                {{ $score['quiz2'] }}
                                            </th>
                                            <th>
                                                {{ $score['midterm'] }}
                                            </th>
                                            <th>
                                                {{ $score['final'] }}
                                            </th>
                                            <th>
                                                {{ $score['total'] }}
                                            </th>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @endif
                </div>
            </div>
        </section>
    </div>
@stop
@push('js')
    {{-- js start --}}
    {{-- js end --}}
@endpush
