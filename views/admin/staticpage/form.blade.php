@extends('livecms::backend')

@section('form')
    @include('livecms::admin.partials.error')
    {!! LiveCMS\FormBuilder\FormBuilder::model(${$base}, $groupName, null, function ($builder) use ($staticpage, $parents) {
        $builder->setField('permalink', 'content');
        $builder->setField('url', 'permalink');
        $builder->addCustomField('permalink', '
            <div class="row form-group">
                <div class="col-md-2">'.
                    Form::label('permalink', trans('livecms::livecms.permalink'), ['class' => 'control-label']).'
                </div>
                <div class="col-md-10">'.
                    Form::text('permalink', $staticpage->permalink ? $staticpage->permalink->permalink : '', ['class' => 'form-control', 'placeholder' => url('path/sebagai/permalink')]).'
                </div>
            </div>
        ');
        $builder->addCustomField('parent', '
            <div class="row form-group">
                <div class="col-md-2">'.
                    Form::label('parent', trans('livecms::livecms.parent'), ['class' => 'control-label']).'
                </div>

                <div class="col-md-10">'.
                    Form::select('parent', $parents, $staticpage->parent_id, ['class' => 'form-control']).'
                </div>
            </div>
        ');
    }) !!}
@stop

@section('content')
@include('livecms::admin.partials.form', ['width' => '12', 'files' => true])
@stop