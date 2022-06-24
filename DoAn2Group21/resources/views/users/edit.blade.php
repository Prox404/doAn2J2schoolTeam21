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
                    <h4 class="card-title">Quản lý sinh viên</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('user.update', $user)}}" class="form form-vertical">
                        @csrf
                        @method('PUT')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name-vertical">First Name</label>
                                        <input type="text" id="name-vertical" class="form-control" name="name" value="{{$user->name}}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="email-id-vertical">Email</label>
                                        <input type="email" id="email-id-vertical" class="form-control" name="email" value="{{$user->email}}">
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