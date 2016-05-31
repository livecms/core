@extends('livecms::backend')

@section('form')
    @include('livecms::partials.error')

    @foreach($contact->getFillable() as $attribute)
    <div class="row form-group">
        <div class="col-md-2">
            {!! Form::label($attribute, trans('livecms::livecms.'.$attribute), ['class' => 'control-label']) !!}
        </div>
        <div class="col-md-10">
            {!! Form::text($attribute, $contact->$attribute, ['class' => 'form-control']) !!}
        </div>
    </div>
    @endforeach

    
@stop

@section('content')
@include('livecms::partials.form', ['width' => '12', 'files' => true])
@stop