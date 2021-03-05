@extends('layouts.app')
@section('content')
<!-- Start Blog Area -->
<div class="page-blog bg--white section-padding--lg blog-sidebar right-sidebar">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-12">
                @isset($posts)
                <div class="blog-page">
                    <div class="page__header">
                        <h2>Post Feeds</h2>
                        {{-- <h2>Category Archives: HTML</h2> --}}
                    </div>
                    <!-- Start Single Post -->
                    @forelse ($posts as $post)
                        <article class="blog__post d-flex flex-wrap">
                            <div class="thumb">
                                <a href="{{ route('frontend.posts.show', $post->slug) }}">
                                    @if ($post->media->count() > 0)
                                        <img
                                            src="{{ asset("assets/posts/{$post->media->first()->file_name}") }}"
                                            alt="{{ Str::limit($post->title, 10, '...') }}" />
                                    @else
                                        <img
                                            src="{{ asset('assets/posts/default.jpg') }}"
                                            alt="Blog Image" />
                                    @endif
                                </a>
                            </div>
                            <div class="content">
                                <h4>
                                    <a href="{{ route('frontend.posts.show', $post->slug) }}">{{ $post->title }}</a>
                                </h4>
                                <ul class="post__meta">
                                    <li>
                                        <span>Posts by : </span>
                                        <a
                                            href="{{ route('frontend.authors.posts.show', $post->user->username) }}">
                                            {{ $post->user->name }}
                                        </a>
                                    </li>
                                    <li class="post_separator">/</li>
                                    <li>
                                        <a
                                            href="{{ route('frontend.archives.posts.show',"{$post->created_at->format('n')}-{$post->created_at->format('Y')}") }}">
                                            {{ $post->created_at->format('M d, Y') }}
                                        </a>
                                    </li>
                                </ul>
                                <p>{!! Str::limit($post->description, 145, '...') !!}</p>
                                @if ($post->tags->count())
                                    <ul class="post__meta">
                                        <li>
                                            <span>Tags: </span>
                                        </li>
                                        @foreach ($post->tags as $tag)
                                            <li>
                                                <a href="{{ route('frontend.posts.by.tag', $tag->slug) }}">
                                                    <span class="label label-info">{{ $tag->name }}{{ (!$loop->last)? ',': '' }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                <div class="blog__btn">
                                    <a href="{{ route('frontend.posts.show', $post->slug) }}">read more</a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="text-center">No Posts Found</div>
                    @endforelse
                    <!-- End Single Post -->
                </div>
                {!! $posts->appends(request()->input())->links() !!}
                @endisset
            </div>
            <div class="col-lg-3 col-12 md-mt-40 sm-mt-40">
                @include('partials.frontend.sidebar')
            </div>
        </div>
    </div>
</div>
<!-- End Blog Area -->
@endsection
