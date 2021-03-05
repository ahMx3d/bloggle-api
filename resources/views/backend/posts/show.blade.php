@extends('layouts.admin')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">{{ $post->title }}</h6>
        <div class="ml-auto">
            <a href="{{ route('admin.posts.index') }}" class="text-decoration-none">
                <span class="text">Posts Table</span>
                <span class="icon primary">
                    <i class="fa fa-home"></i>
                </span>
            </a>
        </div>
    </div>
    <div class="{{ ($post->media->count())? 'card-body': 'text-center' }}">
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
                                style="height: 400px"
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
        <div class="table-responsive">
            <table class="table table-hover" width="100%" cellspacing="0">
                <tbody>
                    <tr>
                        <th>Comments</th>
                        <td>{{ ($post->comment_able == 1)? $post->commentsCount(): 'Forbidden' }}</td>
                        <th>Status</th>
                        <td>{{ $post->status }}</td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>{{ $post->category->name }}</td>
                        <th>Author</th>
                        <td>{{ $post->user->username }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $post->created_at->format('d-m-Y h:i A') }}</td>
                        <th>Updated</th>
                        <td>{{ $post->updated_at->diffForHumans() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@if ($post->comments->count())
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Comments</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Author</th>
                            <th width="40%">Comment</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th class="text-center" style="width: 30px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($comments as $comment)
                        <tr>
                            <td>
                                <img
                                    class="img-fluid img-thumbnail"
                                    src="{{ get_gravatar($comment->email, 50) }}"
                                    alt={{ Str::limit($comment->name, 10, '...') }} />
                            </td>
                            <td>
                                <a
                                    href="{!! ($url=$comment->url)? $url: 'javascript:void(0);' !!}"
                                    @if ($comment->url)
                                        target="_blank"
                                    @endif
                                    class="text-decoration-none">
                                    {{ $comment->name }}
                                </a>
                                {{ ($comment->user_id)? '(Member)': '' }}
                            </td>
                            <td>{!! $comment->comment !!}</td>
                            <td>{{ $comment->status }}</td>
                            <td width="20%">{{ $comment->created_at->format('d-m-Y h:i A') }}</td>
                            <td width="15%">{{ $comment->updated_at->diffForHumans() }}</td>
                            <td>
                                <div class="btn-group">
                                    <a
                                        href="{{ route('admin.post_comments.edit', $comment->id) }}"
                                        class="btn btn-primary btn-sm mr-1">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a
                                        href="javascript:void(0);"
                                        onclick="event.preventDefault();
                                                ((confirm('Are you sure to delete this comment?'))?
                                                    document.getElementById('comment-delete-{{ $comment->id }}').submit():
                                                        false);"
                                        class="btn btn-danger btn-sm ml-1">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <form
                                        action = "{{ route('admin.post_comments.destroy', $comment->id) }}"
                                        method = "POST"
                                        class  = "d-none"
                                        id     = "comment-delete-{{ $comment->id }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No Comments Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if ($comments->count() > 0)
                        <tfoot>
                            <tr>
                                <th colspan="7">
                                    <div class="float-right">
                                        {!! $comments->appends(request()->input())->links() !!}
                                    </div>
                                </th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
