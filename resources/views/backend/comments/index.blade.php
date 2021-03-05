@extends('layouts.admin')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Comments</h6>
    </div>
    <div class="card-body">
        @include('backend.comments.filters.index')
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Author</th>
                        <th width="30%">Comment</th>
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
                        <td>
                            {!! $comment->comment !!}
                            <div class="text-muted">
                                <a
                                    class="text-decoration-none"
                                    href="{{ route('admin.posts.show', $comment->post_id) }}">
                                    {{ $comment->post->title }}
                                </a>
                            </div>
                        </td>
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
                @if ($comments->count())
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
@endsection
