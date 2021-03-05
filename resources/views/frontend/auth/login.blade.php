@extends('layouts.app')
@section('style')
    <style>
        .facebook{
            background    : rgba(0, 0, 0, 0) none repeat scroll 0 0;
            border        : 2px solid #333;
            color         : #333;
            display       : inline-block;
            font-size     : 12px;
            font-weight   : 700;
            line-height   : 34px;
            padding       : 2px 20px 0;
            text-transform: uppercase;
            transition    : all 0.4s ease 0s;
        }
        .facebook:hover{
            background  : rgba(0, 0, 0, 0) none repeat scroll 0 0;
            border-color: #e59285;
            color       : #e59285;
        }
    </style>
@endsection
@section('content')
<section class="my_account_area pt--80 pb--55 bg--white">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-md-3">
                <div class="my__account__wrapper">
                    <h3 class="account__title">Login</h3>
                    {!! Form::open(['route'=> 'frontend.login', 'method'=>'POST']) !!}
                        <div class="account__form">
                            <div class="input__box">
                                {!! Form::label('username', 'Username*') !!}
                                {!! Form::text('username', old('username', request()->username)) !!}
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input__box">
                                {!! Form::label('password', 'Password*') !!}
                                {!! Form::password('password') !!}
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form__btn d-flex justify-content-between">
                                <div class="p-0 m-0">
                                {!! Form::button('Login', ['type'=>'submit']) !!}
                                <a
                                    href="{{ route('auth.provider.redirect', 'facebook') }}"
                                    class="facebook">
                                    By Facebook
                                </a>
                                    <label class="label-for-checkbox">
                                        <input
                                            class="input-checkbox"
                                            type="checkbox"
                                            name="remember"
                                            id="remember"
                                            {{ old('remember') ? 'checked' : '' }} />
                                        <span>Remember me</span>
                                    </label>
                                </div>
                                {{-- <a
                                    href="{{ route('password.request') }}"
                                    class="forget_pass my-2">Lost your password?</a> --}}
                            </div>
                            {{-- <div class="my-2">
                                <a
                                    href="{{ route('auth.provider.redirect', 'facebook') }}"
                                    class="btn btn-block btn-primary text-white">
                                    Facebook
                                </a>
                            </div> --}}
                            <a
                                href="{{ route('password.request') }}"
                                class="forget_pass my-0">Lost your password?</a>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
