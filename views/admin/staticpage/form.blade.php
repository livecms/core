@extends('livecms::backend')

@section('form')
    @include('livecms::admin.partials.error')
    {!! LiveCMS\FormBuilder\FormBuilder::model(${$base}, $groupName, null, function ($builder) use ($staticpage, $parents) {
        $builder->setField('permalink', 'content');
        $builder->setField('url', 'permalink');
        $builder->addCustomField('permalink', Form::text('permalink', $staticpage->permalink ? $staticpage->permalink->permalink : '', ['class' => 'form-control', 'placeholder' => url('path/sebagai/permalink')]));
        $builder->addCustomField('parent', Form::select('parent', $parents, $staticpage->parent_id, ['class' => 'form-control']));
    }) !!}
@stop

@section('content')
@include('livecms::admin.partials.form', ['width' => '12', 'files' => true])
@stop