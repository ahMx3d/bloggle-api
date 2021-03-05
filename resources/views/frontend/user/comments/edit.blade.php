@extends('layouts.app')
@section('content')
<!-- Start Blog Area -->
<div class="page-blog bg--white section-padding--lg blog-sidebar right-sidebar">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-12">
                <div class="contact-form-wrap">
                    <h2 class="contact__title">Edit Comment</h2>
                    {{-- <h4>{{ $comment->post->title }}</h4> --}}
                    {!! Form::model($comment, ['route'=>['user.comment.update', $comment->id], 'method'=>'PUT']) !!}
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                {!! Form::label('name', 'Name*') !!}
                                {!! Form::text('name', old('name', $comment->name), ['class'=>'form-control']) !!}
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                {!! Form::label('email', 'Email*') !!}
                                {!! Form::email('email', old('email', $comment->email), ['class'=>'form-control']) !!}
                                @error('email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                {!! Form::label('status', 'Status*') !!}
                                @if ($comment->status=='Active')
                                {!! Form::select('status', ['1'=>'Active','0'=>'Pending'], old('status', 1), ['class'=>'form-control']) !!}
                                @else
                                {!! Form::select('status', ['1'=>'Active','0'=>'Pending'], old('status', 0), ['class'=>'form-control']) !!}
                                @endif
                                {!! Form::hidden('validation', $comment->post->slug, ['class'=>'d-none']) !!}
                                @error('status')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                {!! Form::label('url', 'Website') !!}
                                {!! Form::url('url', old('url', $comment->url), ['class'=>'form-control']) !!}
                                @error('url')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                {!! Form::label('comment', 'Comment*') !!}
                                {!! Form::textarea('comment', old('comment', $comment->comment), ['class'=>'form-control', 'rows'=>'5']) !!}
                                @error('comment')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="contact-btn">
                        {!! Form::button('Update Comment Details', ['type' => 'submit','class'=>'btn btn-lg btn-block']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="col-lg-3 col-12 md-mt-40 sm-mt-40">
                @include('partials.frontend.user.sidebar')
            </div>
        </div>
    </div>
</div>
<!-- End Blog Area -->
@endsection
