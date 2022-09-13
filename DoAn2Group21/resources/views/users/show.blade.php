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
        <style>
            .hide {
                display: none;
            }
        </style>
        <section class="row">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tài khoản</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('user.storeUser', $user) }}" class="form form-vertical">
                        @csrf
                        @method('PUT')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" class="form-control" name="name"
                                            value="{{ $user->name }}" disabled>
                                    </div>
                                    @if ($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="birthday">Birthday</label>
                                        <input type="date" id="birthday" class="form-control" name="birthday"
                                            value="{{ $user->birthday }}" disabled>
                                    </div>
                                    @if ($errors->has('birthday'))
                                            <span class="text-danger">{{ $errors->first('birthday') }}</span>
                                        @endif
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" class="form-control" name="email"
                                            value="{{ $user->email }}" disabled>
                                    </div>
                                    @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                </div>
                                <div class="list-unstyled mb-0">
                                    {{-- <div class="form-check">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="form-check-input form-check-primary" name="isChangePassword" id="changePassword">
                                            <label class="form-check-label" for="changePassword">Thay đổi mật khẩu</label>
                                        </div>
                                    </div> --}}
                                    <div class="form-check form-check-lg d-flex align-items-end">
                                        <input class="form-check-input me-2" type="checkbox" name="isChangePassword" id="changePassword">
                                        <label class="form-check-label text-gray-600" for="isChangePassword">
                                            Thay đổi mật khẩu
                                        </label>
                                        @if ($errors->has('isChangePassword'))
                                            <span class="text-danger">{{ $errors->first('isChangePassword') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 hide" id="editPassword">
                                    <div class="form-group">
                                        <label for="currentPassword">Current password</label>
                                        <input type="password" id="currentPassword" class="form-control" name="currentPassword" disabled>
                                        @if ($errors->has('currentPassword'))
                                            <span class="text-danger">{{ $errors->first('currentPassword') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" class="form-control" name="password" disabled>
                                        @if ($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="rePassword">Re-Password</label>
                                        <input type="password" id="rePassword" class="form-control" name="rePassword" disabled>
                                        @if ($errors->has('rePassword'))
                                            <span class="text-danger">{{ $errors->first('rePassword') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="button" id="edit" class="btn btn-primary me-1 mb-1">Edit</button>
                                    <button type="button" id="cancel"
                                        class="btn btn-primary me-1 mb-1 hide">Cancel</button>
                                    <button type="submit" id="submit" onClick="return validate()" class="btn btn-primary me-1 mb-1 submit hide">Submit</button>
                                    <button type="reset"
                                        class="btn btn-light-secondary me-1 mb-1 submit hide">Reset</button>
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
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#cancel').click(function() {
                $('.submit').addClass('hide');
                $('#edit').removeClass('hide');
                $('#cancel').addClass('hide');
                $('.form-control').prop('disabled', true);
            });
            $('#edit').click(function() {
                $('.submit').removeClass('hide');
                $('#edit').addClass('hide');
                $('#cancel').removeClass('hide');
                $('.form-control').prop('disabled', false);
            });

            $('#changePassword').change(function() {
                $('#editPassword').toggleClass('hide');
            });
        });

        function validate(){
            let flag = true;
            let isChangePassword = $('#changePassword').is(':checked');
            if (isChangePassword) {
                var password = $("#password").val();
                var confirmPassword = $('#rePassword').val();
                if (password != confirmPassword || password == '') {
                    alert("Passwords do not match.");
                    flag = false;
                }
            }
            let name = $('#name').val();
            let birthday = $('#birthday').val();
            let email = $('#email').val();
            if (name == '' || birthday == '' || email == '') {
                alert('Please fill in all fields');
                flag = false;
            }
            let currentPassword = $('#currentPassword').val();
            if (isChangePassword && currentPassword == '') {
                alert('Please fill in current password fields');
                flag = false;
            }
            return flag;
        }
    </script>
    {{-- js end --}}
@endpush
