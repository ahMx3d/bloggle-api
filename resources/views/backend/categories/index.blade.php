@extends('layouts.admin')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Posts</h6>
    </div>
    <div class="card-body">
        @include('backend.categories.filters.index')
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Posts</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th class="text-center" style="width: 30px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                    <tr>
                        <td>
                            {{ Str::limit($category->name, 39, '...') }}
                        </td>
                        <td>
                            <a
                                class="text-decoration-none"
                                href="{{ route('admin.posts.index', ['category_id'=>$category->id]) }}">
                                {{ $category->posts_count }}
                            </a>
                        </td>
                        <td>{{ $category->status() }}</td>
                        <td>{{ $category->created_at->format('d-m-Y h:i A') }}</td>
                        <td>{{ $category->updated_at->diffForHumans() }}</td>
                        <td>
                            <div class="btn-group">
                                <a
                                    href="{{ route('admin.post_categories.edit', $category->id) }}"
                                    class="btn btn-primary btn-sm mr-1">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a
                                    href="javascript:void(0);"
                                    onclick="event.preventDefault();
                                            ((confirm('Are you sure to delete this category?'))?
                                                document.getElementById('category-delete-{{ $category->id }}').submit():
                                                    false);"
                                    class="btn btn-danger btn-sm ml-1">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <form
                                    action = "{{ route('admin.post_categories.destroy', $category->id) }}"
                                    method = "POST"
                                    class  = "d-none"
                                    id     = "category-delete-{{ $category->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No Categories Found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6">
                            <div class="d-flex">
                                <div>
                                    <a href="{{ route('admin.post_categories.create') }}" class="btn btn-primary">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        <span class="text">Add New Category</span>
                                    </a>
                                </div>
                                @if ($categories->count())
                                    <div class="ml-auto">
                                        {!! $categories->appends(request()->input())->links() !!}
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
