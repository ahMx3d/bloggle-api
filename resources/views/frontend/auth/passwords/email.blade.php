@extends('layouts.app')

@section('content')
<section class="my_account_area pt--80 pb--55 bg--white">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-md-3">
                <div class="my__account__wrapper">
                    <h3 class="account__title">Reset Password</h3>
                    {!! Form::open(['route'=> 'password.email', 'method'=>'POST']) !!}
                        <div class="account__form">
                            <div class="input__box">
                                {!! Form::label('email', 'Email*') !!}
                                {!! Form::email('email', old('email', request()->email)) !!}
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <div class="form__btn">
                                    {!! Form::button('Send Password Reset Link', ['type'=>'submit']) !!}
                                </div>
                                <a
                                    href="{{ route('frontend.show_login_form') }}"
                                    class="forget_pass">Login?</a>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
