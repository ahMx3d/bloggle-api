@extends('layouts.admin')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tags</h6>
    </div>
    <div class="card-body">
        @include('backend.tags.filters.index')
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Posts</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th class="text-center" style="width: 30px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tags as $tag)
                    <tr>
                        <td>
                            {{ Str::limit($tag->name, 39, '...') }}
                        </td>
                        <td>
                            <a
                                class="text-decoration-none"
                                href="{{ route('admin.posts.index', ['tag_id'=>$tag->id]) }}">
                                {{ $tag->posts_count }}
                            </a>
                        </td>
                        <td>{{ $tag->created_at->format('d-m-Y h:i A') }}</td>
                        <td>{{ $tag->updated_at->diffForHumans() }}</td>
                        <td>
                            <div class="btn-group">
                                <a
                                    href="{{ route('admin.post_tags.edit', $tag->id) }}"
                                    class="btn btn-primary btn-sm mr-1">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a
                                    href="javascript:void(0);"
                                    onclick="event.preventDefault();
                                            ((confirm('Are you sure to delete this tag?'))?
                                                document.getElementById('tag-delete-{{ $tag->id }}').submit():
                                                    false);"
                                    class="btn btn-danger btn-sm ml-1">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <form
                                    action = "{{ route('admin.post_tags.destroy', $tag->id) }}"
                                    method = "POST"
                                    class  = "d-none"
                                    id     = "tag-delete-{{ $tag->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No Tags Found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6">
                            <div class="d-flex">
                                <div>
                                    <a href="{{ route('admin.post_tags.create') }}" class="btn btn-primary">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        <span class="text">Add New Tag</span>
                                    </a>
                                </div>
                                @if ($tags->count())
                                    <div class="ml-auto">
                                        {!! $tags->appends(request()->input())->links() !!}
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
