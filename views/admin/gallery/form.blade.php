@extends('livecms::backend')

@section('form')
	@include('livecms::admin.partials.error')
    @include('livecms::admin.partials.postableForm', ['contentName' => 'content', 'model' => $gallery])
@stop

@section('content')
@include('livecms::admin.partials.form', ['width' => '12', 'files' => true])
@stop