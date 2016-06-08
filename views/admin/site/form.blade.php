@extends('livecms::backend')

@section('form')
	
	@include('livecms::admin.partials.error')
	<div class="row form-group">
		<div class="col-md-3">
			{!! Form::label('sitename', trans('livecms::livecms.sitename'), ['class' => 'control-label']) !!}
		</div>
		<div class="col-md-9">
			{!! Form::text('sitename', $site->site, ['class' => 'form-control']) !!}
		</div>
	</div>

	<div class="row form-group">
		<div class="col-md-3">
			{!! Form::label('subdomain', trans('livecms::livecms.subdomain'), ['class' => 'control-label']) !!}
		</div>
		<div class="col-md-9">
			{!! Form::text('subdomain', $site->subdomain, ['class' => 'form-control']) !!}
		</div>
	</div>

	<div class="row form-group">
		<div class="col-md-3">
			{!! Form::label('subfolder', trans('livecms::livecms.subfolder'), ['class' => 'control-label']) !!}
		</div>
		<div class="col-md-9">
			{!! Form::text('subfolder', $site->subfolder, ['class' => 'form-control']) !!}
		</div>
	</div>

	<hr>
	
	<div class="row">
		<div class="col-md-12">
			<h5>
				{{trans('livecms::backend.administrators')}}
			</h5>
		</div>
	</div>

	@if (count($site->admins))
	
	@foreach ($site->admins as $key => $admin)
	<div class="row form-group">
		<div class="col-md-3">
			{!! Form::label('email_'.$key, trans('livecms::livecms.email'), ['class' => 'control-label']) !!}
		</div>
		<div class="col-md-9">
			{!! Form::text('emails_'.$key, $admin->email, ['class' => 'form-control']) !!}
		</div>
	</div>
	@endforeach

	@else
	
	<div class="row form-group">
		<div class="col-md-3">
			{!! Form::label('email', trans('livecms::livecms.email'), ['class' => 'control-label']) !!}
		</div>
		<div class="col-md-9">
			{!! Form::text('email', null, ['class' => 'form-control']) !!}
		</div>
	</div>

	@endif

@stop

@section('content')
@include('livecms::admin.partials.form')
@stop