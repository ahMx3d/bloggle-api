@extends('layouts.admin')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Edit Category</h6>
        <div class="ml-auto">
            <a href="{{ route('admin.post_categories.index') }}" class="text-decoration-none">
                <span class="text">Categories Table</span>
                <span class="icon primary">
                    <i class="fa fa-home"></i>
                </span>
            </a>
        </div>
    </div>
    <div class="card-body">
        {!! Form::model($category, ['route'=>['admin.post_categories.update', $category->id], 'method'=>'PUT']) !!}
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    {!! Form::label('name', 'Name') !!}
                    {!! Form::text('name', old('name', $category->name), ['class'=>'form-control']) !!}
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-4">
                {!! Form::label('status', 'Status') !!}
                {!! Form::select('status', ['1'=>'Active','0'=>'Pending'], old('status', $category->status), ['class'=>'form-control']) !!}
                @error('status')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="form-group">
            {!! Form::button('Update Category', ['type' => 'submit', 'class'=>'btn btn-lg btn-block btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
