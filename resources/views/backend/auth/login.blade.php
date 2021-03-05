@extends('layouts.auth-admin')
@section('style')
    <style>
        body{
            /* background: #3E3E3E !important; */
            background: #6E8683 !important;
        }
        .login-heading{
            color: black !important;
        }
        input[type="submit"]{
            background: #FFF !important;
            border    : 3px solid black !important;
            color     : black !important;
        }
        input[type="submit"]:hover{
            border    : 3px solid #ce6555 !important;
            color     : #ce6555 !important;
        }
        a{
            color     : #ce6555 !important;
        }
    </style>
@endsection
@section('content')
<!-- Outer Row -->
<div class="row justify-content-center">

    <div class="col-xl-10 col-lg-12 col-md-9 pt-5">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4 login-heading">Welcome Back!</h1>
                            </div>
                            {!! Form::open(['route'=>'admin.login','method'=>'POST','class'=>'user']) !!}
                            <div class="text-center form-group">
                                    {!! Form::text(
                                        'username',
                                        old('username', request('username', null)),
                                        [
                                            'class'        => 'form-control form-control-user',
                                            'placeholder'  => 'Enter Username...',
                                            'id'           => 'username',
                                            'required'     => true,
                                            'autocomplete' => 'username',
                                            'autofocus'    => true
                                        ]
                                    ) !!}
                                    @error('username')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="text-center form-group">
                                    {!! Form::password(
                                        'password',
                                        [
                                            'class'        => 'form-control form-control-user',
                                            'placeholder'  => 'Enter Password...',
                                            'id'           => 'password',
                                            'required'     => true,
                                            'autocomplete' => 'new-password'
                                        ]
                                    ) !!}
                                    @error('password')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">
                                        <input
                                            class = "custom-control-input"
                                            type  = "checkbox"
                                            name  = "remember"
                                            id    = "remember"
                                            {{ old('remember') ? 'checked' : '' }} />
                                        <label class="custom-control-label" for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                                {!! Form::submit('Login', ['type'=>'submit','class'=>'btn btn-primary btn-user btn-block']) !!}
                            {!! Form::close() !!}
                            <hr>
                            <div class="text-center">
                                <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
