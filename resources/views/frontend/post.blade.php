@extends('layouts.app')
@section('content')
<div class="page-blog-details section-padding--lg bg--white">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-12">
                <div class="blog-details content">
                    <article class="blog-post-details">
                        @if ($post->media->count()>0)
                        <div id="carouselIndicators" class="carousel slide post-thumbnail" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @foreach ($post->media as $medium)
                                <li data-target="#carouselIndicators" data-slide-to="{{ $loop->index }}" class="{{ ($loop->index == 0)? 'active': ''}}"></li>
                                @endforeach
                            </ol>
                            <div class="carousel-inner">
                                @foreach ($post->media as $medium)
                                <div class="carousel-item {{ ($loop->index == 0)? 'active': ''}}">
                                    <img
                                        class="d-block w-100 img-fluid img-thumbnail"
                                        style="height: 465px"
                                        src="{{ asset("assets/posts/{$medium->file_name}") }}"
                                        alt="{{ Str::limit($post->title, 10, '...') }}" />
                                </div>
                                @endforeach
                            </div>
                            @if ($post->media->count()>1)
                            <a class="carousel-control-prev" href="#carouselIndicators" role="button"
                                data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselIndicators" role="button"
                                data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                            @endif
                        </div>
                        @endif
                        {{-- <div class="post-thumbnail">
                            <img
                                src="{{ asset('frontend/images/blog/big-img/1.jpg') }}"
                                alt="blog images" />
                        </div> --}}
                        <div class="post_wrapper">
                            <div class="post_header">
                                <h2>{{ $post->title }}</h2>
                                <div class="blog-date-categori">
                                    <ul>
                                        <li>{{ $post->created_at->format('M d, Y') }}</li>
                                        <li>
                                            <a
                                                href="{{ route('frontend.authors.posts.show', $post->user->username) }}"
                                                title="Posts by {{ $post->user->name }}"
                                                rel="author">{!! Str::ucfirst($post->user->name) !!}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="post_content">
                                <p>{!! $post->description !!}</p>
                            </div>
                            <ul class="blog_meta">
                                <li>Category: <span>{{ $post->category->name }}</span></li>
                                @if ($post->tags->count())
                                    <li> / </li>
                                    <li>
                                        <span>Tags: </span>
                                    </li>
                                    @foreach ($post->tags as $tag)
                                        <li>
                                            <a
                                                href="{{ route('frontend.posts.by.tag', $tag->slug) }}"
                                                class="bg-info p-1">
                                                <span class="text-white">{{ $tag->name }}{{ (!$loop->last)? ',': '' }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </article>
                    <div class="comments_area">
                        <h3 class="comment__title">{{ $post->approved_comments->count() }} comment{{ ($post->approved_comments->count()>1 || $post->approved_comments->count()==0)? 's': '' }}</h3>
                        <ul class="comment__list">
                            @forelse ($post->approved_comments as $comment)
                            <li>
                                <div class="wn__comment">
                                    <div class="thumb">
                                        <img src="{{ get_gravatar($comment->email, 46) }}"
                                            alt="{{ Str::limit($comment->name, 10, '...') }}" />
                                    </div>
                                    <div class="content">
                                        <div class="comnt__author d-block d-sm-flex">
                                            <span><a href="{{ ($comment->url)? $comment->url: 'javascript:void(0);' }}">{{ $comment->name }}</a></span>
                                            <span>{{ $comment->created_at->format('M d, Y \a\t h:i a') }}</span>
                                        </div>
                                        <p>{{ $comment->comment }}</p>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li>
                                <div class="wn__comment">
                                    <div class="content">
                                        <p>No Comments Found.</p>
                                    </div>
                                </div>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="comment_respond">
                        <h3 class="reply_title">Leave a Reply <small></small></h3>
                        {!! Form::open([
                            'route'  => [
                                'frontend.posts.comments.add',
                                $post->slug
                            ],
                            'method' => 'POST',
                            'class'  => 'comment__form',
                            'id'     => 'comment-form'
                        ]) !!}
                            <p>Your email address will not be published.Required fields are marked </p>
                            <div class="input__box">
                                {!! Form::textarea('comment', old('comment'), ['placeholder' => 'Your comment here']) !!}
                                @error('comment')
                                <span class="text-danger text-center">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input__wrapper clearfix">
                                <div class="input__box name one--third">
                                    {!! Form::text('name', old('name'), ['placeholder' => 'Your name here']) !!}
                                    @error('name')
                                    <span class="text-danger text-center">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input__box email one--third">
                                    {!! Form::email('email', old('email'), ['placeholder' => 'Your email herer']) !!}
                                    @error('email')
                                    <span class="text-danger text-center">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input__box website one--third">
                                    {!! Form::url('url', old('url'), ['placeholder' => 'Your URL here']) !!}
                                    @error('url')
                                    <span class="text-danger text-center">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="submite__btn">
                                <a
                                    onclick="document.getElementById('comment-form').submit();"
                                    href="javascript:void(0);">Post Comment</a>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12 md-mt-40 sm-mt-40">
                @include('partials.frontend.sidebar')
            </div>
        </div>
    </div>
</div>
@endsection
