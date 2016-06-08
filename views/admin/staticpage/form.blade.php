@extends('livecms::backend')

@section('form')
	@include('livecms::admin.partials.error')
	@include('livecms::admin.partials.postableForm', ['model' => $staticpage])

	<div class="row form-group">
        <div class="col-md-2">
            {!! Form::label('parent', trans('livecms::livecms.parent'), ['class' => 'control-label']) !!}
        </div>

        <div class="col-md-10">
            {!! Form::select('parent', $parents, $staticpage->parent_id, ['class' => 'form-control']) !!}
        </div>
    </div>

@stop

@section('content')
@include('livecms::admin.partials.form', ['width' => '12', 'files' => true])
@stop