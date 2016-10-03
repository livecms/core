@extends('livecms::backend')

@section('form')
	@include('livecms::admin.partials.error')
	@include('livecms::admin.partials.postableForm', ['contentName' => 'content', 'model' => $article])
@stop

@section('content')
@include('livecms::admin.partials.form', ['width' => '10', 'files' => true])
@stop