@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-3">
        <div class="card">
            <div class="card-header">Settings</div>
            <ul class="list-group list-group-flush">
                @foreach ($st_sections as $st_section)
                    <li class="list-group-item">
                        <a
                            href="{{ route('admin.settings.index', ['section' => $st_section]) }}"
                            class="nav-link">
                            <i class="fa fa-gear"></i>
                            <span>{{ ucwords(str_replace('_', ' ', $st_section)) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-9">
        <div class="card">
            <div class="card-header">{{ ucwords(str_replace('_', ' ', $section)) }} Settings </div>
            <div class="card-body">
                {!! Form::model($settings, [
                    'route'  => [
                        'admin.settings.update',
                        1
                    ],
                    'method' => 'PATCH',
                    'files'  => true
                ]) !!}
                    @foreach ($settings as $setting)
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    {!! Form::label('title', $setting->display_name, []) !!}
                                    @if ($setting->type == 'text')
                                        {!! Form::text("value[{$loop->index}]", $setting->value,[
                                            'id'    => 'value',
                                            'class' => 'form-control'
                                        ]) !!}

                                    @elseif ($setting->type == 'textarea')
                                        {!! Form::textarea("value[{$loop->index}]", $setting->value, [
                                            'id'    => 'value',
                                            'class' => 'form-control',
                                            'cols'  => '30',
                                            'rows'  => '10'
                                        ]) !!}

                                    @elseif ($setting->type == 'image')
                                        {!! Form::file("value[{$loop->index}]", [
                                            'id'    => 'value',
                                            'class' => 'form-control'
                                        ]) !!}

                                    @elseif ($setting->type == 'select')
                                        {!! Form::select("value[{$loop->index}]",
                                            explode('|', $setting->details), $setting->value, [
                                                'id'    => 'value',
                                                'class' => 'form-control'
                                        ]) !!}

                                    @elseif ($setting->type == 'checkbox')
                                        {!! Form::checkbox("value[{$loop->index}]", 1,
                                            (($setting->value == 1)? true: false), [
                                                'id'    => 'value',
                                                'class' => 'styled'
                                        ]) !!}

                                    @elseif ($setting->type == 'radio')
                                        {!! Form::radio("value[{$loop->index}]", 1,
                                            (($setting->value == 1)? true: false), [
                                                'id'    => 'value',
                                                'class' => 'styled'
                                        ]) !!}
                                    @endif
                                    {!! Form::hidden("key[{$loop->index}]", $setting->key, [
                                        'id'       => 'key',
                                        'class'    => 'form-control d-none',
                                        'readonly' => true,
                                    ]) !!}
                                    {!! Form::hidden("id[{$loop->index}]", $setting->id, [
                                        'id'       => 'key',
                                        'class'    => 'form-control d-none',
                                        'readonly' => true,
                                    ]) !!}
                                    {!! Form::hidden("ordering[{$loop->index}]", $setting->ordering, [
                                        'id'       => 'key',
                                        'class'    => 'form-control d-none',
                                        'readonly' => true,
                                    ]) !!}
                                    @error('value')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-right">
                        {!! Form::button('Submit', [
                            'type'  => 'submit',
                            'class' => 'btn btn-primary'
                        ]) !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
