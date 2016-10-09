@extends('livecms::backend')

@section('form')
    @include('livecms::admin.partials.error')
    {!! LiveCMS\FormBuilder\FormBuilder::model(${$base}, $groupName) !!}
    <hr>
    <div class="row form-group">
        {!! Form::label('mediasocial', trans('livecms::livecms.mediasocial'), ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-9">
            @foreach ($contact->socialMedias() as $social)
            <div class="row form-group">
                <label for="social-{{$social}}" class="col-xs-2 control-label">
                    <a href="javascript:;" class="btn btn-sm btn-social-icon btn-{{$social == 'google-plus' ? 'google' : $social}}"><i class="fa fa-{{$social}}"></i></a>
                </label>
                <div class="col-xs-10">
                    {!! Form::text('socials['.$social.']', $contact->getSocials($social), ['class' => 'form-control', 'placeholder' => title_case($social).' '.trans('livecms::livecms.url')]) !!}
                </div>
            </div>
            @endforeach
        </div>
    </div>
@stop

@section('content')
@include('livecms::admin.partials.form', ['width' => '12', 'files' => true])
@stop