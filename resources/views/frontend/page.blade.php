@extends('layouts.app')
@section('content')
@if ($page->slug == 'contact-us')
@include('partials.frontend.contact')
@else
<div class="page-blog-details section-padding--lg bg--white">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="blog-details content">
                    <article class="blog-post-details">
                        @if ($page->media->count()>0)
                        <div id="carouselIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @foreach ($page->media as $medium)
                                <li data-target="#carouselIndicators" data-slide-to="{{ $loop->index }}" class="{{ ($loop->index == 0)? 'active': ''}}"></li>
                                @endforeach
                            </ol>
                            <div class="carousel-inner">
                                @foreach ($page->media as $medium)
                                <div class="carousel-item {{ ($loop->index == 0)? 'active': ''}}">
                                    <img
                                        class="d-block w-100"
                                        src="{{ asset("assets/posts/{$medium->file_name}") }}"
                                        alt="{{ Str::limit($page->title, 10, '...') }}" />
                                </div>
                                @endforeach
                            </div>
                            @if ($page->media->count()>1)
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
                        <div class="post_wrapper">
                            <div class="post_header">
                                <h2>{{ $page->title }}</h2>
                            </div>
                            <div class="post_content">
                                <p>{!! $page->description !!}</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
