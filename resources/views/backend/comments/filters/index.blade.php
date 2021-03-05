{!! Form::open(['route'=>'admin.post_comments.index','method'=>'GET']) !!}
<div class="row">
    <div class="col-2">
        <div class="form-group">
            {!! Form::text('keyword', old('keyword',
            request()->input('keyword')), ['class'=>'form-control',
            'placeholder'=>'Search Here...']) !!}
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            {!! Form::select('post_id', [''=>'Post Name']+$posts, old('post_id',
            request()->input('post_id')), ['class'=>'form-control']) !!}
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            {!! Form::select('sort_by', [''=>'Columns', 'id'=>'ID', 'name'=>'Author',
            'created_at'=>'Created At'], old('sort_by',
            request()->input('sort_by')), ['class'=>'form-control']) !!}
        </div>
    </div>
    <div class="col-1">
        <div class="form-group">
            {!! Form::select('limit_by', [''=>'NO.', '10'=>'10', '20'=>'20', '50'=>'50',
            '100'=>'100'], old('limit_by',
            request()->input('limit_by')), ['class'=>'form-control']) !!}
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            {!! Form::select('status', [''=>'Status', '1'=>'Active', '0'=>'Pending'],
            old('status', request()->input('status')), ['class'=>'form-control']) !!}
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            {!! Form::select('order_by', [''=>'Order', 'asc'=>'Ascending',
            'desc'=>'Deascending'], old('order_by',
            request()->input('order_by')), ['class'=>'form-control']) !!}
        </div>
    </div>
    <div class="col-1">
        <div class="form-group">
            {!! Form::button('Filter', ['type'=>'submit', 'class'=>'btn btn-primary']) !!}
        </div>
    </div>
</div>
{!! Form::close() !!}
