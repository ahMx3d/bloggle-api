@extends('layouts.admin')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Supervisors</h6>
    </div>
    <div class="card-body">
        @include('backend.supervisors.filters.index')
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">Image</th>
                        <th>Name</th>
                        <th>Contacts</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Dates</th>
                        <th class="text-center" style="width: 30px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td class="">
                            <img
                                src    = "{{ ($image=$user->user_image)? asset("assets/users/{$image}"): asset('assets/users/user.jpeg') }}"
                                class  = "img-fluid img-thumbnail center-block d-block mx-auto"
                                width  = "80"
                                alt    = "{{ Str::limit($user->username, 10, '...') }}" />
                        </td>
                        <td>
                            <a
                                class="d-block text-decoration-none"
                                href="{{ route('admin.supervisors.show', $user->id) }}">
                                {{ $user->name }}
                                <b class="d-block text-gray-400 mb-0">
                                    {{ $user->username }}
                                </b>
                            </p>
                        </td>
                        <td>
                            <span>{{ $user->email }}</span>
                            <p class="text-gray-400 mb-0">
                                <b>{{ $user->mobile }}</b>
                            </p>
                        </td>
                        <td>{{ $user->status() }}</td>
                        <td>
                            <span class="d-inline-block w-25">Created</span>
                            <span>: {{ $user->created_at->format('d-m-Y h:i A') }}</span>
                            <br>
                            <span class="d-inline-block w-25">Updated</span>
                            <span>: {{ $user->updated_at->diffForHumans() }}</span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a
                                    href="{{ route('admin.supervisors.edit', $user->id) }}"
                                    class="btn btn-primary btn-sm mr-1">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a
                                    href="javascript:void(0);"
                                    onclick="event.preventDefault();
                                            ((confirm('Are you sure to delete this supervisor?'))?
                                                document.getElementById('supervisor-delete-{{ $user->id }}').submit():
                                                    false);"
                                    class="btn btn-danger btn-sm ml-1">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <form
                                    action = "{{ route('admin.supervisors.destroy', $user->id) }}"
                                    method = "POST"
                                    class  = "d-none"
                                    id     = "supervisor-delete-{{ $user->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Supervisors Found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7">
                            <div class="d-flex">
                                <div>
                                    <a href="{{ route('admin.supervisors.create') }}" class="btn btn-primary">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        <span class="text">Add New Supervisor</span>
                                    </a>
                                </div>
                                @if ($users->count())
                                    <div class="ml-auto">
                                        {!! $users->appends(request()->input())->links() !!}
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
