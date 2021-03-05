@section('style')
    <style>
        .global-tags {
            background    : #ebebeb none repeat scroll 0 0;
            color         : #333;
            display       : inline-block;
            font-size     : 12px;
            line-height   : 20px;
            margin        : 5px 5px 0 0;
            padding       : 5px 15px;
            text-transform: capitalize;
        }
    </style>
@endsection
<div class="wn__sidebar">
    <!-- Start Single Widget -->
    <aside class="widget search_widget">
        <h3 class="widget-title">Search</h3>
        {!! Form::open(['route'=>'frontend.search','method'=>'GET']) !!}
        <div class="form-input">
            {!! Form::text('keyword', old('keyword', request()->keyword), ['placeholder'=>'Search...']) !!}
            {!! Form::button('<i class="fa fa-search"></i>', ['type'=>'submit']) !!}
        </div>
        {!! Form::close() !!}
    </aside>
    <!-- End Single Widget -->
    <!-- Start Single Widget -->
    <aside class="widget category_widget">
        <h3 class="widget-title">tags</h3>
        <ul>
            @foreach($global_tags as $global_tag)
                <span class="global-tags">
                    <a href="{{ route('frontend.posts.by.tag', $global_tag->slug) }}">
                        {{ $global_tag->name }} ({{ $global_tag->posts_count }})
                    </a>
                </span>
            @endforeach
        </ul>
    </aside>
    <!-- End Single Widget -->
    <!-- Start Single Widget -->
    <aside class="widget recent_widget">
        <h3 class="widget-title">Most Recently</h3>
        <div class="recent-posts">
            <ul>
                @foreach ($recent_posts as $recent_post)
                <li>
                    <div class="post-wrapper d-flex">
                        <div class="thumb">
                            <a href="{{ route('frontend.posts.show', $recent_post->slug) }}">
                                @if ($recent_post->media->count() > 0)
                                    <img
                                        src="{{ asset("assets/posts/{$recent_post->media->first()->file_name}") }}"
                                        alt="{{ Str::limit($recent_post->title, 10, '...') }}" />
                                @else
                                    <img
                                        src="{{ asset('assets/posts/default_sm.jpg') }}"
                                        alt="Blog Image" />
                                @endif
                        </div>
                        <div class="content">
                            <h4>
                                <a href="{{ route('frontend.posts.show', $recent_post->slug) }}">
                                    {{ Str::limit($recent_post->title, 20, '...') }}
                                </a>
                            </h4>
                            <p> {{ $recent_post->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </aside>
    <!-- End Single Widget -->
    <!-- Start Single Widget -->
    <aside class="widget comment_widget">
        <h3 class="widget-title">Comments</h3>
        <ul>
            @foreach ($recent_comments as $recent_comment)
            <li>
                <div class="post-wrapper">
                    <div class="thumb">
                            <img
                                src="{{ get_gravatar($recent_comment->email, 47) }}"
                                alt="{{ Str::limit($recent_comment->name, 10, '...') }}" />
                    </div>
                    <div class="content">
                        <p>{{ $recent_comment->name }} says:</p>
                        <a href="{{ route('frontend.posts.show', $recent_comment->post->slug) }}">
                            {{  Str::limit($recent_comment->comment, 20, '...')  }}
                        </a>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </aside>
    <!-- End Single Widget -->
    <!-- Start Single Widget -->
    <aside class="widget category_widget">
        <h3 class="widget-title">Categories</h3>
        <ul>
            @foreach ($sidebar_categories as $category)
            <li>
                <a
                    href="{{ route('frontend.categories.posts.show', $category->slug) }}">
                    {{ $category->name }}
                </a>
            </li>
            @endforeach
        </ul>
    </aside>
    <!-- End Single Widget -->
    <!-- Start Single Widget -->
    <aside class="widget archives_widget">
        <h3 class="widget-title">Archives</h3>
        <ul>
            @foreach ($sidebar_archives as $month => $year)
            <li>
                <a
                    {{-- href="{{ route('frontend.archives.posts.show',(($month!=1)? intval($month)-1: 12).'-'.$year) }}">
                    {{ date('F', mktime(0,0,0,$month-1,1)). ' ' .$year }} --}}
                    href="{{ route('frontend.archives.posts.show',"{$month}-{$year}") }}">
                    {{ date('F', mktime(0,0,0,$month,1)). ' ' .$year }}
                </a>
            </li>
            @endforeach
        </ul>
    </aside>
    <!-- End Single Widget -->
</div>
