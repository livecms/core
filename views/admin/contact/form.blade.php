@extends('livecms::backend')

@section('form')
    @include('livecms::admin.partials.error')
    {!! LiveCMS\FormBuilder\FormBuilder::model(${$base}, $groupName) !!}
@stop

@section('content')
@include('livecms::admin.partials.form', ['width' => '12', 'files' => true])
@stop