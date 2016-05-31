@extends('livecms::backend')

@section('form')
	@include('livecms::partials.error')
	@include('livecms::partials.postableForm', ['model' => $gallery])
@stop

@section('content')
@include('livecms::partials.form', ['width' => '12', 'files' => true])
@stop