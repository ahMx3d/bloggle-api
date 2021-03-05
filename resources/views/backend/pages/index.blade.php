@extends('layouts.admin')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pages</h6>
    </div>
    <div class="card-body">
        @include('backend.pages.filters.index')
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th class="text-center" style="width: 30px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pages as $page)
                    <tr>
                        <td>
                            <a
                                class="text-decoration-none"
                                href="{{ route('admin.pages.show', $page->id) }}">
                                {{ Str::limit($page->title, 39, '...') }}
                            </a>
                        </td>
                        <td>{{ $page->status() }}</td>
                        <td>
                            <a
                                class="text-decoration-none"
                                href="{{ route('admin.pages.index', ['category_id'=>$page->category_id]) }}">
                                {{ $page->category->name }}
                            </a>
                        </td>
                        <td>{{ $page->user->username }}</td>
                        <td>{{ $page->created_at->format('d-m-Y h:i A') }}</td>
                        <td>{{ $page->updated_at->diffForHumans() }}</td>
                        <td>
                            <div class="btn-group">
                                <a
                                    href="{{ route('admin.pages.edit', $page->id) }}"
                                    class="btn btn-primary btn-sm mr-1">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a
                                    href="javascript:void(0);"
                                    onclick="event.preventDefault();
                                            ((confirm('Are you sure to delete this page?'))?
                                                document.getElementById('page-delete-{{ $page->id }}').submit():
                                                    false);"
                                    class="btn btn-danger btn-sm ml-1">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <form
                                    action = "{{ route('admin.pages.destroy', $page->id) }}"
                                    method = "POST"
                                    class  = "d-none"
                                    id     = "page-delete-{{ $page->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Pages Found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7">
                            <div class="d-flex">
                                <div>
                                    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        <span class="text">Add New Page</span>
                                    </a>
                                </div>
                                @if ($pages->count())
                                    <div class="ml-auto">
                                        {!! $pages->appends(request()->input())->links() !!}
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
