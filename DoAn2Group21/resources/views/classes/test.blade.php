@extends('layout.master')
@push('title')
    <title>Classes</title>
@endpush
@push('css')
    {{-- css start --}}
    <link href="{{ asset('vendors/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/sl-1.4.0/datatables.min.css" />
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
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thêm nâng cao</h4>
                </div>
                <div class="card-body">
                    <div class="input-group" style="width:40%;">
                        <form action="{{ route('user.advancedImport') }}" method="POST" enctype="multipart/form-data"
                            class="d-flex justify-content-start">
                            @csrf
                            <div class="input-group me-2">
                                <label class="input-group-text" for="inputGroupFile01"><i class="bi bi-upload"></i></label>
                                <input type="file" class="form-control" id="user-file" name="user_file"
                                    accept=".xlsx, .xls, .csv, .ods">
                            
                            </div>
                            <button type="submit" class="btn btn-info"
                                OnClick="return confirm('Are u sủe ?')">Import</button>
                            
                        </form>
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
