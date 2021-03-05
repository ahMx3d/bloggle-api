@extends('layouts.admin')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">{{ $message->title }}</h6>
        <div class="ml-auto">
            <a href="{{ route('admin.contact_us.index') }}" class="text-decoration-none">
                <span class="text">Contacts Table</span>
                <span class="icon primary">
                    <i class="fa fa-home"></i>
                </span>
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" width="100%" cellspacing="0">
                <tbody>
                    <tr>
                        <th>Title</th>
                        <td>{{ $message->title }}</td>
                    </tr>
                    <tr>
                        <th>From</th>
                        <td>{{ $message->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><strong>&#60;{{ $message->email }}&#62;</strong></td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td>{!! $message->message !!}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
