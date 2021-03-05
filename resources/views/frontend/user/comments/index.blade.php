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
                                <th class="product-thumbnail">Avatar</th>
                                <th class="product-price">User</th>
                                <th class="product-name">Post</th>
                                <th class="product-quantity">Status</th>
                                <th class="product-add-to-cart">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($comments as $comment)
                            <tr>
                                <td class="product-thumbnail">
                                    <a href="{{ route('frontend.posts.show', $comment->post->slug) }}">
                                        <img
                                            src="{{ get_gravatar($comment->email, 80) }}"
                                            alt="{{ Str::limit($comment->name, 10, '...') }}" />
                                    </a>
                                </td>
                                <td class="product-price">
                                    <a
                                        href="{{ route('frontend.posts.show', $comment->post->slug) }}">
                                        {{ $comment->name }}
                                    </a>
                                </td>
                                <td class="product-name">
                                    <span class="amount">
                                        <a href="{{ route('frontend.posts.show', $comment->post->slug) }}">
                                            {{ $comment->post->title }}
                                        </a>
                                    </span>
                                </td>
                                <td class="product-quantity">{{ $comment->status }}</td>
                                <td class="product-add-to-cart">
                                    <a
                                        href="{{ route('user.comment.edit', $comment->id) }}"
                                        class="mb-1">
                                        Edit
                                    </a>
                                    <a
                                        class="mt-1"
                                        onclick="
                                            if(confirm('Are you sure to delete this comment?')){
                                                document.getElementById('comment-delete-{{ $comment->id }}').submit()
                                            }else{return false;}"
                                        href="javascript:void(0);">
                                        Delete
                                    </a>
                                    <form
                                        id="comment-delete-{{ $comment->id }}"
                                        action="{{ route('user.comment.delete', $comment->id) }}"
                                        method="POST"
                                        class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <td colspan="5">No Comments Found</td>
                            @endforelse
                        </tbody>
                        @if ($comments->count() > 0)
                        <tfoot>
                            <tr>
                                <td colspan="5">{!! $comments->appends(request()->input())->links() !!}</td>
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
