@extends('layouts.admin')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Posts</h6>
    </div>
    <div class="card-body">
        @include('backend.posts.filters.index')
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Comments</th>
                        <th>Status</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Created At</th>
                        <th class="text-center" style="width: 30px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                    <tr>
                        <td>
                            <a
                                class="text-decoration-none"
                                href="{{ route('admin.posts.show', $post->id) }}">
                                {{ Str::limit($post->title, 39, '...') }}
                            </a>
                        </td>
                        <td>
                            @if ($post->comment_able == 1)
                                <a
                                    class="text-decoration-none"
                                    href="{{ route('admin.post_comments.index', ['post_id'=> $post->id]) }}">
                                    {{ $post->commentsCount() }}
                                </a>
                            @else
                                <span>Forbidden</span>
                            @endif
                        </td>
                        <td>{{ $post->status }}</td>
                        <td>
                            <a
                                class="text-decoration-none"
                                href="{{ route('admin.posts.index', ['category_id'=>$post->category_id]) }}">
                                {{ $post->category->name }}
                            </a>
                        </td>
                        <td>{{ $post->user->username }}</td>
                        {{-- <td>{{ $post->created_at->format('d-m-Y') }}</td> --}}
                        {{-- <td>{{ $post->created_at->format('d-m-Y h:i a') }}</td> --}}
                        <td>{{ $post->created_at->diffForHumans() }}</td>
                        <td>
                            <div class="btn-group">
                                <a
                                    href="{{ route('admin.posts.edit', $post->id) }}"
                                    class="btn btn-primary btn-sm mr-1">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a
                                    href="javascript:void(0);"
                                    onclick="event.preventDefault();
                                            ((confirm('Are you sure to delete this post?'))?
                                                document.getElementById('post-delete-{{ $post->id }}').submit():
                                                    false);"
                                    class="btn btn-danger btn-sm ml-1">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <form
                                    action = "{{ route('admin.posts.destroy', $post->id) }}"
                                    method = "POST"
                                    class  = "d-none"
                                    id     = "post-delete-{{ $post->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Posts Found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7">
                            <div class="d-flex">
                                <div>
                                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        <span class="text">Add New Post</span>
                                    </a>
                                </div>
                                @if ($posts->count())
                                    <div class="ml-auto">
                                        {!! $posts->appends(request()->input())->links() !!}
                                    </div>
                                @endif
                            </div>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
