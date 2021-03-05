@extends('layouts.app')
@section('content')
<!-- Start Blog Area -->
<div class="page-blog bg--white section-padding--lg blog-sidebar right-sidebar">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-12">
                <div class="contact-form-wrap">
                    <h2 class="contact__title">Edit Information</h2>
                    {!! Form::open(['route'=>['frontend.profile.info.update', auth()->user()->username], 'name'=>'user_info', 'id'=>'user_info', 'method'=>'PUT', 'files'=>true]) !!}
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                {!! Form::label('name', 'Name*') !!}
                                {!! Form::text('name', old('name', auth()->user()->name), ['class'=>'form-control']) !!}
                                @error('name')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                {!! Form::label('email', 'Email*') !!}
                                {!! Form::email('email', old('email', auth()->user()->email), ['class'=>'form-control']) !!}
                                @error('email')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                {!! Form::label('mobile', 'Mobile*') !!}
                                {!! Form::text('mobile', old('mobile', auth()->user()->mobile), ['class'=>'form-control']) !!}
                                @error('mobile')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                {!! Form::label('receive_email', 'Receive Email*') !!}
                                {!! Form::select('receive_email', ['1'=>'Yes','0'=>'No'], old('receive_email', auth()->user()->receive_email), ['class'=>'form-control']) !!}
                                @error('receive_email')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                {!! Form::label('bio', 'Bio*') !!}
                                {!! Form::textarea('bio', old('bio', auth()->user()->bio), ['class'=>'form-control']) !!}
                                @error('bio')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- @if (auth()->user()->user_image)
                        <div class="col-12">
                            <img
                            src="{{ asset('assets/users/'.auth()->user()->user_image) }}"
                            class="img-fluid"
                            width="150"
                            alt="{{ Str::limit(auth()->user()->name, 10, '...') }}" />
                        </div>
                        @endif --}}
                        <div class="col-12">
                            <div class="form-group">
                                {!! Form::label('user_image', 'User Image') !!}
                                @if (auth()->user()->user_image)
                                {!! Form::file('user_image', ['class'=>'custom-file', 'id'=>'profile_image']) !!}
                                @else
                                {!! Form::file('user_image', ['class'=>'custom-file',]) !!}
                                @endif
                                @error('user_image')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {{-- <div class="form-group">
                                {!! Form::submit('Update Info', ['name'=>'update_info', 'class'=>'btn btn-primary btn-lg btn-block']) !!}
                            </div> --}}
                            <div class="contact-btn">
                                {!! Form::button('Update Information', ['name'=>'update_info','type' => 'submit', 'class'=>'btn btn-lg btn-block']) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                {{-- <hr />
                <div class="blog-page">
                    <div class="page__header">
                        <h3>Edit Password</h3>
                    </div>
                    {!! Form::open(['route'=>['frontend.profile.password.update', auth()->user()->username], 'name'=>'user_password', 'id'=>'user_password', 'method'=>'PUT']) !!}
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                {!! Form::label('current_password', 'Current Password*') !!}
                                {!! Form::password('current_password', ['class'=>'form-control', 'autocomplete'=>'new-password']) !!}
                                @error('current_password')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                {!! Form::label('password', 'New Password*') !!}
                                {!! Form::password('password', ['class'=>'form-control', 'autocomplete'=>'new-password']) !!}
                                @error('password')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                {!! Form::label('password_confirmation', 'Re-Password*') !!}
                                {!! Form::password('password_confirmation', ['class'=>'form-control', 'autocomplete'=>'new-password']) !!}
                                @error('password_confirmation')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                {!! Form::submit('Update Password', ['name'=>'update_password', 'class'=>'btn btn-primary btn-lg btn-block']) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div> --}}
            </div>
            <div class="col-lg-3 col-12 md-mt-40 sm-mt-40">
                @include('partials.frontend.user.sidebar')
            </div>
        </div>
    </div>
</div>
<!-- End Blog Area -->
@endsection
@section('script')
    <script>
        $(function () {
            $('#profile_image').fileinput({
                theme           : 'fa',
                maxFileCount    : 1,
                allowedFileTypes: ['image'],
                showCancel      : true,
                showRemove      : false,
                showUpload      : false,
                overwriteInitial: false
            });
        });
    </script>
@endsection
