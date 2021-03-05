@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{ asset('frontend/summernote/summernote-bs4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('frontend/js/select2/css/select2.min.css') }}" />
@endsection
@section('content')
<!-- Start Blog Area -->
<div class="page-blog bg--white section-padding--lg blog-sidebar right-sidebar">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-12">
                <div class="contact-form-wrap">
                    <h2 class="contact__title">Edit Post</h2>
                    {!! Form::model($post, ['route'=>['user.posts.update', $post->id], 'method'=>'PUT', 'files'=>true]) !!}
                    {!! Form::token() !!}
                    <div class="form-group">
                        {!! Form::label('title', 'Title') !!}
                        {!! Form::text('title', old('title', $post->title), ['class'=>'form-control']) !!}
                        @error('title')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::label('description', 'Description') !!}
                        {!! Form::textarea('description', old('description', $post->description), ['class'=>'form-control summernote']) !!}
                        @error('description')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
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
                    <div class="row">
                        <div class="col-4">
                            {!! Form::label('category_id', 'Category') !!}
                            {!! Form::select('category_id', [''=>'---']+$categories->toArray(), old('category_id', $post->category_id), ['class'=>'form-control']) !!}
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
                            <div class="file-loading">
                                {!! Form::file('images[]', ['id'=>'post-images', 'multiple'=>'multiple']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="contact-btn">
                        {!! Form::button('Update Post Details', ['type' => 'submit','class'=>'btn btn-lg btn-block']) !!}
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
@section('script')
    <script src="{{ asset('frontend/js/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('frontend/summernote/summernote-bs4.min.js') }}"></script>
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
                                    "user.posts.media.destroy",
                                    [
                                        // $medium->post->slug,
                                        // $medium->file_name,
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
