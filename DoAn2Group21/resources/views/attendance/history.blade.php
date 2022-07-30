@extends('layout.master')
@push('title')
<title>Attendance</title>
@endpush
@push('css')
    {{-- css start --}}
    <link href="{{ asset('vendors/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/sl-1.4.0/datatables.min.css" />
    <link rel="stylesheet" href="{{ asset('vendors/choices.js/choices.min.css') }}" />
    <link rel="stylesheet" href="{{asset('css/pages/dripicons.css')}}">
    <link rel="stylesheet" href="{{asset('/vendors/dripicons/webfont.css')}}">
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
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Số buổi học</h4>
                </div>
                <div class="card-body">
                    @foreach ($schedules as $schedule)
                    <div class="btn-group btn-group-sm mb-2" role="group">
                            @php
                                $date = new Carbon($schedule->date);
                                $now = Carbon::now();
                            @endphp
                        <a href="{{$class->id}}/{{$schedule->id}}" class="btn 
                            @if ($date->gt($now))
                                btn-primary
                            @else
                                btn-light
                            @endif
                            ">
                            
                            @switch($date->isoFormat('E'))
                                @case(1)
                                    Mon,
                                    @break
                                @case(2)
                                    Tue,
                                    @break
                                @case(3)
                                    Wed,
                                    @break
                                @case(4)
                                    Thu,
                                    @break
                                @case(5)
                                    Fri,
                                    @break
                                @case(6)
                                    Sat,
                                    @break
                                @case(7)
                                    Sun,
                                    @break
                                @default
                                    Unknown, 
                            @endswitch

                            {{$schedule->date}}
                        </a>
                        <button type="button" class="btn"><i class="icon dripicons-cross"></i></button>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

@stop
@push('js')
    {{-- js start --}}
    {{-- js end --}}
@endpush