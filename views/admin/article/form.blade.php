@extends('livecms::backend')

@section('form')
	@include('livecms::partials.error')
	@include('livecms::partials.postableForm', ['model' => $article])

	<div class="row form-group">
		<div class="col-md-2">
			{!! Form::label('category', trans('livecms::livecms.category'), ['class' => 'control-label']) !!}
		</div>
		<div class="col-md-10">
			{!! Form::select('categories[]', $categories, $article->categories->pluck('id')->all(), ['class' => 'form-control', 'multiple' => true, 'data-tags' => true]) !!}
		</div>
	</div>

	<div class="row form-group">
		<div class="col-md-2">
			{!! Form::label('tag', 'Tag', ['class' => 'control-label']) !!}
		</div>
		<div class="col-md-10">
			{!! Form::select('tags[]', $tags, $article->tags->pluck('id')->all(), ['class' => 'form-control', 'multiple' => true, 'data-tags' => true]) !!}
		</div>
	</div>
@stop

@section('content')
@include('livecms::partials.form', ['width' => '12', 'files' => true])
@stop