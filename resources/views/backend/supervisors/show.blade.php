@extends('layouts.admin')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">{{ $user->name }}</h6>
        <div class="ml-auto">
            <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                <span class="text">Users Table</span>
                <span class="icon primary">
                    <i class="fa fa-home"></i>
                </span>
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="position-relative">
            <img
                src="{{ ($image=$user->user_image)? asset("assets/users/{$image}"): asset('assets/users/user.jpeg') }}"
                class="d-block w-50 mx-auto img-fluid img-thumbnail mb-4"
                alt="{{ Str::limit($user->username, 10, '...') }}" />
            @if ($user->user_image)
                <button
                    style="top:0;right:0;"
                    class="position-absolute btn btn-danger remove-image">
                    Remove Image
                </button>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-hover" width="100%" cellspacing="0">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td>{{ $user->name }}</td>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td>{{ $user->username }}</td>
                        <th>Posts</th>
                        <td>{{ $user->posts_count }}</td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td>{{ $user->mobile }}</td>
                        <th>Status</th>
                        <td>{{ $user->status() }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $user->created_at->format('d-m-Y h:i A') }}</td>
                        <th>Updated</th>
                        <td>{{ $user->updated_at->diffForHumans() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $(function () {
            $('.remove-image').click(function () {
                $.post("{{ route('admin.users.media.destroy') }}",
                    {
                        user_id: '{{ $user->id }}',
                        _token : '{{ csrf_token() }}'
                    },
                    function (data, textStatus, jqXHR) {
                        if (data == true) window.location.href = window.location;
                    }
                );
            });
        });
    </script>
@endsection
