@extends('layouts.admin')
@section('style')
<link rel="stylesheet" href="{{ asset('backend/vendor/select2/css/select2.min.css') }}" />
<link rel="stylesheet" href="{{ asset('backend/vendor/summernote/summernote-bs4.min.css') }}" />
@endsection
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Edit Post</h6>
        <div class="ml-auto">
            <a href="{{ route('admin.posts.index') }}" class="text-decoration-none">
                <span class="text">Posts Table</span>
                <span class="icon primary">
                    <i class="fa fa-home"></i>
                </span>
            </a>
        </div>
    </div>
    <div class="card-body">
        {!! Form::model($post, ['route'=>['admin.posts.update', $post->id], 'method'=>'PUT', 'files'=>true]) !!}
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {!! Form::label('title', 'Title') !!}
                    {!! Form::text('title', old('title', $post->title), ['class'=>'form-control']) !!}
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
                    {!! Form::textarea('description', old('description', $post->description), ['class'=>'form-control summernote']) !!}
                    @error('description')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {!! Form::label('tags', 'Tags') !!}
                    <div class="d-flex justify-content-between align-items-center">
                    {!! Form::select('tags[]', $tags, old('tags', $post->tags),
                    ['class'=>'form-control tags mr-3', 'multiple'=>'multiple', 'id'=>'tags']) !!}
                    {!! Form::button('Bulk',
                    ['class'=>'btn btn-primary btn-xs','id'=>'tags-btn-bulk','type'=>'button']) !!}
                    {!! Form::button('Undo',
                    ['class'=>'btn btn-danger btn-xs','id'=>'tags-btn-undo','type'=>'button']) !!}
                    </div>
                    @error('tags')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                {!! Form::label('category_id', 'Category') !!}
                {!! Form::select('category_id', [''=>'---']+$categories, old('category_id', $post->category_id), ['class'=>'form-control']) !!}
                @error('category_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-4">
                {!! Form::label('comment_able', 'Allow Comments?') !!}
                {!! Form::select('comment_able', ['1'=>'Yes','0'=>'No'], old('comment_able', $post->comment_able), ['class'=>'form-control']) !!}
                @error('comment_able')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-4">
                {!! Form::label('status', 'Status') !!}
                @if ($post->status=='Active')
                {!! Form::select('status', ['1'=>'Active','0'=>'Pending'], old('status', 1), ['class'=>'form-control']) !!}
                @else
                {!! Form::select('status', ['1'=>'Active','0'=>'Pending'], old('status', 0), ['class'=>'form-control']) !!}
                @endif
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
                    {!! Form::file('images[]', ['id'=>'post-images', 'class'=>'file-input-overview', 'multiple'=>'multiple']) !!}
                    <span class="form-text text-muted">Image width should be 800X500</span>
                    @error('images')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::button('Update Post', ['type' => 'submit', 'class'=>'btn btn-lg btn-block btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
@section('script')
    <script src="{{ asset('backend/vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        $(function () {
            $('.tags').select2({
                minimumResultsForSearch: Infinity,
                tags                   : true,
                closeOnSelect          : false
            });

            $('#tags-btn-bulk').click(function () {
                $('#tags > option').prop('selected', 'selected');
                $('#tags').trigger('change');
            });
            $('#tags-btn-undo').click(function () {
                $('#tags > option').prop('selected', '');
                $('#tags').trigger('change');
            });

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

            $('#post-images').fileinput({
                theme           : 'fa',
                maxFileCount    : {{ 5- ($post->media->count()) }},
                allowedFileTypes: ['image'],
                showCancel      : true,
                showRemove      : false,
                showUpload      : false,
                overwriteInitial: false,
                initialPreview  : [
                    @if($post->media->count()>0)
                        @foreach($post->media as $medium)
                            '{{ asset("assets/posts/{$medium->file_name}") }}',
                        @endforeach
                    @endif
                ],
                initialPreviewAsData  : true,
                initialPreviewFileType: 'image',
                initialPreviewConfig  : [
                    @if($post->media->count()>0)
                        @foreach($post->media as $medium)
                            {
                                caption: '{{ $medium->file_name }}',
                                size   : '{{ $medium->file_size }}',
                                width  : '120px',
                                url    : '{{ route(
                                    "admin.post.media.destroy",
                                    [
                                        $medium->id,
                                        "_token"=>csrf_token()
                                    ]
                                ) }}',
                                key    : '{{ $medium->id }}',
                            },
                        @endforeach
                    @endif
                ],
            });
        });
    </script>
@endsection
