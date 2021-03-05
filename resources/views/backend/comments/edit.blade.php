@extends('layouts.admin')
@section('style')
<link
    rel="stylesheet"
    href="{{ asset('backend/vendor/summernote/summernote-bs4.min.css') }}" />
@endsection
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Edit Comment</h6>
        <div class="ml-auto">
            <a href="{{ route('admin.post_comments.index') }}" class="text-decoration-none">
                <span class="text">Comments Table</span>
                <span class="icon primary">
                    <i class="fa fa-home"></i>
                </span>
            </a>
        </div>
    </div>
    <div class="card-body">
        {!! Form::model($comment, ['route'=>['admin.post_comments.update', $comment->id],
        'method'=>'PUT']) !!}
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    {!! Form::label('name', 'Name') !!}
                    {{ ($comment->user_id)? '(Member)': '' }}
                    {!! Form::text('name', old('name', $comment->name), [
                        'class'=>'form-control']) !!}
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {!! Form::label('email', 'Email') !!}
                    {!! Form::email('email', old('email', $comment->email),
                    ['class'=>'form-control']) !!}
                    @error('email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {!! Form::label('url', 'URL') !!}
                    {!! Form::url('url', old('url', $comment->url),
                    ['class'=>'form-control']) !!}
                    @error('url')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                {!! Form::label('ip_address', 'IP Address') !!}
                {!! Form::text('ip_address', old('ip_address', $comment->ip_address),
                    ['class'=>'form-control']) !!}
                @error('ip_address')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-6">
                {!! Form::label('status', 'Status') !!}
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
        <div class="row py-4">
            <div class="col-12">
                {!! Form::label('comment', 'Comment') !!}
                {!! Form::textarea('comment', old('comment', $comment->comment),
                    ['class'=>'form-control summernote', 'rows'=>5]) !!}
                @error('comment')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="form-group">
            {!! Form::button('Update Comment', ['type' => 'submit', 'class'=>'btn btn-lg btn-block btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
@section('script')
    <script src="{{ asset('backend/vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        $(function () {
            $('.summernote').summernote({
                tabsize    : 2,
                height     : 200,
                toolbar    : [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
            const markupStr = $('.summernote').summernote('code');
            $('.summernote').summernote('code', markupStr);
        });
    </script>
@endsection
