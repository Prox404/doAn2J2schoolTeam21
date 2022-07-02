@extends('layout.master')
@push('title')
<title>Home</title>
@endpush
@push('css')
    {{-- css start --}}
    {{-- css end --}}
@endpush
@section('content')
    <div class="page-content">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <section class="row">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Home chưa biết làm gì</h4>
                </div>
                <div class="card-body">

                </div>
            </div>
        </section>
    </div>
@stop
@push('js')
    {{-- js start --}}
    {{-- js end --}}
@endpush
