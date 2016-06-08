@extends('livecms::backend')

@section('form')

	@include('livecms::admin.partials.error')
	<div class="row form-group">
		<div class="col-md-4">
			{!! Form::label('permalink', url('/').'/', ['class' => 'control-label']) !!}
		</div>
		<div class="col-md-8">
			{!! Form::text('permalink', $permalink->permalink, ['class' => 'form-control']) !!}
		</div>
	</div>

@stop

@section('content')
@include('livecms::admin.partials.form')
@stop