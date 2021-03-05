@extends('layouts.app')
@section('content')
<!-- Start Blog Area -->
<section class="wn_contact_area bg--white pt--80 pb--80">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-12 md-mt-40">
                <div class="contact-form-wrap">
                    <h2 class="contact__title text-center">Edit Password</h2>
                    {!! Form::open(['route'=>['frontend.profile.password.update', auth()->user()->username], 'name'=>'user_password', 'id'=>'user_password', 'method'=>'PUT']) !!}
                    <div class="single-contact-form">
                        {!! Form::password('current_password', ['placeholder'=>'Current Password*', 'autocomplete'=>'new-password']) !!}
                        @error('current_password')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="single-contact-form">
                        {!! Form::password('password', ['placeholder'=>'New Password*', 'autocomplete'=>'new-password']) !!}
                        @error('password')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="single-contact-form">
                        {!! Form::password('password_confirmation', ['placeholder'=>'Confirm Password*', 'autocomplete'=>'new-password']) !!}
                        @error('password_confirmation')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="contact-btn mt-5">
                        {!! Form::button('Update', ['name'=>'update_password', 'type' => 'submit', 'class'=>'btn btn-lg']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="col-lg-3 col-12 md-mt-40 sm-mt-40">
                @include('partials.frontend.user.sidebar')
            </div>
        </div>
    </div>
</section>
<!-- End Blog Area -->
@endsection
