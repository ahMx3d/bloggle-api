@extends('layouts.admin')
@section('style')
<link
    rel="stylesheet"
    href="{{ asset('backend/vendor/summernote/summernote-bs4.min.css') }}" />
@endsection
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Create Page</h6>
        <div class="ml-auto">
            <a href="{{ route('admin.pages.index') }}" class="text-decoration-none">
                <span class="text">Pages Table</span>
                <span class="icon primary">
                    <i class="fa fa-home"></i>
                </span>
            </a>
        </div>
    </div>
    <div class="card-body">
        {!! Form::open(['route'=>'admin.pages.store', 'method'=>'POST', 'files'=>true]) !!}
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {!! Form::label('title', 'Title') !!}
                    {!! Form::text('title', old('title'), ['class'=>'form-control']) !!}
                    @error('title')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {!! Form::label('description', 'Description') !!}
                    {!! Form::textarea('description', old('description'), ['class'=>'form-control summernote']) !!}
                    @error('description')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                {!! Form::label('category_id', 'Category') !!}
                {!! Form::select('category_id', [''=>'---']+$categories, old('category_id'), ['class'=>'form-control']) !!}
                @error('category_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-6">
                {!! Form::label('status', 'Status') !!}
                {!! Form::select('status', ['1'=>'Active','0'=>'Pending'], old('status'), ['class'=>'form-control']) !!}
                @error('status')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row py-4">
            <div class="col-12">
                {!! Form::label('images', 'Images') !!}
                <br />
                <div class="file-loading">
                    {!! Form::file('images[]', ['id'=>'page-images', 'class'=>'file-input-overview', 'multiple'=>'multiple']) !!}
                    <span class="form-text text-muted">Image width should be 800X500</span>
                    @error('images')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::button('Add New', ['type' => 'submit', 'class'=>'btn btn-lg btn-block btn-primary']) !!}
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

            $('#page-images').fileinput({
                theme           : 'fas',
                maxFileCount    : 5,
                allowedFileTypes: ['image'],
                showCancel      : true,
                showRemove      : false,
                showUpload      : false,
                overwriteInitial: false,
            });
        });
    </script>
@endsection
