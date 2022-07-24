@extends('layout.master')
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
                    <h4 class="card-title">Sửa môn học</h4>
                </div>
                <div class="card-body">
                    <form class="form form-vertical" action="{{ Route('subject.update', $subject) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name-vertical">Subject Name</label>
                                        <input type="text" id="name-vertical" class="form-control" name="name"
                                            value="{{$subject->name}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="start-date-vertical">Start Date</label>
                                        <input type="date" id="start-date-vertical" class="form-control" name="start_date"
                                            value="{{$subject->start_date}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="end-date-vertical">Numbers Sessions</label>
                                        <input type="number" id="end-date-vertical" class="form-control" name="class_sessions"
                                            value="{{$subject->class_sessions}}">
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                </div>
                            </div>
                        </div>
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