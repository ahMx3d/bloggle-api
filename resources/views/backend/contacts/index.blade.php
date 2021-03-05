@extends('layouts.admin')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Contacts</h6>
    </div>
    <div class="card-body">
        @include('backend.contacts.filters.index')
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th class="text-center" style="width: 30px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                    <tr>
                        <td>{{ $message->name }}</td>
                        <td>
                            <a
                                class="text-decoration-none"
                                href="{{ route('admin.contact_us.show', $message->id) }}">
                                {{ $message->title }}
                            </a>
                        </td>
                        <td>{{ $message->status() }}</td>
                        <td>{{ $message->created_at->diffForHumans() }}</td>
                        <td>
                            <div class="btn-group">
                                <a
                                    href="{{ route('admin.contact_us.show', $message->id) }}"
                                    class="btn btn-primary btn-sm mr-1">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a
                                    href="javascript:void(0);"
                                    onclick="event.preventDefault();
                                            ((confirm('Are you sure to delete this message?'))?
                                                document.getElementById('message-delete-{{ $message->id }}').submit():
                                                    false);"
                                    class="btn btn-danger btn-sm ml-1">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <form
                                    action = "{{ route('admin.contact_us.destroy', $message->id) }}"
                                    method = "POST"
                                    class  = "d-none"
                                    id     = "message-delete-{{ $message->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No Messages Found</td>
                    </tr>
                    @endforelse
                </tbody>
                @if ($messages->count()>5)
                    <tfoot>
                        <tr>
                            <th colspan="5">
                                <div class="float-right">
                                    {!! $messages->appends(request()->input())->links() !!}
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
