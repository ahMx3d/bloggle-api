@extends('layouts.app')
@section('content')
<!-- cart-main-area start -->
<div class="cart-main-area section-padding--lg bg--white">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-12">
                <div class="table-content wnro__table table-responsive">
                    <table>
                        <thead>
                            <tr class="title-top">
                                <th class="product-thumbnail">Image</th>
                                <th class="product-name">Title</th>
                                <th class="product-price">Comments</th>
                                <th class="product-quantity">Status</th>
                                <th class="product-add-to-cart">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($posts as $post)
                            <tr>
                                <td class="product-thumbnail">
                                    <a href="{{ route('frontend.posts.show', $post->slug) }}">
                                        <img
                                            src="{{
                                                isset($post->media->first()->file_name)? asset(
                                                    "assets/posts/{$post->media->first()->file_name}"
                                                ): asset(
                                                    "assets/posts/table_sm.jpg"
                                                )
                                            }}"
                                            alt="{{ Str::limit($post->title, 10, '...') }}" />
                                    </a>
                                </td>
                                <td class="product-name">
                                    <a
                                        href="{{ route('frontend.posts.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </td>
                                <td class="product-price">
                                    <span class="amount">
                                        <a href="{{ route('user.comments.index', ['post'=>$post->id]) }}">
                                            {{ $post->comments_count }}
                                        </a>
                                    </span>
                                </td>
                                <td class="product-quantity">{{ $post->status }}</td>
                                <td class="product-add-to-cart">
                                    <a
                                        href="{{ route('user.posts.edit', $post->slug) }}"
                                        class="mb-1">
                                        Edit
                                    </a>
                                    <a
                                        class="mt-1"
                                        onclick="
                                            if(confirm('Are you sure to delete this post?')){
                                                document.getElementById('post-delete-{{ $post->id }}').submit()
                                            }else{return false;}"
                                        href="javascript:void(0);">
                                        Delete
                                    </a>
                                    <form
                                        id="post-delete-{{ $post->id }}"
                                        action="{{ route('user.posts.delete', $post->slug) }}"
                                        method="POST"
                                        class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <td colspan="5">No Posts Found</td>
                            @endforelse
                        </tbody>
                        @if ($posts->count() > 5)
                        <tfoot>
                            <tr>
                                <td colspan="5">{!! $posts->appends(request()->input())->links() !!}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
            <div class="col-lg-3 col-12 md-mt-40 sm-mt-40">
                @include('partials.frontend.user.sidebar')
            </div>
        </div>
    </div>
</div>
<!-- cart-main-area end -->
@endsection
