@extends('livecms::backend')

@section('form')
    @include('livecms::admin.partials.error')
    @include('livecms::admin.partials.postableForm', ['contentName' => 'decription', 'model' => $team])
    <hr>
    <div class="row form-group">
        {!! Form::label('mediasocial', trans('livecms::livecms.mediasocial'), ['class' => 'col-md-2 control-label']) !!}
        <div class="col-md-10">
            @foreach ($socials as $social => $socialTitle)
            <div class="row form-group">
                <label for="{{$social}}" class="col-xs-1">
                    <a href="javascript:;" class="btn btn-sm btn-social-icon btn-{{$social == 'google-plus' ? 'google' : $social}}"><i class="fa fa-{{$social}}"></i></a>
                </label>
                <div class="col-sm-8">
                    {!! Form::text('socials['.$social.']', ($socialInfo = $team->socials()->where('social', $social)->first()) ? $socialInfo->url : '', ['class' => 'form-control', 'placeholder' => title_case($social).' '.trans('livecms::livecms.url')]) !!}
                </div>
            </div>
            @endforeach
        </div>
    </div>
@stop

@section('content')
@include('livecms::admin.partials.form', ['width' => '12', 'files' => true])
@stop