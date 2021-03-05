@extends('layouts.admin')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Create Tag</h6>
        <div class="ml-auto">
            <a href="{{ route('admin.post_tags.index') }}" class="text-decoration-none">
                <span class="text">Tags Table</span>
                <span class="icon primary">
                    <i class="fa fa-home"></i>
                </span>
            </a>
        </div>
    </div>
    <div class="card-body">
        {!! Form::open(['route'=>'admin.post_tags.store', 'method'=>'POST']) !!}
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {!! Form::label('name', 'Name') !!}
                    {!! Form::text('name', old('name'), ['class'=>'form-control']) !!}
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::button('Add New', ['type' => 'submit', 'class'=>'btn btn-lg btn-block btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
