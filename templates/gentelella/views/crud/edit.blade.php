<?php
$source = LC_CurrentTheme().'.views.layout';
$targetView = 'livecms-templates::'.$source; ?>
@extends($targetView)

@section('content')
<div class="row">
    <div class="col-sm-10">
        <h3 class="x_panel-title">{{__('Edit :resource_title', ['resource_title' => ResTitle()])}}</h3>
        <div class="x_panel-button">
            <a href="{{ResRoute('index')}}" class="btn btn-default" title="{{__('Back To Index')}}">
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>
        <div class="x_panel">
            <div class="x_content">
                {!! Form::open(['method' => 'PUT', 'url' => ResRoute('update', ['id' => ResModel()->id]), 'id' => 'resource_form']) !!}
                {!! Form::render('form') !!}
                <button type="submit" class="btn btn-primary pull-right">{{__('Save')}}</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-bottom')
<script>
@if ($errors->any())
const form_errors = {!! json_encode($errors->toArray()) !!};
const validation_identifier = '{{old('_identifier')}}';
const validation_title = '{{ __('Validation Failed.') }}';
@endif

{!! Form::javascript() !!}

if (typeof form_errors != 'undefined') {
{!! ResValidationJS() !!}
}
</script>
@endpush
