{!! Form::open(['route'=>'admin.post_tags.index','method'=>'GET']) !!}
<div class="row">
    <div class="col-5">
        <div class="form-group">
            {!! Form::text('keyword', old('keyword', request()->input('keyword')), ['class'=>'form-control',
            'placeholder'=>'Search Here...']) !!}
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            {!! Form::select('sort_by', [''=>'Column Name', 'id'=>'ID', 'name'=>'Name', 'created_at'=>'Created At'], old('sort_by',
            request()->input('sort_by')), ['class'=>'form-control']) !!}
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            {!! Form::select('limit_by', [''=>'Rows NO.', '10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100'], old('limit_by',
            request()->input('limit_by')), ['class'=>'form-control']) !!}
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            {!! Form::select('order_by', [''=>'Order', 'asc'=>'Ascending', 'desc'=>'Deascending'], old('order_by',
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
